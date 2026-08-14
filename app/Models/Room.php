<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_name',
        'code',
        'is_active',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'expires_at' => 'datetime',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Generate a unique 6-character alphanumeric code (uppercase).
     */
    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Is this room currently usable?
     */
    public function isUsable(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        return true;
    }

    public function getPlayerCountAttribute(): int
    {
        return $this->users()->where('role', 'player')->count();
    }

    public function getAvgScoreAttribute(): float
    {
        return round(
            $this->users()
                 ->with('gameScores')
                 ->get()
                 ->flatMap->gameScores
                 ->avg('score') ?? 0,
            1
        );
    }
}
