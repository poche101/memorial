@extends('layouts.public')
@section('title', 'Biography')
@section('content')
<section class="bio">
  <div class="container">
    <div class="section-label">His Story</div>
    <h2 class="section-title">Biography &amp; Life Story</h2>
    <div class="rule"></div>
    <p>{{ $memorial->biography }}</p>
  </div>
</section>
@endsection
