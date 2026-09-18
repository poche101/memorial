<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineEntry extends Model
{
    protected $fillable = [
        'memorial_id',
        'year_label',
        'event_date',
        'title',
        'description',
        'image_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return ['event_date' => 'date'];
    }

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }
}
