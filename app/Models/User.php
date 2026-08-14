<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'school',
        'room_id',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function gameScores()
    {
        return $this->hasMany(GameScore::class);
    }

    public function latestScore()
    {
        return $this->hasOne(GameScore::class)->latestOfMany();
    }

    public function bestScore()
    {
        return $this->hasOne(GameScore::class)->ofMany('score', 'max');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPlayer(): bool
    {
        return $this->role === 'player';
    }

    public function getTotalPlaysAttribute(): int
    {
        return $this->gameScores()->count();
    }

    public function getHighestScoreAttribute(): int
    {
        return $this->gameScores()->max('score') ?? 0;
    }

    public function getAverageScoreAttribute(): float
    {
        return round($this->gameScores()->avg('score') ?? 0, 1);
    }
}
