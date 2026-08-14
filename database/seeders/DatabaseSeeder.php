<?php

namespace Database\Seeders;

use App\Models\GameScore;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin Account ────────────────────────────────────────────────────
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@pythongame.com',
            'password' => Hash::make('admin1234'),
            'role'     => 'admin',
        ]);

        // ─── Demo Player Accounts ─────────────────────────────────────────────
        $players = [
            ['name' => 'สมชาย ใจดี',    'email' => 'somchai@demo.com',   'password' => 'player1234'],
            ['name' => 'สมหญิง รักเรียน','email' => 'somying@demo.com',  'password' => 'player1234'],
            ['name' => 'วิชัย เก่งมาก',  'email' => 'wichai@demo.com',   'password' => 'player1234'],
            ['name' => 'นภา สดใส',       'email' => 'napa@demo.com',     'password' => 'player1234'],
            ['name' => 'ทดสอบ ระบบ',    'email' => 'player@demo.com',   'password' => 'player1234'],
        ];

        foreach ($players as $p) {
            $user = User::create([
                'name'     => $p['name'],
                'email'    => $p['email'],
                'password' => Hash::make($p['password']),
                'role'     => 'player',
            ]);

            // ─── สร้าง demo scores ────────────────────────────────────────────
            $levelsCompleted = rand(2, 6);
            $levelScores     = [];
            $levelTimes      = [];
            $totalScore      = 0;
            $totalTime       = 0;

            for ($i = 0; $i < 6; $i++) {
                if ($i < $levelsCompleted) {
                    $pts            = rand(12, 17);   // ~16.67 per level
                    $secs           = rand(30, 180);
                    $levelScores[]  = $pts;
                    $levelTimes[]   = $secs;
                    $totalScore    += $pts;
                    $totalTime     += $secs;
                } else {
                    $levelScores[] = 0;
                    $levelTimes[]  = 0;
                }
            }

            GameScore::create([
                'user_id'            => $user->id,
                'score'              => min($totalScore, 100),
                'levels_completed'   => $levelsCompleted,
                'level_scores'       => $levelScores,
                'time_spent_seconds' => $totalTime,
                'level_times'        => $levelTimes,
                'hints_used'         => rand(0, 5),
                'completed_at'       => $levelsCompleted >= 6 ? now() : null,
            ]);
        }
    }
}
