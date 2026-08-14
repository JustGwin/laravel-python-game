<?php

namespace App\Http\Controllers;

use App\Models\GameScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * หน้าเล่นเกม (embeds python.html logic แบบ integrated)
     */
    public function index()
    {
        $user       = Auth::user();
        $latestScore = $user->latestScore;
        $bestScore   = $user->bestScore;
        $totalPlays  = $user->gameScores()->count();

        return view('game.index', compact('user', 'latestScore', 'bestScore', 'totalPlays'));
    }

    /**
     * บันทึกคะแนนเมื่อจบเกม (AJAX POST)
     */
    public function saveScore(Request $request)
    {
        $validated = $request->validate([
            'score'              => ['required', 'integer', 'min:0', 'max:100'],
            'levels_completed'   => ['required', 'integer', 'min:0', 'max:6'],
            'level_scores'       => ['required', 'array'],
            'level_scores.*'     => ['integer', 'min:0'],
            'time_spent_seconds' => ['required', 'integer', 'min:0'],
            'level_times'        => ['required', 'array'],
            'level_times.*'      => ['integer', 'min:0'],
            'hints_used'         => ['required', 'integer', 'min:0'],
        ]);

        $validated['user_id']      = Auth::id();
        $validated['completed_at'] = $validated['levels_completed'] >= 6 ? now() : null;

        $gameScore = GameScore::create($validated);

        return response()->json([
            'success'  => true,
            'score_id' => $gameScore->id,
            'grade'    => $gameScore->grade,
            'message'  => 'บันทึกคะแนนเรียบร้อยแล้ว!',
        ]);
    }

    /**
     * ประวัติคะแนนของผู้เล่น
     */
    public function history()
    {
        $user   = Auth::user();
        $scores = $user->gameScores()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('game.history', compact('user', 'scores'));
    }

    /**
     * หน้าสรุปผลเมื่อผ่านครบ 6 ด่าน
     */
    public function complete()
    {
        $user      = Auth::user();
        $bestScore = $user->bestScore;

        if (!$bestScore) {
            return redirect()->route('game.index');
        }

        return view('game.complete', compact('user', 'bestScore'));
    }
}
