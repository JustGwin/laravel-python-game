@extends('layouts.app')
@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .page-header { margin-bottom: 24px; }
    .page-title  { font-size: 24px; font-weight: 700; }
    .page-sub    { font-size: 14px; color: var(--muted); margin-top: 4px; }

    /* ─── Leaderboard ─────────────────────────────────────────────────── */
    .section-title {
        font-size: 16px; font-weight: 700; margin-bottom: 14px;
        display: flex; align-items: center; gap: 8px;
    }
    .lb-row {
        display: flex; align-items: center; gap: 14px;
        padding: 12px 16px; border-radius: 10px;
        transition: background .15s;
    }
    .lb-row:hover { background: rgba(59,130,246,.04); }
    .lb-row + .lb-row { border-top: 1px solid var(--border); }
    .lb-rank {
        width: 28px; height: 28px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; flex-shrink: 0;
    }
    .lb-rank.r1 { background: linear-gradient(135deg,#ffd700,#ffa500); color: #2a1500; }
    .lb-rank.r2 { background: linear-gradient(135deg,#c0c0c0,#a0a0a0); color: #1a1a1a; }
    .lb-rank.r3 { background: linear-gradient(135deg,#cd7f32,#b06000); color: #fff; }
    .lb-rank.rn { background: var(--surface); color: var(--muted); }
    .lb-name  { flex: 1; font-size: 14px; font-weight: 600; }
    .lb-score { font-size: 18px; font-weight: 700; color: var(--good); }
    .lb-time  { font-size: 12px; color: var(--muted); }

    /* ─── Search Bar ──────────────────────────────────────────────────── */
    .toolbar-row {
        display: flex; gap: 10px; align-items: center;
        margin-bottom: 16px; flex-wrap: wrap;
    }
    .search-input {
        flex: 1; min-width: 200px; padding: 9px 14px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 10px; color: var(--ink); font-size: 14px;
        font-family: inherit;
    }
    .search-input:focus { outline: none; border-color: var(--accent); }

    /* ─── Player Table ────────────────────────────────────────────────── */
    .score-bar { display: flex; align-items: center; gap: 8px; }
    .bar-track {
        flex: 1; height: 6px; background: var(--surface);
        border-radius: 999px; overflow: hidden;
    }
    .bar-fill { height: 100%; border-radius: 999px; transition: width .5s; }
    .bar-high   { background: linear-gradient(90deg,#3ddc84,#36c46e); }
    .bar-mid    { background: linear-gradient(90deg,#ffc95d,#ff9d3d); }
    .bar-low    { background: linear-gradient(90deg,#ff8fa3,#ff5d6c); }
    .score-num { font-weight: 700; font-size: 15px; min-width: 36px; text-align: right; }

    /* ─── Actions ─────────────────────────────────────────────────────── */
    .action-group { display: flex; gap: 6px; flex-wrap: wrap; }

    /* ─── Grid ────────────────────────────────────────────────────────── */
    .dash-grid {
        display: grid; gap: 20px;
        grid-template-columns: 1fr;
    }
    @media(min-width: 960px) {
        .dash-grid { grid-template-columns: 2.2fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Admin Dashboard</h1>
    <p class="page-sub">จัดการผู้เล่นและดูสถิติคะแนน</p>
</div>

<!-- ─── Stats Cards ─────────────────────────────────────────────────── -->
<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card accent">
        <div class="stat-value"><i class="fa-solid fa-users" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $stats['total_players'] }}</div>
        <div class="stat-label">ผู้เล่นทั้งหมด</div>
    </div>
    <div class="stat-card green">
        <div class="stat-value"><i class="fa-solid fa-play" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $stats['total_plays'] }}</div>
        <div class="stat-label">เซสชันทั้งหมด</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-value"><i class="fa-solid fa-star" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $stats['avg_score'] }}</div>
        <div class="stat-label">คะแนนเฉลี่ย</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-value"><i class="fa-solid fa-circle-check" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $stats['completed_count'] }}</div>
        <div class="stat-label">ผ่านครบ 6 ด่าน</div>
    </div>
    <div class="stat-card red">
        <div class="stat-value"><i class="fa-solid fa-trophy" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $stats['top_score'] }}</div>
        <div class="stat-label">คะแนนสูงสุด</div>
    </div>
</div>

<!-- ─── Analytics Charts ────────────────────────────────────────────── -->
<div class="dash-grid" style="margin-bottom:24px; grid-template-columns: 1fr; @media(min-width: 960px){ grid-template-columns: 1fr 1fr; }">
    <div class="card" style="padding:20px">
        <div class="section-title"><i class="fa-solid fa-chart-column"></i> คะแนนเฉลี่ยสูงสุด 10 โรงเรียนแรก</div>
        <div style="position: relative; height:250px; width:100%;">
            <canvas id="schoolChart"></canvas>
        </div>
    </div>
    <div class="card" style="padding:20px">
        <div class="section-title"><i class="fa-solid fa-clock"></i> เวลาที่ใช้เฉลี่ยแต่ละด่าน (วินาที)</div>
        <div style="position: relative; height:250px; width:100%;">
            <canvas id="levelTimeChart"></canvas>
        </div>
    </div>
</div>

<div class="dash-grid">
    <!-- ─── Players Table ──────────────────────────────────────────── -->
    <div class="card" style="padding:20px">
        <div class="section-title"><i class="fa-solid fa-table-list"></i> รายชื่อผู้เล่น</div>

        <!-- Search & Export -->
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <div class="toolbar-row">
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="ค้นหาชื่อหรืออีเมล..."
                    value="{{ request('search') }}"
                />
                <select name="sort" class="search-input" style="max-width:200px" onchange="this.form.submit()">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>ล่าสุดไปเก่าสุด</option>
                    <option value="max_score" {{ request('sort') == 'max_score' ? 'selected' : '' }}>คะแนนมากสุดไปน้อยสุด</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>ตามชื่อผู้เล่น</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i> ค้นหา</button>
            </div>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ชื่อ / อีเมล</th>
                        <th>คะแนนสูงสุด</th>
                        <th>ด่านที่ผ่าน</th>
                        <th>เล่นทั้งหมด</th>
                        <th>เวลารวม</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $player)
                    <tr>
                        <td>
                            <div style="font-weight:600">{{ $player->name }}</div>
                            <div style="font-size:12px;color:var(--muted)">โรงเรียน: {{ $player->school ?? '-' }}</div>
                            <div style="font-size:12px;color:var(--muted)">{{ $player->email }}</div>
                        </td>
                        <td>
                            @if($player->bestScore)
                                <div class="score-bar">
                                    <div class="bar-track">
                                        <div class="bar-fill
                                            {{ $player->bestScore->score >= 70 ? 'bar-high' : ($player->bestScore->score >= 40 ? 'bar-mid' : 'bar-low') }}"
                                            style="width:{{ $player->bestScore->score }}%"
                                        ></div>
                                    </div>
                                    <span class="score-num">{{ $player->bestScore->score }}</span>
                                    <span class="chip chip-{{ $player->bestScore->grade === 'A' ? 'green' : ($player->bestScore->grade === 'B' ? 'blue' : ($player->bestScore->grade === 'C' ? 'yellow' : 'red')) }}">
                                        {{ $player->bestScore->grade }}
                                    </span>
                                </div>
                            @else
                                <span class="chip chip-muted">ยังไม่ได้เล่น</span>
                            @endif
                        </td>
                        <td>
                            @if($player->bestScore)
                                <span class="chip chip-{{ $player->bestScore->levels_completed >= 6 ? 'green' : 'blue' }}">
                                    {{ $player->bestScore->levels_completed }} / 6 ด่าน
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <span class="chip chip-muted">{{ $player->total_plays }} ครั้ง</span>
                        </td>
                        <td style="color:var(--muted);font-size:13px">
                            @if($player->bestScore)
                                {{ $player->bestScore->formatted_time }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.player.detail', $player) }}" class="btn btn-ghost btn-sm"><i class="fa-solid fa-eye"></i> ดู</a>
                                <form method="POST" action="{{ route('admin.player.reset', $player) }}"
                                      onsubmit="return confirm('รีเซ็ตคะแนนของ {{ $player->name }} ทั้งหมด?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                                </form>
                                <form method="POST" action="{{ route('admin.player.delete', $player) }}"
                                      onsubmit="return confirm('ลบผู้เล่น {{ $player->name }} และข้อมูลทั้งหมดแบบถาวร?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="background:var(--bad)"><i class="fa-solid fa-trash"></i> ลบถาวร</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--muted);padding:32px">
                            ไม่พบผู้เล่น
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($players->hasPages())
        <div class="pagination" style="margin-top:16px">
            @if($players->onFirstPage())
                <span>‹</span>
            @else
                <a href="{{ $players->previousPageUrl() }}">‹</a>
            @endif

            @foreach($players->links()->elements[0] as $page => $url)
                @if($page == $players->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($players->hasMorePages())
                <a href="{{ $players->nextPageUrl() }}">›</a>
            @else
                <span>›</span>
            @endif
        </div>
        @endif
    </div>

    <!-- ─── Leaderboard ────────────────────────────────────────────── -->
    <div style="display:flex; flex-direction:column; gap:20px;">
        <!-- Top 5 Players -->
        <div class="card" style="padding:20px">
            <div class="section-title"><i class="fa-solid fa-ranking-star"></i> Top 5 ผู้เล่นสูงสุด</div>
            @forelse($leaderboard as $i => $entry)
                <div class="lb-row">
                    <div class="lb-rank {{ ['r1','r2','r3','rn','rn'][$i] ?? 'rn' }}">
                        {{ ($i+1) }}
                    </div>
                    <div class="lb-name">
                        <div>{{ $entry->user?->name ?? 'Unknown' }}</div>
                        <div style="font-size:11px;font-weight:400;color:var(--muted)">{{ $entry->levels_completed }}/6 ด่าน</div>
                    </div>
                    <div style="text-align:right">
                        <div class="lb-score">{{ $entry->score }}<span style="font-size:12px;color:var(--muted)">/100</span></div>
                        <div class="lb-time">{{ $entry->formatted_time }}</div>
                    </div>
                </div>
            @empty
                <p style="color:var(--muted);font-size:13px;padding:20px 0;text-align:center">ยังไม่มีข้อมูล</p>
            @endforelse
        </div>

        <!-- Top 10 Schools -->
        <div class="card" style="padding:20px">
            <div class="section-title"><i class="fa-solid fa-school"></i> Top 10 โรงเรียนคะแนนสูงสุด</div>
            @forelse($schoolLeaderboard as $i => $school)
                <div class="lb-row">
                    <div class="lb-rank {{ ['r1','r2','r3','rn','rn'][$i] ?? 'rn' }}">
                        {{ ($i+1) }}
                    </div>
                    <div class="lb-name">
                        <div>{{ $school->school }}</div>
                        <div style="font-size:11px;color:var(--muted)"><i class="fa-solid fa-users"></i> {{ $school->player_count }} คน</div>
                    </div>
                    <div style="text-align:right">
                        <div class="lb-score" style="color:var(--accent);">{{ round($school->avg_score, 1) }}</div>
                    </div>
                </div>
            @empty
                <p style="color:var(--muted);font-size:13px;padding:20px 0;text-align:center">ยังไม่มีข้อมูล</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── School Chart ──────────────────────────────────────────────────────────
    const schoolCtx = document.getElementById('schoolChart').getContext('2d');
    const schoolData = @json($schoolLeaderboard);
    
    const schoolLabels = schoolData.map(s => s.school);
    const schoolScores = schoolData.map(s => s.avg_score);

    new Chart(schoolCtx, {
        type: 'bar',
        data: {
            labels: schoolLabels,
            datasets: [{
                label: 'คะแนนเฉลี่ย',
                data: schoolScores,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100 }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // ── Level Time Chart ──────────────────────────────────────────────────────
    const levelTimeCtx = document.getElementById('levelTimeChart').getContext('2d');
    const levelTimes = @json($levelAvgTimes);
    const levelLabels = ['ด่าน 1', 'ด่าน 2', 'ด่าน 3', 'ด่าน 4', 'ด่าน 5', 'ด่าน 6'];

    new Chart(levelTimeCtx, {
        type: 'line',
        data: {
            labels: levelLabels,
            datasets: [{
                label: 'เวลาเฉลี่ย (วินาที)',
                data: levelTimes,
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                pointRadius: 4,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush
