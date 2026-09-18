@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="admin-stats">
  <div class="stat"><div class="num">{{ $totalTributes }}</div><div class="lbl">Total tribute submissions</div></div>
  <div class="stat"><div class="num">{{ $pendingTributes }}</div><div class="lbl">Pending moderation</div></div>
  <div class="stat"><div class="num">{{ $publishedTributes }}</div><div class="lbl">Published tributes</div></div>
  <div class="stat"><div class="num">{{ $galleryItemCount }}</div><div class="lbl">Gallery items</div></div>
</div>

<div class="admin-card">
  <h3 style="margin-bottom:16px;">Upcoming Memorial Events</h3>
  <table class="admin-table">
    <thead><tr><th>Title</th><th>Date</th><th>Venue</th></tr></thead>
    <tbody>
      @forelse($upcomingEvents as $event)
      <tr><td>{{ $event->title }}</td><td>{{ $event->event_date->format('D j M Y') }}</td><td>{{ $event->venue }}</td></tr>
      @empty
      <tr><td colspan="3">No upcoming events.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-card">
  <h3 style="margin-bottom:16px;">Recent Visitor Submissions</h3>
  <table class="admin-table">
    <thead><tr><th>Name</th><th>Message</th><th>Status</th><th>Submitted</th></tr></thead>
    <tbody>
      @forelse($recentTributes as $tribute)
      <tr>
        <td>{{ $tribute->name }}</td>
        <td>{{ \Illuminate\Support\Str::limit($tribute->message, 60) }}</td>
        <td><span class="badge {{ $tribute->status }}">{{ ucfirst($tribute->status) }}</span></td>
        <td>{{ $tribute->created_at->diffForHumans() }}</td>
      </tr>
      @empty
      <tr><td colspan="4">No submissions yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
