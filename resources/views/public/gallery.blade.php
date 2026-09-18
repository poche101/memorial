@extends('layouts.public')
@section('title', 'Gallery')
@section('content')
<section class="gallery">
  <div class="container">
    <div class="section-label">In Pictures</div>
    <h2 class="section-title">Photo &amp; Video Gallery</h2>
    <div class="rule"></div>

    @forelse($albums as $album)
      <h3 style="margin:34px 0 14px; font-size:22px;">{{ $album->title }}</h3>
      <div class="gallery-grid">
        @forelse($album->media as $item)
          <div class="gallery-tile">
            @if($item->type === 'photo')
              <img src="{{ $item->url() }}" alt="{{ $item->caption }}" loading="lazy">
            @else
              <a href="{{ $item->url() }}" target="_blank" rel="noopener">{{ $item->caption ?? 'Watch video' }}</a>
            @endif
          </div>
        @empty
          <div class="gallery-tile">No media yet in this album</div>
        @endforelse
      </div>
    @empty
      <p class="note">Photographs and videos will appear here once uploaded through the admin dashboard.</p>
    @endforelse
  </div>
</section>
@endsection
