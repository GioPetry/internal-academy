<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'workshop_id',
        'status',
        'position',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }

    public function isRegistered(): bool
    {
        return $this->status === 'registered';
    }

    public function isWaitlisted(): bool
    {
        return $this->status === 'waitlisted';
    }

    public function promoteFromWaitlist(int $workshopId): ?Registration
    {
        $nextInLine = self::where('workshop_id', $workshopId)
            ->where('status', 'waitlisted')
            ->orderBy('position', 'asc')
            ->first();

        if ($nextInLine) {
            $nextInLine->update(['status' => 'registered']);
            $nextInLine->save();
            return $nextInLine;
        }

        return null;
    }
}