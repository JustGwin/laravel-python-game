<?php $__env->startSection('title', 'ประวัติคะแนน'); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <h1 style="font-size:22px;font-weight:700"><i class="fa-solid fa-clock-rotate-left" style="color:var(--accent)"></i> ประวัติคะแนนของฉัน</h1>
        <p style="color:var(--muted);font-size:14px;margin-top:4px"><?php echo e($user->name); ?></p>
    </div>
    <a href="<?php echo e(route('game.index')); ?>" class="btn btn-primary"><i class="fa-solid fa-gamepad"></i> เล่นเกม</a>
</div>

<!-- Stats -->
<div class="stats-grid" style="margin-bottom:20px">
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
                <?php $__empty_1 = true; $__currentLoopData = $scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:var(--muted);font-size:13px">
                        <?php echo e(($scores->currentPage()-1)*10 + $i + 1); ?>

                    </td>
                    <td style="font-size:13px">
                        <?php echo e($score->created_at->format('d/m/Y H:i')); ?><br>
                        <span style="color:var(--muted);font-size:11px"><?php echo e($score->created_at->diffForHumans()); ?></span>
                    </td>
                    <td>
                        <span style="font-size:20px;font-weight:700;color:var(--good)"><?php echo e($score->score); ?></span>
                        <span style="color:var(--muted);font-size:12px">/100</span>
                    </td>
                    <td>
                        <span class="chip chip-<?php echo e(['A'=>'green','B'=>'blue','C'=>'yellow','D'=>'yellow'][$score->grade] ?? 'red'); ?>"
                              style="font-size:15px;padding:4px 14px">
                            <?php echo e($score->grade); ?>

                        </span>
                    </td>
                    <td>
                        <span class="chip chip-<?php echo e($score->levels_completed >= 6 ? 'green' : 'blue'); ?>">
                            <?php echo e($score->levels_completed); ?> / 6
                        </span>
                    </td>
                    <td style="font-size:14px"><?php echo e($score->formatted_time); ?></td>
                    <td style="color:var(--muted);font-size:13px"><?php echo e($score->hints_used); ?> ครั้ง</td>
                    <td>
                        <?php if($score->is_completed): ?>
                            <span class="chip chip-green">ผ่านครบ</span>
                        <?php else: ?>
                            <span class="chip chip-muted">ยังไม่ครบ</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--muted);padding:40px">
                        ยังไม่มีประวัติการเล่น — <a href="<?php echo e(route('game.index')); ?>" style="color:var(--accent)">เริ่มเล่นเลย!</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($scores->hasPages()): ?>
    <div class="pagination" style="padding:16px">
        <?php if(!$scores->onFirstPage()): ?>
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
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/fnwin/Documents/Code/laravel-python-game/resources/views/game/history.blade.php ENDPATH**/ ?>