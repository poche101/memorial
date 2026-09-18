@extends('layouts.public')
@section('title', 'Funeral Arrangements')
@section('content')
<section class="arrangements">
  <div class="container">
    <div class="section-label">Funeral Arrangements</div>
    <h2 class="section-title">Join Us in Celebrating His Life</h2>
    <div class="events">
      @foreach($events as $event)
      <div class="event">
        <h3>{{ $event->title }}</h3>
        <div class="when">{{ $event->event_date->format('l, jS F Y') }}@if($event->event_time) &middot; {{ $event->event_time }}@endif</div>
        <div class="where">{{ $event->address ?? $event->venue }}</div>
        @if($event->online_link)
          <p style="margin-top:12px;"><a href="{{ $event->online_link }}" target="_blank" rel="noopener" style="color:var(--gold-light);">Join online &rarr;</a></p>
        @endif
      </div>
      @endforeach
    </div>
    @if($events->firstWhere('description', '!=', null))
      <div class="interment">
        <h3>Interment</h3>
        <span>{{ $events->firstWhere('description', '!=', null)->description }}</span>
      </div>
    @endif
  </div>
</section>
@endsection
