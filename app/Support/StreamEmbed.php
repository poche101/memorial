<?php

namespace App\Support;

use App\Models\Memorial;
use Illuminate\Support\Carbon;

class StreamEmbed
{
    public const PROVIDERS = [
        'youtube'  => 'YouTube (live or video link)',
        'facebook' => 'Facebook (live or video link)',
        'vimeo'    => 'Vimeo (video or live event link)',
        'hls'      => 'HLS (.m3u8) or direct video file (.mp4)',
        'embed'    => 'Other (embed URL from your streaming platform)',
    ];

    /**
     * Build the data the player needs from the memorial's stream settings.
     * Returns null when streaming is switched off (unless $preview is true,
     * which is used by the admin page to test a link before going live).
     */
    public static function resolve(?Memorial $memorial, bool $preview = false): ?array
    {
        if (! $memorial) {
            return null;
        }

        if (! $preview && ! $memorial->stream_enabled) {
            return null;
        }

        $autoplay = $preview ? false : (bool) $memorial->stream_autoplay;

        $base = [
            'title'       => $memorial->stream_title ?: 'Livestream',
            'description' => $memorial->stream_description,
            'starts_at'   => $memorial->stream_starts_at ? Carbon::parse($memorial->stream_starts_at) : null,
            'live'        => (bool) $memorial->stream_is_live,
            'autoplay'    => $autoplay,
            'mode'        => null,
            'src'         => null,
        ];

        $player = self::build(
            (string) $memorial->stream_provider,
            trim((string) $memorial->stream_url),
            $autoplay
        );

        return array_merge($base, $player ?? []);
    }

    /**
     * Turn a pasted link into something embeddable.
     * Returns ['mode' => 'iframe'|'hls', 'src' => '...'] or null if the link is not valid.
     */
    public static function build(string $provider, string $url, bool $autoplay = true): ?array
    {
        if ($url === '' || ! preg_match('#^https?://#i', $url)) {
            return null;
        }

        $ap = $autoplay ? 1 : 0;

        switch ($provider) {
            case 'youtube':
                $id = self::youtubeId($url);

                return $id ? [
                    'mode' => 'iframe',
                    'src'  => "https://www.youtube-nocookie.com/embed/{$id}?autoplay={$ap}&mute={$ap}&rel=0&playsinline=1&modestbranding=1",
                ] : null;

            case 'facebook':
                if (! preg_match('#facebook\.com|fb\.watch#i', $url)) {
                    return null;
                }

                return [
                    'mode' => 'iframe',
                    'src'  => 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($url)
                        . '&show_text=false&allowfullscreen=true&autoplay=' . ($autoplay ? 'true' : 'false'),
                ];

            case 'vimeo':
                if (preg_match('#vimeo\.com/event/(\d+)#i', $url, $m)) {
                    return [
                        'mode' => 'iframe',
                        'src'  => "https://vimeo.com/event/{$m[1]}/embed?autoplay={$ap}&muted={$ap}",
                    ];
                }

                if (preg_match('#vimeo\.com/(?:.*/)?(\d+)#i', $url, $m)) {
                    return [
                        'mode' => 'iframe',
                        'src'  => "https://player.vimeo.com/video/{$m[1]}?autoplay={$ap}&muted={$ap}&title=0&byline=0",
                    ];
                }

                return null;

            case 'hls':
                return ['mode' => 'hls', 'src' => $url];

            case 'embed':
                return preg_match('#^https://#i', $url)
                    ? ['mode' => 'iframe', 'src' => $url]
                    : null;
        }

        return null;
    }

    public static function label(string $provider): string
    {
        return self::PROVIDERS[$provider] ?? 'stream';
    }

    protected static function youtubeId(string $url): ?string
    {
        if (preg_match(
            '~(?:youtu\.be/|youtube\.com/(?:watch\?(?:.*&)?v=|embed/|live/|shorts/|v/))([A-Za-z0-9_-]{11})~i',
            $url,
            $m
        )) {
            return $m[1];
        }

        return null;
    }
}
