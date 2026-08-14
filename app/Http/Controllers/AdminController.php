<?php

namespace App\Http\Controllers;

use App\Models\GameScore;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin Dashboard – ตารางสรุปผู้เล่นทุกคน
     */
    public function dashboard(Request $request)
    {
        $query = User::where('role', 'player')
            ->with(['latestScore', 'bestScore'])
            ->withCount('gameScores as total_plays')
            ->withMax('gameScores as max_score', 'score');

        // Search 
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                  ->orWhere('email','like', "%{$search}%");
            });
        }

        // Sort 
        $sort = $request->input('sort', 'created_at');
        $dir  = $request->input('dir', 'desc');
        $allowedSorts = ['name', 'email', 'created_at', 'max_score'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $dir);
        }

        $players = $query->paginate(20)->withQueryString();

        // Stats Cards 
        $stats = [
            'total_players'   => User::where('role','player')->count(),
            'total_plays'     => GameScore::where('is_hidden', false)->count(),
            'avg_score'       => round(GameScore::where('is_hidden', false)->avg('score') ?? 0, 1),
            'completed_count' => GameScore::where('levels_completed', 6)->where('is_hidden', false)->count(),
            'top_score'       => GameScore::where('is_hidden', false)->max('score') ?? 0,
        ];

        // Top 5 leaderboard 
        $leaderboard = GameScore::with('user')
            ->where('is_hidden', false)
            ->orderByDesc('score')
            ->orderBy('time_spent_seconds')
            ->take(5)
            ->get();

        // ── School Leaderboard (by room) ─────────────────────────────────────
        // Group by school_name → avg score across all players in that school
        $schoolLeaderboard = User::where('role', 'player')
            ->whereNotNull('school')
            ->select('school', DB::raw('AVG(gs.score) as avg_score'), DB::raw('COUNT(DISTINCT users.id) as player_count'))
            ->join('game_scores as gs', 'users.id', '=', 'gs.user_id')
            ->where('gs.is_hidden', false)
            ->groupBy('school')
            ->orderByDesc('avg_score')
            ->limit(10)
            ->get();

        // ── Level Time Chart (avg seconds per level across all scores) ────────
        $allScores = GameScore::where('is_hidden', false)
            ->whereNotNull('level_times')
            ->get(['level_times']);

        $levelTimeSums   = array_fill(0, 6, 0);
        $levelTimeCounts = array_fill(0, 6, 0);
        foreach ($allScores as $gs) {
            $times = is_array($gs->level_times) ? $gs->level_times : json_decode($gs->level_times, true);
            if (! $times) continue;
            foreach (array_slice($times, 0, 6) as $i => $t) {
                $levelTimeSums[$i]   += (int)$t;
                $levelTimeCounts[$i] += 1;
            }
        }
        $levelAvgTimes = [];
        for ($i = 0; $i < 6; $i++) {
            $levelAvgTimes[] = $levelTimeCounts[$i] > 0
                ? round($levelTimeSums[$i] / $levelTimeCounts[$i])
                : 0;
        }

        return view('admin.dashboard', compact(
            'players', 'stats', 'leaderboard', 'sort', 'dir',
            'schoolLeaderboard', 'levelAvgTimes'
        ));
    }

    /**
     * รายละเอียดผู้เล่นคนเดียว
     */
    public function playerDetail(User $user)
    {
        abort_if($user->role !== 'player', 404);

        $scores = $user->gameScores()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.player_detail', compact('user', 'scores'));
    }

    /**
     * ลบ score record เดียว
     */
    public function deleteScore(GameScore $score)
    {
        $score->delete();
        return back()->with('success', 'ลบคะแนนเรียบร้อยแล้ว');
    }

    /**
     * ลบผู้เล่นแบบถาวร
     */
    public function deletePlayer(User $user)
    {
        abort_if($user->role !== 'player', 404);
        $user->gameScores()->delete();
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', "ลบผู้เล่น {$user->name} สำเร็จ");
    }

    /**
     * ซ่อน/แสดง คะแนน
     */
    public function toggleHideScore(GameScore $score)
    {
        $score->is_hidden = !$score->is_hidden;
        $score->save();
        $status = $score->is_hidden ? 'ซ่อน' : 'แสดง';
        return back()->with('success', "{$status}คะแนนเรียบร้อยแล้ว");
    }

    /**
     * Reset คะแนนทั้งหมดของผู้เล่น
     */
    public function resetPlayer(User $user)
    {
        abort_if($user->role !== 'player', 404);
        $user->gameScores()->delete();
        return back()->with('success', "รีเซ็ตคะแนนของ {$user->name} เรียบร้อยแล้ว");
    }

}
