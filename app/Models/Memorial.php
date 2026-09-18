<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Memorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'title',
        'birth_date',
        'death_date',
        'portrait_path',
        'brochure_path',
        'statement',
        'biography',
        'status',
        'visibility_settings',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'death_date' => 'date',
            'visibility_settings' => 'array',
        ];
    }

    public function timelineEntries(): HasMany
    {
        return $this->hasMany(TimelineEntry::class)->orderBy('sort_order');
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class)->orderBy('sort_order');
    }

    public function tributes(): HasMany
    {
        return $this->hasMany(Tribute::class);
    }

    public function approvedTributes(): HasMany
    {
        return $this->tributes()->where('status', 'approved')->latest();
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('event_date');
    }

    public function remembrances(): HasMany
    {
        return $this->hasMany(Remembrance::class);
    }

    public function ageAtDeath(): ?int
    {
        if (! $this->birth_date || ! $this->death_date) {
            return null;
        }

        return $this->birth_date->diffInYears($this->death_date);
    }
}
