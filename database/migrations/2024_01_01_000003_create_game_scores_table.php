<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // ─── คะแนนรวม ────────────────────────────────────────────────────
            $table->unsignedTinyInteger('score')->default(0)
                  ->comment('คะแนนรวม 0-100');
            $table->unsignedTinyInteger('levels_completed')->default(0)
                  ->comment('จำนวนด่านที่ผ่าน 0-6');

            // ─── คะแนนแยกต่อด่าน ─────────────────────────────────────────────
            $table->json('level_scores')
                  ->nullable()
                  ->comment('คะแนนแต่ละด่าน [d1, d2, ..., d6]');

            // ─── เวลา ─────────────────────────────────────────────────────────
            $table->unsignedInteger('time_spent_seconds')->default(0)
                  ->comment('เวลารวมทั้งหมด (วินาที)');
            $table->json('level_times')
                  ->nullable()
                  ->comment('เวลาแต่ละด่าน (วินาที)');

            // ─── ข้อมูลเพิ่มเติม ──────────────────────────────────────────────
            $table->unsignedSmallInteger('hints_used')->default(0)
                  ->comment('จำนวนคำใบ้ที่ใช้ทั้งหมด');

            $table->timestamp('completed_at')
                  ->nullable()
                  ->comment('เวลาที่ผ่านครบทุกด่าน (null ถ้ายังไม่ผ่าน)');

            $table->timestamps();

            // ─── Indexes ──────────────────────────────────────────────────────
            $table->index(['user_id', 'score']);
            $table->index(['score', 'time_spent_seconds']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_scores');
    }
};
