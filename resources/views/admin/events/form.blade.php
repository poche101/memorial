@extends('layouts.admin')
@section('title', $event->exists ? 'Edit Event' : 'New Event')
@section('content')
<div class="admin-card">
  <form method="POST" action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}" class="admin-form">
    @csrf
    @if($event->exists) @method('PUT') @endif

    <label>Title</label>
    <input type="text" name="title" required value="{{ old('title', $event->title) }}" placeholder="e.g. Service of Songs">

    <label>Date</label>
    <input type="date" name="event_date" required value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}">

    <label>Time (display text)</label>
    <input type="text" name="event_time" value="{{ old('event_time', $event->event_time) }}" placeholder="e.g. 4:00 PM">

    <label>Venue name</label>
    <input type="text" name="venue" value="{{ old('venue', $event->venue) }}">

    <label>Full address</label>
    <textarea name="address" rows="3">{{ old('address', $event->address) }}</textarea>

    <label>Online link (optional)</label>
    <input type="url" name="online_link" value="{{ old('online_link', $event->online_link) }}">

    <label>Description / notes (e.g. interment details)</label>
    <textarea name="description" rows="3">{{ old('description', $event->description) }}</textarea>

    <label style="display:flex; gap:8px; align-items:center;">
      <input type="checkbox" name="is_published" value="1" style="width:auto;" @checked(old('is_published', $event->is_published ?? true))> Published
    </label>

    <button type="submit" class="submit-btn" style="margin-top:18px;">Save Event</button>
  </form>
</div>
@endsection
