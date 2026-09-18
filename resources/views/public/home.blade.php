@extends('layouts.public')
@section('title', 'Home')
@section('content')

<section class="bio" id="biography">
  <div class="container">
    <div class="bio-grid">
      <div>
        <div class="section-label">His Story</div>
        <h2 class="section-title">A Life of Faith and Service</h2>
        <div class="rule"></div>
        <p>{{ \Illuminate\Support\Str::of($memorial->biography)->limit(420) }}</p>
        <p><a href="{{ route('biography') }}">Read his full story &rarr;</a></p>
      </div>
      <div class="bio-fact-card">
        <h3>At a Glance</h3>
        <dl>
          <div class="row"><dt>Born</dt><dd>{{ $memorial->birth_date?->format('Y') }}</dd></div>
          <div class="row"><dt>Passed</dt><dd>{{ $memorial->death_date?->format('Y') }}</dd></div>
          @if($memorial->ageAtDeath())
          <div class="row"><dt>Age</dt><dd>{{ $memorial->ageAtDeath() }} years</dd></div>
          @endif
          @foreach($events as $event)
          <div class="row"><dt>{{ $event->title }}</dt><dd>{{ $event->event_date->format('D j M Y') }}</dd></div>
          @endforeach
        </dl>
      </div>
    </div>
  </div>
</section>

@if($featuredTributes->count())
<section class="tributes">
  <div class="container">
    <div class="section-label">Remembered With Love</div>
    <h2 class="section-title">Featured Tributes</h2>
    <div class="rule"></div>
    <div class="wall">
      @foreach($featuredTributes as $tribute)
      <div class="wall-item">
        @if($tribute->imageUrl())
          <img src="{{ $tribute->imageUrl() }}" alt="Photo shared by {{ $tribute->name }}" style="width:100%; max-width:220px; margin-bottom:12px; box-shadow:0 10px 24px -12px rgba(0,0,0,0.35);">
        @endif
        <div class="msg">&ldquo;{{ $tribute->message }}&rdquo;</div>
        <div class="who">&mdash; {{ $tribute->name }}@if($tribute->relationship) ({{ $tribute->relationship }})@endif</div>
      </div>
      @endforeach
    </div>
    <p style="margin-top:26px;"><a href="{{ route('tributes.index') }}">View the full remembrance wall &rarr;</a></p>
  </div>
</section>
@endif

@endsection
