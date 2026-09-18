<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model

{
    protected $table = 'media';

    protected $fillable = [
        'album_id',
        'type',
        'path',
        'thumbnail_path',
        'caption',
        'description',
        'sort_order',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function url(): string
    {
        return str_starts_with($this->path, 'http')
            ? $this->path
            : asset('storage/'.$this->path);
    }
}
