@extends('layouts.admin')
@section('title', 'Events Manager')
@section('content')
<a href="{{ route('admin.events.create') }}" class="btn" style="margin-bottom:20px; display:inline-block;">+ New Event</a>
<div class="admin-card">
  <table class="admin-table">
    <thead><tr><th>Title</th><th>Date</th><th>Venue</th><th>Published</th><th></th></tr></thead>
    <tbody>
      @forelse($events as $event)
      <tr>
        <td>{{ $event->title }}</td>
        <td>{{ $event->event_date->format('D j M Y') }} {{ $event->event_time }}</td>
        <td>{{ $event->venue }}</td>
        <td>{{ $event->is_published ? 'Yes' : 'No' }}</td>
        <td style="white-space:nowrap;">
          <a href="{{ route('admin.events.edit', $event) }}" class="btn secondary">Edit</a>
          <form method="POST" action="{{ route('admin.events.toggle-publish', $event) }}" style="display:inline;">
            @csrf<button type="submit" class="btn secondary">{{ $event->is_published ? 'Unpublish' : 'Publish' }}</button>
          </form>
          <form method="POST" action="{{ route('admin.events.destroy', $event) }}" style="display:inline;" onsubmit="return confirm('Delete this event?')">
            @csrf @method('DELETE')<button type="submit" class="btn danger">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="5">No events yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
