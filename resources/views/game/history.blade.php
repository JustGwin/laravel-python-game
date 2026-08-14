@extends('layouts.app')
@section('title', 'ประวัติคะแนน')

@section('content')
<div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <h1 style="font-size:22px;font-weight:700"><i class="fa-solid fa-clock-rotate-left" style="color:var(--accent)"></i> ประวัติคะแนนของฉัน</h1>
        <p style="color:var(--muted);font-size:14px;margin-top:4px">{{ $user->name }}</p>
    </div>
    <a href="{{ route('game.index') }}" class="btn btn-primary"><i class="fa-solid fa-gamepad"></i> เล่นเกม</a>
</div>

<!-- Stats -->
<div class="stats-grid" style="margin-bottom:20px">
    <div class="stat-card accent">
        <div class="stat-value"><i class="fa-solid fa-trophy" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $user->highest_score }}</div>
        <div class="stat-label">คะแนนสูงสุด</div>
    </div>
    <div class="stat-card green">
        <div class="stat-value"><i class="fa-solid fa-star" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $user->average_score }}</div>
        <div class="stat-label">คะแนนเฉลี่ย</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-value"><i class="fa-solid fa-play" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $user->total_plays }}</div>
        <div class="stat-label">เล่นทั้งหมด</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-value"><i class="fa-solid fa-circle-check" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> {{ $scores->where('levels_completed', 6)->count() }}</div>
        <div class="stat-label">ครั้งที่ผ่านครบ</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>วันที่เล่น</th>
                    <th>คะแนน</th>
                    <th>เกรด</th>
                    <th>ด่านที่ผ่าน</th>
                    <th>เวลาที่ใช้</th>
                    <th>คำใบ้</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scores as $i => $score)
                <tr>
                    <td style="color:var(--muted);font-size:13px">
                        {{ ($scores->currentPage()-1)*10 + $i + 1 }}
                    </td>
                    <td style="font-size:13px">
                        {{ $score->created_at->format('d/m/Y H:i') }}<br>
                        <span style="color:var(--muted);font-size:11px">{{ $score->created_at->diffForHumans() }}</span>
                    </td>
                    <td>
                        <span style="font-size:20px;font-weight:700;color:var(--good)">{{ $score->score }}</span>
                        <span style="color:var(--muted);font-size:12px">/100</span>
                    </td>
                    <td>
                        <span class="chip chip-{{ ['A'=>'green','B'=>'blue','C'=>'yellow','D'=>'yellow'][$score->grade] ?? 'red' }}"
                              style="font-size:15px;padding:4px 14px">
                            {{ $score->grade }}
                        </span>
                    </td>
                    <td>
                        <span class="chip chip-{{ $score->levels_completed >= 6 ? 'green' : 'blue' }}">
                            {{ $score->levels_completed }} / 6
                        </span>
                    </td>
                    <td style="font-size:14px">{{ $score->formatted_time }}</td>
                    <td style="color:var(--muted);font-size:13px">{{ $score->hints_used }} ครั้ง</td>
                    <td>
                        @if($score->is_completed)
                            <span class="chip chip-green">ผ่านครบ</span>
                        @else
                            <span class="chip chip-muted">ยังไม่ครบ</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--muted);padding:40px">
                        ยังไม่มีประวัติการเล่น — <a href="{{ route('game.index') }}" style="color:var(--accent)">เริ่มเล่นเลย!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($scores->hasPages())
    <div class="pagination" style="padding:16px">
        @if(!$scores->onFirstPage())
            <a href="{{ $scores->previousPageUrl() }}">‹</a>
        @endif
        @foreach($scores->links()->elements[0] as $page => $url)
            @if($page == $scores->currentPage())
                <span class="active">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach
        @if($scores->hasMorePages())
            <a href="{{ $scores->nextPageUrl() }}">›</a>
        @endif
    </div>
    @endif
</div>
@endsection
