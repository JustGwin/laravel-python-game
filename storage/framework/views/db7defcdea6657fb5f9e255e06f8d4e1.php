<?php $__env->startSection('title', 'ยินดีด้วย! ผ่านครบทุกด่าน'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .complete-wrap {
        max-width: 680px; margin: 40px auto; text-align: center;
    }
    .trophy-icon {
        font-size: 72px; margin-bottom: 20px;
        animation: bounce 1s ease infinite alternate;
    }
    @keyframes bounce {
        from { transform: translateY(0); }
        to   { transform: translateY(-12px); }
    }
    .complete-title {
        font-size: 32px; font-weight: 700;
        background: linear-gradient(135deg, var(--accent), var(--accent2), var(--good));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        margin-bottom: 8px;
    }
    .complete-sub { color: var(--muted); font-size: 16px; margin-bottom: 32px; }

    .result-card {
        background: var(--panel); border: 1px solid var(--border);
        border-radius: 20px; padding: 32px; margin-bottom: 24px;
        position: relative; overflow: hidden;
    }
    .result-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, var(--accent), var(--accent2), var(--good));
    }

    .big-score {
        font-size: 80px; font-weight: 700; line-height: 1;
        background: linear-gradient(135deg, #3ddc84, #36c46e);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .big-grade {
        font-size: 40px; font-weight: 700; margin-top: 8px;
    }

    .result-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 16px; margin-top: 24px;
    }
    .result-item { text-align: center; }
    .result-val { font-size: 22px; font-weight: 700; color: var(--ink); }
    .result-lbl { font-size: 12px; color: var(--muted); margin-top: 4px; }

    .level-breakdown {
        display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;
        margin-top: 20px;
    }
    .level-pill {
        padding: 6px 14px; border-radius: 999px; font-size: 13px; font-weight: 600;
        background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3);
        color: #166534;
    }

    .btn-row { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="complete-wrap">
    <div class="trophy-icon" style="color:var(--warn);font-size:72px;margin-bottom:20px;text-shadow: 0 10px 30px rgba(245,158,11,0.4);"><i class="fa-solid fa-trophy"></i></div>
    <div class="complete-title">ยินดีด้วย! ผ่านครบทุกด่าน!</div>
    <div class="complete-sub">คุณได้สำเร็จการเรียน Python Beginner เรียบร้อยแล้ว</div>

    <div class="result-card">
        <div class="big-score"><?php echo e($bestScore->score); ?></div>
        <div style="font-size:14px;color:var(--muted);margin-top:4px">คะแนนจาก 100 คะแนน</div>

        <div class="big-grade">
            <?php
            $gradeColors = ['A'=>'#10b981','B'=>'#3b82f6','C'=>'#f59e0b','D'=>'#f59e0b','F'=>'#ef4444'];
            ?>
            <span style="color:<?php echo e($gradeColors[$bestScore->grade] ?? '#ff5d6c'); ?>">
                เกรด <?php echo e($bestScore->grade); ?>

            </span>
        </div>

        <div class="result-grid">
            <div class="result-item">
                <div class="result-val"><?php echo e($bestScore->levels_completed); ?>/6</div>
                <div class="result-lbl">ด่านที่ผ่าน</div>
            </div>
            <div class="result-item">
                <div class="result-val"><?php echo e($bestScore->formatted_time); ?></div>
                <div class="result-lbl">เวลาที่ใช้</div>
            </div>
            <div class="result-item">
                <div class="result-val"><?php echo e($bestScore->hints_used); ?></div>
                <div class="result-lbl">คำใบ้ที่ใช้</div>
            </div>
        </div>

        <?php if($bestScore->level_scores): ?>
        <div class="level-breakdown">
            <?php $__currentLoopData = $bestScore->level_scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $pts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="level-pill">ด่าน <?php echo e($i+1); ?>: <?php echo e($pts); ?> คะแนน</div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="btn-row">
        <a href="<?php echo e(route('game.index')); ?>" class="btn btn-primary"><i class="fa-solid fa-rotate-right"></i> เล่นอีกครั้ง</a>
        <a href="<?php echo e(route('game.history')); ?>" class="btn btn-ghost"><i class="fa-solid fa-clock-rotate-left"></i> ดูประวัติ</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/fnwin/Documents/Code/laravel-python-game/resources/views/game/complete.blade.php ENDPATH**/ ?>