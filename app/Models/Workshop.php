<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    protected $fillable = [
        'title',
        'description',
        'instructor',
        'scheduled_at',
        'duration_minutes',
        'max_participants',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function activeRegistrations(): HasMany
    {
        return $this->registrations()->where('status', '!=', 'cancelled');
    }

    public function availableSlots(): int
    {
        $registered = $this->activeRegistrations()->where('status', 'registered')->count();
        return max(0, $this->max_participants - $registered);
    }

    public function isFull(): bool
    {
        return $this->availableSlots() <= 0;
    }
}