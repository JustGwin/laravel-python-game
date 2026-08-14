@extends('layouts.app')
@section('title', 'เล่นเกม Python')

@push('styles')
<style>
    :root {
        --g-bg:#0f1220;--g-panel:#171a2b;--g-ink:#e8ebff;--g-muted:#a6b0d8;
        --g-accent:#7c9fff;--g-good:#3ddc84;--g-bad:#ff5d6c;--g-warn:#ffc95d;
    }
    .game-info-bar {
        display:flex; gap:12px; align-items:center; flex-wrap:wrap;
        background:var(--panel); border:1px solid var(--border);
        border-radius:12px; padding:12px 16px; margin-bottom:16px;
    }
    .game-info-bar .gi-item { font-size:13px; color:var(--muted); }
    .game-info-bar .gi-item strong { color:var(--ink); }
    .game-frame-wrap {
        border-radius:16px; overflow:hidden;
        border:1px solid var(--border);
        box-shadow:0 8px 32px rgba(0,0,0,.15);
    }
    iframe { display:block; width:100%; border:none; }
</style>
@endpush

@section('content')

<div style="margin-bottom:14px">
    <h1 style="font-size:22px;font-weight:700"><i class="fa-brands fa-python" style="color:var(--accent);"></i> Python Beginner Game</h1>
    <p style="color:var(--muted);font-size:14px;margin-top:4px">สวัสดี <strong style="color:var(--ink)">{{ $user->name }}</strong> — เล่นเกมแล้วคะแนนจะถูกบันทึกอัตโนมัติ!</p>
</div>

<!-- ─── Game Frame ─────────────────────────────────────────────────────── -->
<div class="game-frame-wrap">
    <iframe
        id="gameFrame"
        src="{{ asset('game_assets/python-game.html') }}"
        height="760"
        title="Python Beginner Game"
    ></iframe>
</div>

@endsection

@push('scripts')
<script>
/*
 * รับ message จาก iframe (python-game.html) เมื่อมีการ save score
 * Game จะส่ง postMessage มาในรูปแบบ:
 * { type: 'SAVE_SCORE', payload: { score, levels_completed, level_scores,
 *   time_spent_seconds, level_times, hints_used } }
 */
window.addEventListener('message', async function(event) {
    if (!event.data || event.data.type !== 'SAVE_SCORE') return;

    const payload = event.data.payload;

    try {
        const res = await fetch('{{ route('game.score.save') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (data.success) {
            // ส่ง ack กลับไปให้ iframe
            document.getElementById('gameFrame').contentWindow.postMessage({
                type: 'SCORE_SAVED',
                grade: data.grade,
                message: data.message,
            }, '*');

            // ถ้าผ่านครบทุกด่าน redirect ไป complete page
            if (payload.levels_completed >= 6) {
                setTimeout(() => {
                    window.location.href = '{{ route('game.complete') }}';
                }, 2000);
            }
        }
    } catch (err) {
        console.error('Save score failed:', err);
    }
});
</script>
@endpush
