<?php

namespace App\Http\Controllers;

use App\Models\Memorial;
use Illuminate\Http\JsonResponse;

class StreamStatusController extends Controller
{
    /**
     * Tiny public endpoint the waiting-room card polls so visitors
     * flip to the player automatically when you go live.
     */
    public function __invoke(): JsonResponse
    {
        $memorial = Memorial::first();

        return response()
            ->json([
                'enabled' => (bool) $memorial?->stream_enabled,
                'live'    => (bool) ($memorial?->stream_enabled && $memorial?->stream_is_live),
            ])
            ->header('Cache-Control', 'no-store');
    }
}
