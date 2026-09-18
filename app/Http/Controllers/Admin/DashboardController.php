<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Event;
use App\Models\Memorial;
use App\Models\Tribute;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $memorial = Memorial::first();

        return view('admin.dashboard', [
            'memorial' => $memorial,
            'totalTributes' => Tribute::count(),
            'pendingTributes' => Tribute::pending()->count(),
            'publishedTributes' => Tribute::approved()->count(),
            'galleryItemCount' => Album::withCount('media')->get()->sum('media_count'),
            'upcomingEvents' => Event::published()->where('event_date', '>=', now())->orderBy('event_date')->take(5)->get(),
            'recentTributes' => Tribute::latest()->take(5)->get(),
        ]);
    }
}
