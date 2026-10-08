@php
  // Livestream data comes from the admin "Live Stream" page.
  $stream     = \App\Support\StreamEmbed::resolve($memorial ?? null);
  $streamLive = $stream && $stream['live'] && $stream['mode'];
@endphp
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
/* Elegant portrait frame (kept in case other pages use it) */
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

.flourish { color: #a9863f; }

@media (max-width: 640px) {
  .hero-portrait {
    width: 220px;
    height: 300px;
  }
}

/* ---------- Livestream player (full width) ---------- */
.stream-hero {
  width: 100%;
  margin: 0;
  padding: 0;
  background: #1a140a;
}

.stream-panel {
  width: 100%;
  max-width: none;
  margin: 0;
  padding: 0;
  color: #fdfbf5;
  border-bottom: 6px solid #a9863f;
}

.stream-head {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 20px;
  flex-wrap: wrap;
}

.stream-head h2 {
  margin: 0;
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-weight: 600;
  font-size: clamp(1.1rem, 2.4vw, 1.6rem);
  color: #f4e6bd;
}

.stream-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 3px 10px;
  border-radius: 999px;
  font: 600 11px/1.4 'EB Garamond', Georgia, serif;
  letter-spacing: .14em;
  text-transform: uppercase;
  background: #a9863f;
  color: #fff;
}

.stream-badge.is-live { background: #c0392b; }
.stream-badge.is-live::before {
  content: "";
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #fff;
  animation: streamPulse 1.4s infinite;
}

@keyframes streamPulse { 0%, 100% { opacity: 1; } 50% { opacity: .25; } }

/* Full width, height capped so it is not huge on wide monitors.
   Change the numbers in clamp(min, preferred, max) to taste. */
.stream-screen {
  position: relative;
  width: 100%;
  height: clamp(300px, 60vh, 560px);
  background: #000;
  overflow: hidden;
}

/* Phones: go back to a true 16:9 shape */
@media (max-width: 720px) {
  .stream-screen {
    height: auto;
    aspect-ratio: 16 / 9;
  }
  .stream-head { padding: 10px 14px; }
}

.stream-screen iframe,
.stream-screen video {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  border: 0;
  display: block;
}

.stream-wait {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 16px;
  text-align: center;
  background: radial-gradient(circle at center, #2b2213 0%, #0f0b05 100%);
}

.stream-wait .wait-title {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: clamp(1.1rem, 3vw, 1.8rem);
  color: #f4e6bd;
}

.stream-wait .wait-when,
.stream-wait .wait-count {
  font-family: 'EB Garamond', Georgia, serif;
  font-size: clamp(.9rem, 2vw, 1.1rem);
  color: #d9ccaa;
}

.stream-wait .wait-count { font-variant-numeric: tabular-nums; letter-spacing: .06em; }

.stream-desc {
  margin: 0;
  padding: 12px 20px 16px;
  font-family: 'EB Garamond', Georgia, serif;
  font-size: 1rem;
  line-height: 1.5;
  color: #d9ccaa;
}

/* ---------- Tribute song button ---------- */
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
</head>
<body>

{{-- LIVESTREAM PLAYER (managed from Admin > Live Stream), full width --}}
@if($stream)
<header class="stream-hero" id="top">
  <section class="stream-panel" id="stream" aria-label="Livestream">
    <div class="stream-head">
      <span class="stream-badge {{ $streamLive ? 'is-live' : '' }}">{{ $streamLive ? 'Live' : 'Upcoming' }}</span>
      <h2>{{ $stream['title'] }}</h2>
    </div>

    <div class="stream-screen">
      @if($streamLive)
        @include('partials.stream-player', ['stream' => $stream])
      @else
        <div class="stream-wait">
          <div class="wait-title">The stream will begin shortly</div>
          @if($stream['starts_at'])
            <div class="wait-when">{{ $stream['starts_at']->format('l, j F Y \a\t g:i A') }}</div>
            <div class="wait-count" id="streamCountdown" data-start="{{ $stream['starts_at']->toIso8601String() }}"></div>
          @else
            <div class="wait-when">Please stay on this page. The player will appear automatically.</div>
          @endif
        </div>
      @endif
    </div>

    @if($stream['description'])
      <p class="stream-desc">{{ $stream['description'] }}</p>
    @endif
  </section>
</header>
@endif

@if(($memorial->song_path ?? false) && ! $streamLive)
  {{-- The tribute song is skipped while the stream is live so the two audio sources do not clash. --}}
  <audio id="memorial-song" loop preload="auto">
    <source src="{{ asset('storage/'.$memorial->song_path) }}" type="audio/mpeg">
  </audio>
  <button id="music-toggle" class="music-toggle" aria-label="Pause tribute song" aria-pressed="false" type="button">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
  </button>

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
            // Autoplay with sound was blocked. Start on the first user gesture instead.
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

      attemptPlay();

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

@if($stream && ! $streamLive)
<script>
  // Waiting card: countdown + auto-switch to the player when the admin goes live
  document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('streamCountdown');
    if (el) {
      var target = new Date(el.getAttribute('data-start')).getTime();
      var tick = function () {
        var diff = target - Date.now();
        if (diff <= 0) { el.textContent = 'Starting any moment now…'; return; }
        var s = Math.floor(diff / 1000);
        var d = Math.floor(s / 86400); s -= d * 86400;
        var h = Math.floor(s / 3600);  s -= h * 3600;
        var m = Math.floor(s / 60);    s -= m * 60;
        var pad = function (n) { return String(n).padStart(2, '0'); };
        el.textContent = 'Starts in ' + (d > 0 ? d + 'd ' : '') + pad(h) + 'h ' + pad(m) + 'm ' + pad(s) + 's';
      };
      tick();
      setInterval(tick, 1000);
    }

    var checkLive = function () {
      fetch(@json(route('stream.status')), { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
        .then(function (r) { return r.json(); })
        .then(function (data) { if (data.live) { window.location.reload(); } })
        .catch(function () {});
    };
    setInterval(checkLive, 30000);
  });
</script>
@endif

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
