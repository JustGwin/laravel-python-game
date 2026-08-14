<?php $__env->startSection('title', 'จัดการห้องเรียน'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .page-header { margin-bottom: 24px; }
    .page-title  { font-size: 24px; font-weight: 700; }
    .page-sub    { color: var(--muted); font-size: 14px; margin-top: 4px; }

    /* ─── Code Badge ───────────────────────────────────────────── */
    .room-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 20px; font-weight: 800; letter-spacing: 4px;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        cursor: pointer; user-select: all;
    }
    .room-code-wrap {
        display: flex; align-items: center; gap: 8px;
    }
    .copy-btn {
        background: none; border: none; cursor: pointer;
        color: var(--muted); font-size: 13px; padding: 4px 8px;
        border-radius: 6px; transition: .2s;
    }
    .copy-btn:hover { background: var(--bg); color: var(--accent); }

    /* ─── Status Badge ─────────────────────────────────────────── */
    .badge-status {
        font-size: 12px; font-weight: 600; padding: 4px 12px;
        border-radius: 999px;
    }
    .badge-status.open   { background: #dcfce7; color: #166534; }
    .badge-status.closed { background: #fee2e2; color: #991b1b; }
    .badge-status.expired { background: #fef9c3; color: #854d0e; }

    /* ─── Create Modal ─────────────────────────────────────────── */
    .modal-backdrop {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.4); z-index: 200;
        align-items: center; justify-content: center;
    }
    .modal-backdrop.open { display: flex; }
    .modal {
        background: var(--panel); border-radius: 20px;
        padding: 32px; width: 100%; max-width: 460px;
        box-shadow: 0 24px 64px rgba(0,0,0,.15);
        animation: slideUp .25s ease;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .modal h2 { font-size: 20px; font-weight: 700; margin-bottom: 20px; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: var(--muted); }
    .form-input {
        width: 100%; padding: 10px 14px; border: 1.5px solid var(--border);
        border-radius: 12px; font-size: 14px; background: var(--surface);
        color: var(--ink); outline: none; transition: border-color .2s;
        font-family: inherit;
    }
    .form-input:focus { border-color: var(--accent); }
    .modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; }

    /* ─── Table ────────────────────────────────────────────────── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th { text-align: left; padding: 12px 16px; font-size: 12px; font-weight: 600;
         color: var(--muted); border-bottom: 2px solid var(--border); text-transform: uppercase; letter-spacing: .5px; }
    td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .action-group { display: flex; gap: 6px; flex-wrap: wrap; }
    .empty-state { text-align: center; color: var(--muted); padding: 60px 20px; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; color: #cbd5e1; display: block; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-door-open" style="color:var(--accent)"></i> จัดการห้องเรียน</h1>
        <p class="page-sub">สร้างห้องและแจก Room Code ให้นักเรียนใช้เข้าระบบ</p>
    </div>
    <button class="btn btn-primary" onclick="openModal()">
        <i class="fa-solid fa-plus"></i> สร้างห้องใหม่
    </button>
</div>


<div class="card" style="padding:20px">
    <?php if($rooms->isEmpty()): ?>
        <div class="empty-state">
            <i class="fa-solid fa-door-closed"></i>
            <p style="font-size:16px; font-weight:600; margin-bottom:8px">ยังไม่มีห้องเรียน</p>
            <p style="font-size:14px">กดปุ่ม "สร้างห้องใหม่" เพื่อสร้างห้องและแจก Code ให้นักเรียน</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ชื่อห้อง / โรงเรียน</th>
                        <th>Room Code</th>
                        <th>ผู้เล่น</th>
                        <th>หมดอายุ</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div style="font-weight:600"><?php echo e($room->name); ?></div>
                            <div style="font-size:12px;color:var(--muted)">
                                <i class="fa-solid fa-school"></i> <?php echo e($room->school_name); ?>

                            </div>
                        </td>
                        <td>
                            <div class="room-code-wrap">
                                <span class="room-code" id="code-<?php echo e($room->id); ?>" title="คลิกเพื่อคัดลอก">
                                    <?php echo e($room->code); ?>

                                </span>
                                <button class="copy-btn" onclick="copyCode('<?php echo e($room->code); ?>', this)" title="คัดลอก">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="chip chip-blue">
                                <i class="fa-solid fa-users"></i> <?php echo e($room->player_count); ?> คน
                            </span>
                        </td>
                        <td style="font-size:13px; color:var(--muted)">
                            <?php if($room->expires_at): ?>
                                <?php echo e($room->expires_at->format('d/m/Y H:i')); ?>

                            <?php else: ?>
                                <span style="color:var(--good)">ไม่มีกำหนด</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-group">
                                
                                <form method="POST" action="<?php echo e(route('admin.rooms.destroy', $room)); ?>"
                                      onsubmit="return confirm('ลบห้อง <?php echo e($room->name); ?>? ผู้เล่นจะยังอยู่ในระบบแต่จะไม่ผูกกับห้องนี้')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php if($rooms->hasPages()): ?>
        <div class="pagination" style="margin-top:16px">
            <?php if(!$rooms->onFirstPage()): ?>
                <a href="<?php echo e($rooms->previousPageUrl()); ?>">‹</a>
            <?php endif; ?>
            <?php $__currentLoopData = $rooms->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page == $rooms->currentPage()): ?>
                    <span class="active"><?php echo e($page); ?></span>
                <?php else: ?>
                    <a href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($rooms->hasMorePages()): ?>
                <a href="<?php echo e($rooms->nextPageUrl()); ?>">›</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>


<div class="modal-backdrop" id="createModal" onclick="closeModalOutside(event)">
    <div class="modal">
        <h2><i class="fa-solid fa-plus-circle" style="color:var(--accent)"></i> สร้างห้องใหม่</h2>
        <form method="POST" action="<?php echo e(route('admin.rooms.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label" for="school_name">
                    <i class="fa-solid fa-school"></i> ชื่อโรงเรียน
                </label>
                <input type="text" id="school_name" name="school_name" class="form-input"
                       placeholder="เช่น โรงเรียนสวนกุหลาบวิทยาลัย" required maxlength="120" />
            </div>
            <div class="form-group">
                <label class="form-label" for="room_name">
                    <i class="fa-solid fa-chalkboard-user"></i> ชื่อห้อง / ชั้นเรียน
                </label>
                <input type="text" id="room_name" name="name" class="form-input"
                       placeholder="เช่น ม.2/3, ห้อง A" required maxlength="80" />
            </div>
            <div class="form-group">
                <label class="form-label" for="expires_at">
                    <i class="fa-solid fa-calendar-xmark"></i> วันหมดอายุ <span style="font-weight:400">(เว้นว่างถ้าไม่กำหนด)</span>
                </label>
                <input type="datetime-local" id="expires_at" name="expires_at" class="form-input" />
            </div>
            <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:10px; padding:12px; font-size:13px; color:#0369a1; margin-top:4px;">
                <i class="fa-solid fa-circle-info"></i>
                ระบบจะสร้าง <strong>Room Code 6 หลัก</strong> ให้อัตโนมัติ นำไปแจกให้นักเรียนใช้เข้าระบบ
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal()">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> สร้างห้อง
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openModal()  { document.getElementById('createModal').classList.add('open'); }
function closeModal() { document.getElementById('createModal').classList.remove('open'); }
function closeModalOutside(e) {
    if (e.target === document.getElementById('createModal')) closeModal();
}

function copyCode(code, btn) {
    navigator.clipboard.writeText(code).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check" style="color:var(--good)"></i>';
        setTimeout(() => { btn.innerHTML = orig; }, 1500);
    });
}

// Auto-open modal on validation error
<?php if($errors->any()): ?>
    openModal();
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/fnwin/Documents/Code/laravel-python-game/resources/views/admin/rooms/index.blade.php ENDPATH**/ ?>