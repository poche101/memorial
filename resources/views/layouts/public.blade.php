<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'In Loving Memory') &mdash; {{ $memorial->title ?? '' }} {{ $memorial->name ?? '' }}</title>
<meta name="description" content="A digital memorial celebrating the life and legacy of {{ $memorial->name ?? '' }}.">

{{-- Open Graph metadata (PRD FR-10) --}}
<meta property="og:title" content="In Loving Memory of {{ $memorial->name ?? '' }}">
<meta property="og:description" content="{{ $memorial->statement ?? '' }}">
@if($memorial->portrait_path ?? false)
<meta property="og:image" content="{{ asset('storage/'.$memorial->portrait_path) }}">
@endif

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/tribute.css') }}">

<style>
/* Elegant portrait frame — taller, gilded, with corner flourishes */
.hero-portrait {
  width: 280px;
  height: 360px;
  position: relative;
}

.frame-glow {
  position: absolute;
  inset: -18px;
  background: radial-gradient(circle, rgba(169, 139, 79, 0.35) 0%, transparent 70%);
  filter: blur(18px);
  z-index: 0;
}

.frame-metal {
  position: relative;
  width: 100%;
  height: 100%;
  padding: 10px;
  border-radius: 6px;
  background: linear-gradient(135deg, #f4e6bd 0%, #a9863f 22%, #6b4f22 50%, #a9863f 78%, #f4e6bd 100%);
  box-shadow:
    0 25px 50px -18px rgba(20, 15, 8, 0.55),
    0 0 0 1px rgba(255, 255, 255, 0.15) inset;
  z-index: 1;
}

.frame-bevel {
  height: 100%;
  padding: 6px;
  border-radius: 4px;
  background: linear-gradient(135deg, #2b2213 0%, #4a3a1e 50%, #2b2213 100%);
  box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.4) inset;
}

.frame-mat {
  height: 100%;
  padding: 14px;
  border-radius: 2px;
  background: #fdfbf5;
  box-shadow:
    0 0 0 1px rgba(169, 139, 79, 0.5),
    0 2px 8px rgba(0, 0, 0, 0.15) inset;
  position: relative;
}

/* Delicate corner flourishes on the mat */
.frame-mat::before,
.frame-mat::after,
.frame-mat > .frame-photo::before,
.frame-mat > .frame-photo::after {
  content: "";
  position: absolute;
  width: 22px;
  height: 22px;
  border: 1px solid #a9863f;
  opacity: 0.85;
}
.frame-mat::before { top: 6px; left: 6px; border-right: none; border-bottom: none; }
.frame-mat::after { top: 6px; right: 6px; border-left: none; border-bottom: none; }
.frame-mat > .frame-photo::before { bottom: -8px; left: -8px; border-right: none; border-top: none; }
.frame-mat > .frame-photo::after { bottom: -8px; right: -8px; border-left: none; border-top: none; }

.frame-photo {
  height: 100%;
  overflow: hidden;
  border-radius: 1px;
  position: relative;
}

.frame-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  filter: sepia(8%) saturate(92%);
}

.flourish {
  color: #a9863f;
}

@media (max-width: 640px) {
  .hero-portrait {
    width: 220px;
    height: 300px;
  }
}
</style>
</head>
<body>

<header class="hero" id="top">
  <div class="hero-inner">
   <div class="hero-portrait">
      <div class="frame-glow"></div>
      <div class="flourish">
        <svg viewBox="0 0 240 34" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round">
          <path d="M18 17 H88"/>
          <path d="M152 17 H222"/>
          <path d="M120 6 V28"/>
          <path d="M111 11 L120 6 L129 11"/>
          <path d="M111 23 L120 28 L129 23"/>
          <path d="M40 17 q7 -9 16 -6"/>
          <path d="M56 17 q7 9 16 6"/>
          <path d="M72 17 q7 -9 16 -6"/>
          <path d="M200 17 q-7 -9 -16 -6"/>
          <path d="M184 17 q-7 9 -16 6"/>
          <path d="M168 17 q-7 -9 -16 -6"/>
        </svg>
      </div>
      <div class="frame-metal">
        <div class="frame-bevel">
          <div class="frame-mat">
            <div class="frame-photo">
              @if($memorial->portrait_path ?? false)
                <img src="{{ asset('storage/'.$memorial->portrait_path) }}" alt="Portrait of {{ $memorial->name }}">
              @else
                <img src="{{ asset('images/portrait-placeholder.jpg') }}" alt="Portrait">
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="eyebrow">Celebration of Life</div>
      <h1 class="hero-name">{{ $memorial->title ?? '' }}<br>{{ $memorial->name ?? 'Loading' }}</h1>
      <div class="hero-title">Beloved Father, Pastor &amp; Friend</div>
      <div class="hero-dates">
        <span>{{ $memorial->birth_date?->format('Y') }}</span><span class="dash"></span><span>{{ $memorial->death_date?->format('Y') }}</span>
      </div>
      @if($memorial->statement ?? false)
      <p class="hero-verse">&ldquo;{{ $memorial->statement }}&rdquo;</p>
      @endif
      <nav class="hero-nav">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('biography') }}" class="{{ request()->routeIs('biography') ? 'active' : '' }}">Biography</a>
        <a href="{{ route('timeline') }}" class="{{ request()->routeIs('timeline') ? 'active' : '' }}">Life Timeline</a>
        <a href="{{ route('events') }}" class="{{ request()->routeIs('events') ? 'active' : '' }}">Arrangements</a>
        <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
        <a href="{{ route('tributes.index') }}" class="{{ request()->routeIs('tributes.*') ? 'active' : '' }}">Tributes</a>
        @if($memorial->brochure_path ?? false)
        <a href="{{ route('brochure.download') }}">Download Brochure</a>
        @endif
      </nav>
    </div>
  </div>
</header>

@if(session('status'))
  <div class="container" style="padding-top:32px;"><div class="status-banner">{{ session('status') }}</div></div>
@endif
@if($errors->any())
  <div class="container" style="padding-top:32px;">
    <div class="error-banner">{{ $errors->first() }}</div>
  </div>
@endif

@yield('content')

<footer>
  <div class="fname">{{ $memorial->title ?? '' }} {{ $memorial->name ?? '' }}</div>
  <div>{{ $memorial->birth_date?->format('Y') }} &ndash; {{ $memorial->death_date?->format('Y') }} &middot; Forever in our hearts</div>
</footer>

</body>
</html>
