<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tribute extends Model
{
    protected $fillable = [
        'memorial_id',
        'name',
        'email',
        'relationship',
        'title',
        'message',
        'image_path',
        'publication_consent',
        'status',
        'is_featured',
        'moderated_by',
        'moderated_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'publication_consent' => 'boolean',
            'is_featured' => 'boolean',
            'moderated_at' => 'datetime',
        ];
    }

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }
}
