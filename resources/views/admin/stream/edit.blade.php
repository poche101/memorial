@extends('layouts.admin')

@section('title', 'Live Stream')

@section('content')
@php
  $isLive    = $memorial->stream_enabled && $memorial->stream_is_live;
  $isShowing = (bool) $memorial->stream_enabled;
  $startsVal = old('stream_starts_at', $memorial->stream_starts_at ? \Illuminate\Support\Carbon::parse($memorial->stream_starts_at)->format('Y-m-d\TH:i') : '');
@endphp

<style>
.stream-admin { display:grid; gap:24px; grid-template-columns:minmax(0,1.2fr) minmax(0,1fr); align-items:start; padding:0 24px 40px; }
@media (max-width: 992px) { .stream-admin { grid-template-columns:1fr; padding:0 16px 40px; } }
.s-card { background:#fff; border:1px solid rgba(0,0,0,.08); border-radius:8px; padding:22px; margin-bottom:24px; }
.s-card h2 { margin:0 0 14px; font-size:1.25rem; }
.s-field { margin-bottom:16px; }
.s-field label { display:block; font-weight:600; margin-bottom:6px; font-size:14px; }
.s-field input[type=text], .s-field input[type=url], .s-field input[type=datetime-local],
.s-field select, .s-field textarea { width:100%; padding:10px 12px; border:1px solid #cfcfcf; border-radius:6px; font:inherit; box-sizing:border-box; background:#fff; }
.s-hint { font-size:13px; color:#777; margin-top:4px; line-height:1.4; }
.s-check { display:flex; gap:10px; align-items:flex-start; margin-bottom:14px; }
.s-check input { margin-top:4px; }
.s-check strong { display:block; font-size:14px; }
.s-badge { display:inline-block; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; background:#e6e6e6; color:#555; }
.s-badge.live { background:#c0392b; color:#fff; }
.s-badge.upcoming { background:#a9863f; color:#fff; }
.s-actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:16px; }
.s-actions form { margin:0; }
.s-btn { padding:10px 18px; border-radius:6px; border:1px solid transparent; cursor:pointer; font:inherit; font-weight:600; }
.s-btn.primary { background:#a9863f; color:#fff; }
.s-btn.danger { background:#c0392b; color:#fff; }
.s-btn.ghost { background:transparent; border-color:#a9863f; color:#a9863f; }
.s-preview { aspect-ratio:16/9; background:#000; border-radius:6px; overflow:hidden; display:flex; align-items:center; justify-content:center; color:#aaa; font-size:14px; text-align:center; padding:12px; }
.s-preview iframe, .s-preview video { width:100%; height:100%; border:0; }
.s-help ul { margin:0; padding-left:18px; font-size:14px; line-height:1.7; }
.s-err { color:#b00020; font-size:13px; margin-top:4px; }
</style>

<div class="stream-admin">
  {{-- ============ Settings form ============ --}}
  <div>
    <form method="POST" action="{{ route('admin.stream.update') }}" class="s-card">
      @csrf
      @method('PUT')
      <h2>Stream settings</h2>

      <div class="s-field">
        <label for="stream_provider">Streaming platform</label>
        <select id="stream_provider" name="stream_provider">
          @foreach($providers as $key => $label)
            <option value="{{ $key }}" @selected(old('stream_provider', $memorial->stream_provider ?: 'youtube') === $key)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="s-field">
        <label for="stream_url">Stream link</label>
        <input type="url" id="stream_url" name="stream_url" value="{{ old('stream_url', $memorial->stream_url) }}" placeholder="https://www.youtube.com/watch?v=...">
        <div class="s-hint">Paste the normal share link from the platform. We convert it to an embeddable player for you.</div>
        @error('stream_url')<div class="s-err">{{ $message }}</div>@enderror
      </div>

      <div class="s-field">
        <label for="stream_title">Title shown above the player</label>
        <input type="text" id="stream_title" name="stream_title" value="{{ old('stream_title', $memorial->stream_title) }}" placeholder="Celebration of Life Service">
        @error('stream_title')<div class="s-err">{{ $message }}</div>@enderror
      </div>

      <div class="s-field">
        <label for="stream_description">Short note (optional)</label>
        <textarea id="stream_description" name="stream_description" rows="3" placeholder="Please join us online. The service will begin on time.">{{ old('stream_description', $memorial->stream_description) }}</textarea>
        @error('stream_description')<div class="s-err">{{ $message }}</div>@enderror
      </div>

      <div class="s-field">
        <label for="stream_starts_at">Scheduled start (optional)</label>
        <input type="datetime-local" id="stream_starts_at" name="stream_starts_at" value="{{ $startsVal }}">
        <div class="s-hint">Visitors see a countdown until you go live. Uses the server timezone ({{ config('app.timezone') }}).</div>
        @error('stream_starts_at')<div class="s-err">{{ $message }}</div>@enderror
      </div>

      <div class="s-check">
        <input type="checkbox" id="stream_enabled" name="stream_enabled" value="1" @checked(old('stream_enabled', $memorial->stream_enabled))>
        <label for="stream_enabled"><strong>Show the player on the website</strong>
          <span class="s-hint">When on but not live, visitors see a waiting card with the countdown.</span></label>
      </div>

      <div class="s-check">
        <input type="checkbox" id="stream_is_live" name="stream_is_live" value="1" @checked(old('stream_is_live', $memorial->stream_is_live))>
        <label for="stream_is_live"><strong>We are live now</strong>
          <span class="s-hint">Switches the waiting card to the actual player.</span></label>
      </div>

      <div class="s-check">
        <input type="checkbox" id="stream_autoplay" name="stream_autoplay" value="1" @checked(old('stream_autoplay', $memorial->stream_autoplay ?? true))>
        <label for="stream_autoplay"><strong>Autoplay when visitors arrive</strong>
          <span class="s-hint">Browsers only allow autoplay muted, so viewers tap the speaker icon for sound.</span></label>
      </div>

      <div class="s-actions">
        <button type="submit" class="s-btn primary">Save settings</button>
      </div>
    </form>
  </div>

  {{-- ============ Status / controls / preview ============ --}}
  <div>
    <div class="s-card">
      <h2>Status</h2>
      <p style="margin:0 0 10px;">
        @if($isLive)
          <span class="s-badge live">Live</span> The player is streaming on the site.
        @elseif($isShowing)
          <span class="s-badge upcoming">Upcoming</span> Visitors see the waiting card.
        @else
          <span class="s-badge">Off</span> The player is hidden from the site.
        @endif
      </p>

      <div class="s-actions">
        @unless($isLive)
          <form method="POST" action="{{ route('admin.stream.live') }}">
            @csrf
            <button type="submit" class="s-btn danger">Go live now</button>
          </form>
        @endunless

        @if($isShowing)
          <form method="POST" action="{{ route('admin.stream.end') }}" onsubmit="return confirm('End the stream and hide the player from the site?');">
            @csrf
            <button type="submit" class="s-btn ghost">End stream and hide player</button>
          </form>
        @endif
      </div>
      <div class="s-hint" style="margin-top:12px;">Save your link first, then use <strong>Go live now</strong> when your broadcast has started.</div>
    </div>

    <div class="s-card">
      <h2>Preview</h2>
      <div class="s-preview">
        @if($preview && $preview['mode'])
          @include('partials.stream-player', ['stream' => $preview])
        @else
          Save a valid stream link to preview it here.
        @endif
      </div>
      <div class="s-hint" style="margin-top:8px;">Preview never autoplays and shows the saved link, not unsaved edits.</div>
    </div>

    <div class="s-card s-help">
      <h2>Which link do I paste?</h2>
      <ul>
        <li><strong>YouTube:</strong> the watch or live link, for example youtube.com/watch?v=... or youtube.com/live/...</li>
        <li><strong>Facebook:</strong> the public link to the live video or page video.</li>
        <li><strong>Vimeo:</strong> the video link, or the event link (vimeo.com/event/...).</li>
        <li><strong>HLS:</strong> a .m3u8 playback URL from your encoder, OBS server or CDN (also accepts .mp4).</li>
        <li><strong>Other:</strong> the https embed URL from tools like Restream or StreamYard.</li>
      </ul>
    </div>
  </div>
</div>
@endsection
