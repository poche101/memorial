@extends('layouts.public')
@section('title', 'Life Timeline')
@section('content')
<section class="timeline-section">
  <div class="container">
    <div class="section-label">The Journey</div>
    <h2 class="section-title">Life Timeline</h2>
    <div class="rule"></div>
    <div class="timeline">
      @foreach($entries as $entry)
      <div class="t-item">
        <div class="t-year">{{ $entry->year_label }}</div>
        <div class="t-title">{{ $entry->title }}</div>
        @if($entry->description)<div class="t-desc">{{ $entry->description }}</div>@endif
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
