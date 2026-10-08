@php
    $mode     = data_get($stream, 'mode');
    $src      = data_get($stream, 'src');
    $title    = data_get($stream, 'title', 'Livestream');
    $autoplay = (bool) data_get($stream, 'autoplay', false);
    $isVideo  = in_array($mode, ['hls', 'video', 'file', 'mp4'], true);
    $playerId = 'streamVideo' . substr(md5((string) $src), 0, 6);
@endphp

@if ($src && $isVideo)
    <video id="{{ $playerId }}"
           controls
           playsinline
           preload="auto"
           @if($autoplay) autoplay muted @endif
           title="{{ $title }}"
           style="background:#000;"></video>

    @if ($mode === 'hls')
        <script src="https://cdn.jsdelivr.net/npm/hls.js@1"></script>
    @endif

    <script>
    (function () {
        var video = document.getElementById(@json($playerId));
        var src   = @json($src);
        var mode  = @json($mode);
        if (!video) return;

        function tryPlay() {
            var p = video.play();
            if (p && p.catch) p.catch(function () {});
        }

        if (mode !== 'hls') {
            video.src = src;
            return;
        }

        if (window.Hls && Hls.isSupported()) {
            var hls = new Hls({ lowLatencyMode: true });
            hls.loadSource(src);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, tryPlay);
            hls.on(Hls.Events.ERROR, function (e, data) {
                console.warn('HLS error', data.type, data.details);
                if (!data.fatal) return;
                if (data.type === Hls.ErrorDetails.NETWORK_ERROR || data.type === 'networkError') {
                    setTimeout(function () { hls.startLoad(); }, 3000);
                } else if (data.type === 'mediaError') {
                    hls.recoverMediaError();
                } else {
                    hls.destroy();
                }
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            // Safari / iOS play HLS natively
            video.src = src;
            video.addEventListener('loadedmetadata', tryPlay);
        }
    })();
    </script>
@elseif ($src)
    <iframe src="{{ $src }}"
            title="{{ $title }}"
            allow="autoplay; encrypted-media; picture-in-picture; fullscreen"
            allowfullscreen
            loading="lazy"></iframe>
@else
    <div class="stream-wait">
        <div class="wait-title">No stream is available right now.</div>
    </div>
@endif
