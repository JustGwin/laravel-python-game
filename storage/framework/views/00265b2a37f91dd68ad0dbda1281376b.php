<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>
    <title><?php echo $__env->yieldContent('title', 'Python Game'); ?> | Python Beginner Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;600;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --panel: #ffffff;
            --border: #e2e8f0;
            --ink: #1e293b;
            --muted: #64748b;
            --accent: #3b82f6;
            --accent2: #60a5fa;
            --good: #10b981;
            --bad: #ef4444;
            --warn: #f59e0b;
            --radius: 16px;
            --shadow: 0 8px 24px rgba(149, 157, 165, 0.15);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Noto Sans Thai', 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ─── Navbar ───────────────────────────────────────────────────── */
        .navbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
            height: 60px;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            font-weight: 700; font-size: 18px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
        .nav-brand svg { flex-shrink: 0; }
        .nav-right { display: flex; align-items: center; gap: 12px; }
        .nav-user {
            font-size: 13px; color: var(--muted);
            padding: 6px 14px; border: 1px solid var(--border);
            border-radius: 999px; background: var(--surface);
        }
        .nav-user strong { color: var(--ink); }
        .badge-role {
            font-size: 11px; font-weight: 700; padding: 3px 10px;
            border-radius: 999px; text-transform: uppercase; letter-spacing: .5px;
        }
        .badge-role.admin  { background: linear-gradient(135deg,#f59e0b,#f97316); color: #ffffff; }
        .badge-role.player { background: linear-gradient(135deg,var(--accent),var(--accent2)); color: #ffffff; }

        .btn-logout {
            padding: 7px 16px; border-radius: 10px;
            background: transparent; border: 1px solid var(--border);
            color: var(--muted); font-size: 13px; cursor: pointer;
            transition: .2s;
        }
        .btn-logout:hover { background: var(--bad); color: #fff; border-color: var(--bad); }

        /* ─── Main ─────────────────────────────────────────────────────── */
        .main-wrap { max-width: 1280px; margin: 0 auto; padding: 28px 20px; }

        /* ─── Alert Flash ──────────────────────────────────────────────── */
        .flash {
            padding: 12px 18px; border-radius: 10px; margin-bottom: 18px;
            font-size: 14px; display: flex; align-items: center; gap: 10px;
        }
        .flash.success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; }
        .flash.error   { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; }

        /* ─── Cards ────────────────────────────────────────────────────── */
        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 12px 32px rgba(149, 157, 165, 0.2);
            transform: translateY(-2px);
        }

        /* ─── Buttons ──────────────────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px; border-radius: 10px;
            font-weight: 600; font-size: 14px; cursor: pointer;
            border: 1px solid transparent; transition: .2s transform, .2s opacity;
            text-decoration: none;
        }
        .btn:hover { transform: translateY(-1px); opacity: .9; }
        .btn:active { transform: translateY(0); }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #ffffff;
        }
        .btn-success { background: linear-gradient(135deg,var(--good),#34d399); color: #ffffff; }
        .btn-danger  { background: linear-gradient(135deg,var(--bad),#f87171); color: #ffffff; }
        .btn-ghost   { background: transparent; border-color: var(--border); color: var(--muted); }
        .btn-ghost:hover { color: var(--ink); border-color: var(--muted); }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* ─── Table ────────────────────────────────────────────────────── */
        .table-wrap { overflow-x: auto; border-radius: var(--radius); }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead th {
            padding: 12px 16px; text-align: left;
            background: #f8fafc; color: var(--muted);
            font-size: 12px; text-transform: uppercase; letter-spacing: .5px;
            border-bottom: 1px solid var(--border);
        }
        tbody tr { transition: background .15s; }
        tbody tr:hover { background: rgba(59,130,246,.04); }
        tbody td { padding: 12px 16px; border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }

        /* ─── Chips ────────────────────────────────────────────────────── */
        .chip {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 12px; font-weight: 600; padding: 3px 10px;
            border-radius: 999px;
        }
        .chip-green  { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .chip-blue   { background: #dbeafe; color: #1e3a8a; border: 1px solid #bfdbfe; }
        .chip-yellow { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .chip-red    { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .chip-muted  { background: var(--surface); color: var(--muted); border: 1px solid var(--border); }

        /* ─── Form ─────────────────────────────────────────────────────── */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; margin-bottom: 6px; font-size: 13px; color: var(--muted); font-weight: 600; }
        .form-input {
            width: 100%; padding: 11px 14px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 10px; color: var(--ink); font-size: 15px;
            transition: border-color .2s;
            font-family: inherit;
        }
        .form-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(108,143,255,.15); }
        .form-error { color: var(--bad); font-size: 12px; margin-top: 4px; }

        /* ─── Stat Cards ───────────────────────────────────────────────── */
        .stats-grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); }
        .stat-card {
            background: var(--panel); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 20px 18px;
            position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .stat-card.accent::before  { background: linear-gradient(90deg,var(--accent),var(--accent2)); }
        .stat-card.green::before   { background: linear-gradient(90deg,#3ddc84,#36c46e); }
        .stat-card.yellow::before  { background: linear-gradient(90deg,#ffc95d,#ff9d3d); }
        .stat-card.red::before     { background: linear-gradient(90deg,#ff8fa3,#ff5d6c); }
        .stat-card.purple::before  { background: linear-gradient(90deg,#c77dff,#a07cff); }
        .stat-value { font-size: 28px; font-weight: 700; margin-bottom: 4px; }
        .stat-label { font-size: 12px; color: var(--muted); }

        /* ─── Pagination ───────────────────────────────────────────────── */
        .pagination { display: flex; gap: 8px; justify-content: center; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 7px 13px; border-radius: 8px; font-size: 13px;
            border: 1px solid var(--border); color: var(--muted);
            text-decoration: none; transition: .2s;
        }
        .pagination a:hover { background: var(--surface); color: var(--ink); }
        .pagination span.active { background: var(--accent); color: #ffffff; border-color: var(--accent); font-weight: 700; }

        @media (max-width: 640px) {
            .navbar { padding: 0 14px; }
            .main-wrap { padding: 16px 12px; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<!-- ─── Navbar ─────────────────────────────────────────────────────────── -->
<nav class="navbar">
    <a class="nav-brand" href="<?php echo e(auth()->user()?->isAdmin() ? route('admin.dashboard') : route('game.index')); ?>">
        <i class="fa-brands fa-python" style="font-size:24px; color:var(--accent);"></i>
        Python Game
    </a>
    <div class="nav-right">
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-ghost btn-sm"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                <a href="<?php echo e(route('admin.rooms.index')); ?>" class="btn btn-ghost btn-sm"><i class="fa-solid fa-door-open"></i> ห้องเรียน</a>
            <?php else: ?>
                <a href="<?php echo e(route('game.index')); ?>" class="btn btn-ghost btn-sm"><i class="fa-solid fa-gamepad"></i> เกม</a>
                <a href="<?php echo e(route('game.history')); ?>" class="btn btn-ghost btn-sm"><i class="fa-solid fa-clock-rotate-left"></i> ประวัติ</a>
            <?php endif; ?>
            <span class="nav-user">
                สวัสดี <strong><?php echo e(auth()->user()->name); ?></strong>
            </span>
            <span class="badge-role <?php echo e(auth()->user()->role); ?>"><i class="fa-solid fa-user-shield"></i> <?php echo e(auth()->user()->role); ?></span>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> ออกจากระบบ</button>
            </form>
        <?php endif; ?>
    </div>
</nav>

<!-- ─── Main Content ───────────────────────────────────────────────────── -->
<div class="main-wrap">
    <?php if(session('success')): ?>
        <div class="flash success"><i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="flash error"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/fnwin/Documents/Code/laravel-python-game/resources/views/layouts/app.blade.php ENDPATH**/ ?>