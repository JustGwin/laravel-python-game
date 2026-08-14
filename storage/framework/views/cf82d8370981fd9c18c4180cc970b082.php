<?php $__env->startSection('title', 'รายละเอียด: ' . $user->name); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--muted); font-size: 13px; text-decoration: none;
        margin-bottom: 20px; transition: color .2s;
    }
    .back-link:hover { color: var(--ink); }

    .player-header {
        display: flex; align-items: center; gap: 20px;
        flex-wrap: wrap; margin-bottom: 24px;
    }
    .player-avatar {
        width: 64px; height: 64px; border-radius: 16px;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; font-weight: 700; color: #0a0e2a; flex-shrink: 0;
    }
    .player-info h1 { font-size: 22px; font-weight: 700; }
    .player-info p  { color: var(--muted); font-size: 14px; margin-top: 2px; }

    /* Level dots */
    .level-dots { display: flex; gap: 8px; flex-wrap: wrap; margin: 14px 0; }
    .ldot {
        width: 38px; height: 38px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; border: 2px solid transparent;
    }
    .ldot.done    { background: var(--good); color: #042012; border-color: #2db967; }
    .ldot.pending { background: var(--surface); color: var(--muted); border-color: var(--border); }

    /* Score history rows */
    .history-row {
        display: grid;
        grid-template-columns: auto 1fr auto auto auto;
        align-items: center; gap: 14px;
        padding: 14px 18px; border-bottom: 1px solid rgba(36,45,85,.5);
    }
    .history-row:last-child { border-bottom: none; }
    .rank-circle {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--surface); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; color: var(--muted);
    }

    .mini-level-bars { display: flex; gap: 3px; align-items: flex-end; }
    .mini-bar {
        width: 10px; border-radius: 3px 3px 0 0;
        background: var(--good); transition: height .3s;
    }
    .mini-bar.empty { background: var(--surface); border: 1px solid var(--border); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="back-link"><i class="fa-solid fa-arrow-left"></i> กลับ Dashboard</a>

<!-- ─── Player Header ────────────────────────────────────────────────── -->
<div class="player-header">
    <div class="player-avatar"><?php echo e(mb_substr($user->name, 0, 1)); ?></div>
    <div class="player-info">
        <h1><?php echo e($user->name); ?></h1>
        <p><?php echo e($user->email); ?> • โรงเรียน: <?php echo e($user->school ?? '-'); ?> • เข้าร่วมเมื่อ <?php echo e($user->created_at->diffForHumans()); ?></p>
    </div>
    <div style="margin-left:auto">
        <form method="POST" action="<?php echo e(route('admin.player.reset', $user)); ?>"
              onsubmit="return confirm('รีเซ็ตคะแนนทั้งหมดของ <?php echo e($user->name); ?>?')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger"><i class="fa-solid fa-rotate-left"></i> Reset คะแนนทั้งหมด</button>
        </form>
    </div>
</div>

<!-- ─── Summary Cards ──────────────────────────────────────────────── -->
<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card accent">
        <div class="stat-value"><i class="fa-solid fa-trophy" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> <?php echo e($user->highest_score); ?></div>
        <div class="stat-label">คะแนนสูงสุด</div>
    </div>
    <div class="stat-card green">
        <div class="stat-value"><i class="fa-solid fa-star" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> <?php echo e($user->average_score); ?></div>
        <div class="stat-label">คะแนนเฉลี่ย</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-value"><i class="fa-solid fa-play" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> <?php echo e($user->total_plays); ?></div>
        <div class="stat-label">เล่นทั้งหมด</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-value"><i class="fa-solid fa-circle-check" style="font-size:20px; opacity:0.8; margin-right:4px;"></i> <?php echo e($scores->where('levels_completed', 6)->count()); ?></div>
        <div class="stat-label">ครั้งที่ผ่านครบ</div>
    </div>
</div>

<!-- ─── Score History ───────────────────────────────────────────────── -->
<div class="card" style="padding:20px">
    <div style="font-size:16px;font-weight:700;margin-bottom:16px"><i class="fa-solid fa-clock-rotate-left"></i> ประวัติการเล่น</div>

    <?php $__empty_1 = true; $__currentLoopData = $scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="history-row">
        <!-- Rank -->
        <div class="rank-circle">#<?php echo e(($scores->currentPage() - 1) * 10 + $i + 1); ?></div>

        <!-- Main Info -->
        <div>
            <div style="font-size:14px;font-weight:600;margin-bottom:6px">
                เล่นเมื่อ <?php echo e($score->created_at->format('d/m/Y H:i')); ?>

                <?php if($score->is_completed): ?>
                    <span class="chip chip-green" style="margin-left:8px">ผ่านครบ</span>
                <?php endif; ?>
                <?php if($score->is_hidden): ?>
                    <span class="chip chip-muted" style="margin-left:8px; background:#fee2e2; color:#991b1b; border:1px solid #fecaca;"><i class="fa-solid fa-eye-slash"></i> ซ่อนอยู่</span>
                <?php endif; ?>
            </div>
            <!-- Mini Level Bars -->
            <div class="mini-level-bars">
                <?php $ls = $score->level_scores ?? array_fill(0, 6, 0); ?>
                <?php for($d = 0; $d < 6; $d++): ?>
                    <?php $pts = $ls[$d] ?? 0; ?>
                    <div
                        class="mini-bar <?php echo e($pts > 0 ? '' : 'empty'); ?>"
                        style="height: <?php echo e(max(8, $pts * 2)); ?>px"
                        title="ด่าน <?php echo e($d+1); ?>: <?php echo e($pts); ?> คะแนน"
                    ></div>
                <?php endfor; ?>
                <span style="font-size:11px;color:var(--muted);margin-left:6px;align-self:center"><?php echo e($score->levels_completed); ?>/6 ด่าน</span>
            </div>
        </div>

        <!-- Score -->
        <div style="text-align:center">
            <div style="font-size:22px;font-weight:700;color:var(--good)"><?php echo e($score->score); ?></div>
            <div style="font-size:11px;color:var(--muted)">คะแนน</div>
        </div>

        <!-- Grade -->
        <div>
            <span class="chip chip-<?php echo e(['A'=>'green','B'=>'blue','C'=>'yellow','D'=>'yellow'][$score->grade] ?? 'red'); ?>" style="font-size:16px;padding:5px 14px">
                <?php echo e($score->grade); ?>

            </span>
        </div>

        <!-- Time + Delete -->
        <div style="text-align:right">
            <div style="font-size:14px;margin-bottom:6px"><?php echo e($score->formatted_time); ?></div>
            <div style="font-size:12px;color:var(--muted);margin-bottom:8px">ใบ้ <?php echo e($score->hints_used); ?> ครั้ง</div>
            <div style="display:flex; gap:6px; justify-content:flex-end;">
                <form method="POST" action="<?php echo e(route('admin.score.toggle-hide', $score)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <?php if($score->is_hidden): ?>
                        <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--good);"><i class="fa-solid fa-eye"></i> แสดง</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-ghost btn-sm"><i class="fa-solid fa-eye-slash"></i> ซ่อน</button>
                    <?php endif; ?>
                </form>
                <form method="POST" action="<?php echo e(route('admin.score.delete', $score)); ?>"
                      onsubmit="return confirm('ลบคะแนนนี้?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p style="color:var(--muted);text-align:center;padding:32px">ยังไม่มีประวัติการเล่น</p>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if($scores->hasPages()): ?>
    <div class="pagination" style="margin-top:16px">
        <?php if($scores->onFirstPage()): ?>
            <span>‹</span>
        <?php else: ?>
            <a href="<?php echo e($scores->previousPageUrl()); ?>">‹</a>
        <?php endif; ?>
        <?php $__currentLoopData = $scores->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($page == $scores->currentPage()): ?>
                <span class="active"><?php echo e($page); ?></span>
            <?php else: ?>
                <a href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if($scores->hasMorePages()): ?>
            <a href="<?php echo e($scores->nextPageUrl()); ?>">›</a>
        <?php else: ?>
            <span>›</span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/fnwin/Documents/Code/laravel-python-game/resources/views/admin/player_detail.blade.php ENDPATH**/ ?>