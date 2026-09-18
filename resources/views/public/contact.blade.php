@extends('layouts.public')
@section('title', 'Contact')
@section('content')
<section class="contact" id="contact">
  <div class="container">
    <div class="contact-grid">
      <div>
        <div class="section-label">Get in Touch</div>
        <h2 class="section-title">Contact the Family Office</h2>
        <div class="rule"></div>
        <form method="POST" action="{{ route('contact.store') }}">
          @csrf
          <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
          <div class="field"><label for="cName">Name</label><input type="text" id="cName" name="name" required value="{{ old('name') }}"></div>
          <div class="field"><label for="cEmail">Email</label><input type="email" id="cEmail" name="email" required value="{{ old('email') }}"></div>
          <div class="field"><label for="cSubject">Subject</label><input type="text" id="cSubject" name="subject" value="{{ old('subject') }}"></div>
          <div class="field"><label for="cMsg">Message</label><textarea id="cMsg" name="message" required>{{ old('message') }}</textarea></div>
          <button type="submit" class="submit-btn">Send Message</button>
        </form>
      </div>
      <div class="contact-info">
        @foreach($memorial->events as $event)
        <div class="row">
          <div class="label">{{ $event->title }}</div>
          <div class="value">{{ $event->event_date->format('D j M Y') }}@if($event->event_time), {{ $event->event_time }}@endif @if($event->venue) &mdash; {{ $event->venue }}@endif</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection
