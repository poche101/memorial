<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Event;
use App\Models\Memorial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $memorial = Memorial::firstOrFail();

        return view('admin.events.index', [
            'events' => $memorial->events,
        ]);
    }

    public function create(): View
    {
        return view('admin.events.form', ['event' => new Event()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $memorial = Memorial::firstOrFail();
        $event = $memorial->events()->create($this->validated($request));

        AuditLog::record('event.created', $event);

        return redirect()->route('admin.events.index')->with('status', 'Event created.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.form', ['event' => $event]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $event->update($this->validated($request));
        AuditLog::record('event.updated', $event);

        return redirect()->route('admin.events.index')->with('status', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        AuditLog::record('event.deleted', $event, $event->title);
        $event->delete();

        return back()->with('status', 'Event removed.');
    }

    public function togglePublish(Event $event): RedirectResponse
    {
        $event->update(['is_published' => ! $event->is_published]);
        AuditLog::record('event.publish_toggled', $event);

        return back()->with('status', $event->is_published ? 'Event published.' : 'Event unpublished.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'string', 'max:50'],
            'venue' => ['nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'online_link' => ['nullable', 'url'],
            'rsvp_enabled' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }
}
