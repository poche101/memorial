<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use App\Support\StreamEmbed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StreamController extends Controller
{
    public function edit(): View
    {
        $memorial = Memorial::firstOrFail();

        return view('admin.stream.edit', [
            'memorial'  => $memorial,
            'providers' => StreamEmbed::PROVIDERS,
            'preview'   => StreamEmbed::resolve($memorial, true),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'stream_provider'    => ['required', Rule::in(array_keys(StreamEmbed::PROVIDERS))],
            'stream_url'         => ['nullable', 'url', 'max:2000'],
            'stream_title'       => ['nullable', 'string', 'max:150'],
            'stream_description' => ['nullable', 'string', 'max:1000'],
            'stream_starts_at'   => ['nullable', 'date'],
        ]);

        $url  = trim((string) ($data['stream_url'] ?? ''));
        $live = $request->boolean('stream_is_live');

        if ($url !== '' && StreamEmbed::build($data['stream_provider'], $url, false) === null) {
            return back()->withInput()->withErrors([
                'stream_url' => 'That link is not a valid ' . StreamEmbed::label($data['stream_provider']) . ' link.',
            ]);
        }

        if (($live || $request->boolean('stream_enabled')) && $url === '') {
            return back()->withInput()->withErrors([
                'stream_url' => 'Add a stream link before showing the player or going live.',
            ]);
        }

        Memorial::firstOrFail()->forceFill([
            'stream_provider'    => $data['stream_provider'],
            'stream_url'         => $url ?: null,
            'stream_title'       => $data['stream_title'] ?? null,
            'stream_description' => $data['stream_description'] ?? null,
            'stream_starts_at'   => $data['stream_starts_at'] ?? null,
            'stream_autoplay'    => $request->boolean('stream_autoplay'),
            'stream_is_live'     => $live,
            // Going live always implies the player is shown.
            'stream_enabled'     => $request->boolean('stream_enabled') || $live,
        ])->save();

        return redirect()->route('admin.stream.edit')->with('status', 'Stream settings saved.');
    }

    public function goLive(): RedirectResponse
    {
        $memorial = Memorial::firstOrFail();

        if (blank($memorial->stream_url)) {
            return back()->withErrors(['stream_url' => 'Add and save a stream link first.']);
        }

        $memorial->forceFill(['stream_enabled' => true, 'stream_is_live' => true])->save();

        return redirect()->route('admin.stream.edit')->with('status', 'You are live. The player is now showing on the site.');
    }

    public function end(): RedirectResponse
    {
        Memorial::firstOrFail()->forceFill([
            'stream_enabled' => false,
            'stream_is_live' => false,
        ])->save();

        return redirect()->route('admin.stream.edit')->with('status', 'Stream ended. The player is hidden from the site.');
    }
}
