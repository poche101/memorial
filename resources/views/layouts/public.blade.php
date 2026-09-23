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
<link rel="stylesheet" href="{{ asset('css/tribute.css') }}?v={{ filemtime(public_path('css/tribute.css')) }}">

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
  <div class="hero-slides">
    <div class="hero-slide active" style="background-image:url('{{ asset('images/img1.jpeg') }}');"></div>
    <div class="hero-slide" style="background-image:url('{{ asset('images/img2.jpeg') }}');"></div>
    <div class="hero-slide" style="background-image:url('{{ asset('images/img3.jpeg') }}');"></div>
    <div class="hero-slide" style="background-image:url('{{ asset('images/img4.jpeg') }}');"></div>
    <div class="hero-slide" style="background-image:url('{{ asset('images/img5.jpeg') }}');"></div>
    <div class="hero-slide" style="background-image:url('{{ asset('images/img6.jpeg') }}');"></div>
  </div>

  <div class="hero-tint" aria-hidden="true"></div>
  <div class="hero-vignette" aria-hidden="true"></div>

  <div class="hero-slide-content">
    <p class="hero-eyebrow">CELEBRATION <span class="hero-eyebrow-script">of</span> LIFE</p>
    <p class="hero-role">{{ $memorial->title }}</p>
    <h1 class="hero-fullname">{{ $memorial->name }}</h1>
    <div class="hero-dates-row">
      <span class="hero-dash" aria-hidden="true"></span>
      <span>{{ $memorial->birth_date?->format('Y') }} &ndash; {{ $memorial->death_date?->format('Y') }}</span>
      <span class="hero-dash" aria-hidden="true"></span>
    </div>
    <p class="hero-age-badge">AGED 61 YEARS</p>
  </div>

  <div class="hero-dots" role="tablist" aria-label="Hero image slides">
    <button type="button" class="hero-dot active" data-slide="0" aria-label="Show slide 1"></button>
    <button type="button" class="hero-dot" data-slide="1" aria-label="Show slide 2"></button>
    <button type="button" class="hero-dot" data-slide="2" aria-label="Show slide 3"></button>
    <button type="button" class="hero-dot" data-slide="3" aria-label="Show slide 4"></button>
    <button type="button" class="hero-dot" data-slide="4" aria-label="Show slide 5"></button>
    <button type="button" class="hero-dot" data-slide="5" aria-label="Show slide 6"></button>
  </div>

  <div class="hero-inner">
    <nav class="hero-nav">
      <div class="hero-nav-bar">
        <button
          type="button"
          class="hero-nav-toggle"
          id="heroNavToggle"
          aria-expanded="false"
          aria-controls="heroNavLinks"
          aria-label="Toggle navigation menu"
        >
          <svg class="icon-menu" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
          <svg class="icon-close" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="6" y1="6" x2="18" y2="18"/>
            <line x1="6" y1="18" x2="18" y2="6"/>
          </svg>
        </button>
      </div>
      <div class="hero-nav-links" id="heroNavLinks">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('biography') }}" class="{{ request()->routeIs('biography') ? 'active' : '' }}">Biography</a>
        <a href="{{ route('timeline') }}" class="{{ request()->routeIs('timeline') ? 'active' : '' }}">Life Timeline</a>
        <a href="{{ route('events') }}" class="{{ request()->routeIs('events') ? 'active' : '' }}">Arrangements</a>
        <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
        <a href="{{ route('tributes.index') }}" class="{{ request()->routeIs('tributes.*') ? 'active' : '' }}">Tributes</a>
        @if($memorial->brochure_path ?? false)
        <a href="{{ route('brochure.download') }}">Download Brochure</a>
        @endif
      </div>
    </nav>
  </div>

  @if($memorial->song_path ?? false)
    <audio id="memorial-song" loop preload="auto">
      <source src="{{ asset('storage/'.$memorial->song_path) }}" type="audio/mpeg">
    </audio>
    <button id="music-toggle" class="music-toggle" aria-label="Pause tribute song" aria-pressed="false" type="button">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
    </button>
  @endif
</header>

<style>
  .music-toggle {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 50;
    backdrop-filter: blur(4px);
  }
  .music-toggle.playing svg { display: none; }
  .music-toggle.playing::after { content: "❙❙"; font-size: 14px; letter-spacing: 2px; }
</style>

@if($memorial->song_path ?? false)
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const audio = document.getElementById('memorial-song');
    const btn = document.getElementById('music-toggle');
    if (!audio || !btn) return;

    function setPlayingState(isPlaying) {
      btn.classList.toggle('playing', isPlaying);
      btn.setAttribute('aria-pressed', isPlaying ? 'true' : 'false');
      btn.setAttribute('aria-label', isPlaying ? 'Pause tribute song' : 'Play tribute song');
    }

    function attemptPlay() {
      const playPromise = audio.play();
      if (playPromise !== undefined) {
        playPromise.then(function () {
          setPlayingState(true);
        }).catch(function () {
          // Autoplay with sound was blocked by the browser. Start playback
          // on the visitor's very first tap/click/keypress anywhere on the
          // page instead — that counts as a user gesture, so it's allowed.
          setPlayingState(false);
          const startOnFirstInteraction = function () {
            audio.play().then(function () { setPlayingState(true); }).catch(function () {});
          };
          document.addEventListener('click', startOnFirstInteraction, { once: true });
          document.addEventListener('touchstart', startOnFirstInteraction, { once: true });
          document.addEventListener('keydown', startOnFirstInteraction, { once: true });
        });
      }
    }

    // Try to start the song as soon as the page loads.
    attemptPlay();

    // Manual toggle still works regardless of how playback started.
    btn.addEventListener('click', function () {
      if (audio.paused) {
        audio.play().then(function () { setPlayingState(true); }).catch(function () {});
      } else {
        audio.pause();
        setPlayingState(false);
      }
    });
  });
</script>
@endif

<script>
  // Hero image slideshow: auto-rotate with cross-fade, dots for manual control
  document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    if (slides.length <= 1) return;

    let current = 0;
    let timer = null;
    const intervalMs = 6000;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function showSlide(i) {
      slides.forEach(function (s, idx) { s.classList.toggle('active', idx === i); });
      dots.forEach(function (d, idx) { d.classList.toggle('active', idx === i); });
      current = i;
    }

    function nextSlide() {
      showSlide((current + 1) % slides.length);
    }

    function startAutoplay() {
      if (prefersReducedMotion) return; // respect reduced-motion: no auto-advance
      stopAutoplay();
      timer = setInterval(nextSlide, intervalMs);
    }

    function stopAutoplay() {
      if (timer) clearInterval(timer);
    }

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        showSlide(parseInt(dot.dataset.slide, 10));
        startAutoplay(); // reset the timer after a manual click
      });
    });

    startAutoplay();
  });
</script>

<script>
  // Mobile hero-nav toggle
  document.addEventListener('DOMContentLoaded', function () {
    const navToggle = document.getElementById('heroNavToggle');
    const navLinks = document.getElementById('heroNavLinks');
    if (navToggle && navLinks) {
      navToggle.addEventListener('click', function () {
        const isOpen = navLinks.classList.toggle('open');
        navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });

      // Close the menu after a link is tapped
      navLinks.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          navLinks.classList.remove('open');
          navToggle.setAttribute('aria-expanded', 'false');
        });
      });

      // Collapse back to desktop layout on resize past the breakpoint
      window.addEventListener('resize', function () {
        if (window.innerWidth > 720) {
          navLinks.classList.remove('open');
          navToggle.setAttribute('aria-expanded', 'false');
        }
      });
    }
  });
</script>

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
