<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\AuditLog;
use App\Models\Media;
use App\Models\Memorial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AlbumController extends Controller
{
    public function index(): View
    {
        $memorial = Memorial::firstOrFail();

        return view('admin.gallery.index', [
            'albums' => $memorial->albums()->withCount('media')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $memorial = Memorial::firstOrFail();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $album = $memorial->albums()->create([
            ...$data,
            'slug' => Str::slug($data['title']).'-'.Str::random(4),
        ]);

        AuditLog::record('album.created', $album);

        return back()->with('status', 'Album created.');
    }

    public function destroy(Album $album): RedirectResponse
    {
        AuditLog::record('album.deleted', $album, $album->title);
        $album->delete();

        return back()->with('status', 'Album and its media removed.');
    }

    public function storeMedia(Request $request, Album $album): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:photo,video'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov', 'max:20480'],
            'video_url' => ['nullable', 'url'],
            'caption' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $path = $data['video_url'] ?? null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('gallery', 'public');
        }

        if (! $path) {
            return back()->withErrors(['file' => 'Upload a file or provide a video URL.']);
        }

        $media = $album->media()->create([
            'type' => $data['type'],
            'path' => $path,
            'caption' => $data['caption'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => $album->media()->max('sort_order') + 1,
        ]);

        AuditLog::record('media.created', $media);

        return back()->with('status', 'Media added to album.');
    }

    public function destroyMedia(Media $medium): RedirectResponse
    {
        AuditLog::record('media.deleted', $medium, $medium->caption);
        $medium->delete();

        return back()->with('status', 'Media removed.');
    }
}
