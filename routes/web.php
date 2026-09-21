<?php

use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MemorialController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TimelineEntryController;
use App\Http\Controllers\Admin\TributeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public tribute site (PRD section 8.1)
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/biography', [PageController::class, 'biography'])->name('biography');
Route::get('/timeline', [PageController::class, 'timeline'])->name('timeline');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/brochure', [PageController::class, 'downloadBrochure'])->name('brochure.download');

Route::get('/tributes', [PageController::class, 'tributes'])->name('tributes.index');
Route::post('/tributes', [PageController::class, 'storeTribute'])
    ->middleware('throttle:6,1')
    ->name('tributes.store');

Route::post('/remembrances', [PageController::class, 'storeRemembrance'])
    ->middleware('throttle:10,1')
    ->name('remembrances.store');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'storeContact'])
    ->middleware('throttle:6,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| Admin authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'create'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'store']);
});
Route::post('/admin/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin dashboard (PRD section 5) — every route requires authentication;
| individual routes are further restricted by role.
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:super_admin,memorial_editor')->group(function () {
        Route::get('/memorial', [MemorialController::class, 'edit'])->name('memorial.edit');
        Route::put('/memorial', [MemorialController::class, 'update'])->name('memorial.update');

        Route::resource('timeline', TimelineEntryController::class)
            ->parameters(['timeline' => 'timelineEntry'])
            ->only(['index', 'store', 'update', 'destroy']);

        Route::post('timeline/reorder', [TimelineEntryController::class, 'reorder'])
            ->name('timeline.reorder');

        Route::get('/gallery', [AlbumController::class, 'index'])->name('gallery.index');
        Route::post('/gallery/albums', [AlbumController::class, 'store'])->name('gallery.albums.store');
        Route::delete('/gallery/albums/{album}', [AlbumController::class, 'destroy'])->name('gallery.albums.destroy');
        Route::post('/gallery/albums/{album}/media', [AlbumController::class, 'storeMedia'])->name('gallery.media.store');
        Route::delete('/gallery/media/{medium}', [AlbumController::class, 'destroyMedia'])->name('gallery.media.destroy');
    });

    Route::middleware('role:super_admin,moderator')->group(function () {
        Route::get('/tributes', [TributeController::class, 'index'])->name('tributes.index');
        Route::post('/tributes/export', [TributeController::class, 'export'])->name('tributes.export');
        Route::put('/tributes/{tribute}', [TributeController::class, 'update'])->name('tributes.update');
        Route::post('/tributes/{tribute}/approve', [TributeController::class, 'approve'])->name('tributes.approve');
        Route::post('/tributes/{tribute}/reject', [TributeController::class, 'reject'])->name('tributes.reject');
        Route::post('/tributes/{tribute}/feature', [TributeController::class, 'feature'])->name('tributes.feature');
        Route::delete('/tributes/{tribute}', [TributeController::class, 'destroy'])->name('tributes.destroy');

        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact.index');
        Route::post('/contact-messages/{contactMessage}/read', [ContactMessageController::class, 'markRead'])->name('contact.read');
        Route::post('/contact-messages/{contactMessage}/resolve', [ContactMessageController::class, 'markResolved'])->name('contact.resolve');
    });

    Route::middleware('role:super_admin,event_manager')->group(function () {
        Route::resource('events', EventController::class)
            ->except(['show']);
        Route::post('/events/{event}/toggle-publish', [EventController::class, 'togglePublish'])->name('events.toggle-publish');
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
