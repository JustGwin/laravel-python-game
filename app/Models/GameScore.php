<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score',
        'levels_completed',
        'level_scores',
        'time_spent_seconds',
        'level_times',
        'hints_used',
        'completed_at',
        'is_hidden',
    ];

    protected $casts = [
        'level_scores'       => 'array',
        'level_times'        => 'array',
        'completed_at'       => 'datetime',
        'score'              => 'integer',
        'levels_completed'   => 'integer',
        'time_spent_seconds' => 'integer',
        'hints_used'         => 'integer',
        'is_hidden'          => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * คะแนนเป็น % จาก 100
     */
    public function getScorePercentAttribute(): float
    {
        return round(($this->score / 100) * 100, 1);
    }

    /**
     * แปลงเวลา seconds เป็น mm:ss
     */
    public function getFormattedTimeAttribute(): string
    {
        $mins = intdiv($this->time_spent_seconds, 60);
        $secs = $this->time_spent_seconds % 60;
        return sprintf('%02d:%02d', $mins, $secs);
    }

    /**
     * สถานะ: ผ่านทั้งหมด / ยังไม่ผ่านหมด
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->levels_completed >= 6;
    }

    /**
     * Grade A/B/C/D/F จากคะแนน
     */
    public function getGradeAttribute(): string
    {
        return match(true) {
            $this->score >= 90 => 'A',
            $this->score >= 75 => 'B',
            $this->score >= 60 => 'C',
            $this->score >= 40 => 'D',
            default            => 'F',
        };
    }
}
