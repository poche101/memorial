<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'memorial_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'venue',
        'address',
        'online_link',
        'rsvp_enabled',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'rsvp_enabled' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
