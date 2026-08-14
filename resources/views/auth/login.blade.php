<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>เข้าสู่ระบบ | Python Beginner Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;600;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
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
            --warn: #f59e0b;
            --bad: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Noto Sans Thai','Inter',system-ui,sans-serif;
            background: var(--bg); color: var(--ink); min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }

        .login-container {
            width: 100%; max-width: 420px; padding: 16px;
        }

        /* Brand */
        .brand {
            text-align: center; margin-bottom: 32px;
        }
        .brand-icon {
            width: 64px; height: 64px; border-radius: 18px; margin: 0 auto 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 12px 32px rgba(59,130,246,.15);
        }
        .brand-icon svg { width: 36px; height: 36px; }
        .brand h1 {
            font-size: 26px; font-weight: 700;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .brand p { font-size: 14px; color: var(--muted); margin-top: 6px; }

         /* Card */
        .login-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 8px 32px rgba(0,0,0,.08);
        }
        .login-card h2 { font-size: 20px; font-weight: 700; margin-bottom: 24px; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block; margin-bottom: 7px;
            font-size: 13px; color: var(--muted); font-weight: 600;
        }
        .form-input {
            width: 100%; padding: 12px 14px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; color: var(--ink); font-size: 15px;
            transition: border-color .2s;
            font-family: inherit;
        }
        .form-input:focus {
            outline: none; border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(108,143,255,.15);
        }
        .form-error { color: var(--bad); font-size: 12px; margin-top: 5px; }

        /* ─── Remember ── */
        .remember-row {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: var(--muted); margin-bottom: 22px;
            cursor: pointer;
        }
        .remember-row input[type=checkbox] { width: 16px; height: 16px; cursor: pointer; }

        /* ─── Submit ──── */
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #ffffff; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: .2s transform, .2s opacity; font-family: inherit;
        }
        .btn-submit:hover { transform: translateY(-1px); opacity: .92; }
        .btn-submit:active { transform: translateY(0); }

        /* ─── Error Banner ────────────────────────────────────────────── */
        .error-banner {
            background: #fee2e2; border: 1px solid #fecaca;
            color: #991b1b; border-radius: 10px;
            padding: 10px 14px; margin-bottom: 18px; font-size: 13px;
        }

        /* ─── Demo Accounts ───────────────────────────────────────────── */
        .demo-section {
            margin-top: 24px; padding-top: 20px;
            border-top: 1px solid var(--border);
        }
        .demo-title { font-size: 12px; color: var(--muted); margin-bottom: 12px; font-weight: 600; letter-spacing: .5px; }
        .demo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .demo-btn {
            padding: 9px 12px; border-radius: 10px;
            background: var(--surface); border: 1px solid var(--border);
            color: var(--muted); font-size: 12px; cursor: pointer;
            transition: .2s; text-align: center; font-family: inherit;
        }
        .demo-btn:hover { background: var(--border); color: var(--ink); }
        .demo-btn .demo-role {
            display: block; font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px;
        }
        .demo-btn.admin-btn .demo-role { color: #f59e0b; }
        .demo-btn.player-btn .demo-role { color: var(--accent); }

        /* Tabs */
        .tabs { display: flex; margin-bottom: 24px; border-bottom: 1px solid var(--border); }
        .tab-btn {
            flex: 1; padding: 12px; background: none; border: none; color: var(--muted);
            font-size: 14px; font-weight: 600; cursor: pointer; border-bottom: 2px solid transparent;
            transition: .2s; font-family: inherit;
        }
        .tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); }
        .tab-btn:hover:not(.active) { color: var(--ink); }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Brand -->
    <div class="brand">
        <div class="brand-icon">
            <i class="fa-brands fa-python" style="font-size:36px; color:#ffffff"></i>
        </div>
        <h1>Python Beginner Game</h1>
        <p>ระบบบันทึกคะแนนและจัดการผู้เล่น</p>
    </div>

    <!-- Login Card -->
    <div class="login-card">
        
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('quick')"><i class="fa-solid fa-bolt"></i> เริ่มเล่นทันที</button>
            <button class="tab-btn" onclick="switchTab('admin')"><i class="fa-solid fa-user-shield"></i> ผู้ดูแลระบบ</button>
        </div>

        @if($errors->any())
            <div class="error-banner">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- QUICK PLAY TAB -->
        <div id="tab-quick" class="tab-content active">
            <form method="POST" action="{{ route('quick.play') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name"><i class="fa-solid fa-user" style="color:var(--muted)"></i> ชื่อของคุณ</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-input"
                        placeholder="กรอกชื่อเพื่อเริ่มเล่น..."
                        value="{{ old('name') }}"
                        required
                        autofocus
                    />
                </div>
                <div class="form-group">
                    <label class="form-label" for="room_code">
                        <i class="fa-solid fa-door-open" style="color:var(--muted)"></i> รหัสห้องเรียน
                    </label>
                    <input
                        id="room_code"
                        type="text"
                        name="room_code"
                        class="form-input"
                        placeholder="กรอกรหัสห้องเรียน "
                        value="{{ old('room_code') }}"
                        maxlength="6"
                        required
                        style="text-transform:uppercase; letter-spacing:3px; font-weight:700; font-size:18px;"
                        oninput="this.value=this.value.toUpperCase()"
                    />
                    @error('room_code')
                        <div style="color:var(--bad); font-size:12px; margin-top:6px;">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                    <div style="font-size:12px; color:var(--muted); margin-top:6px;">
                        <i class="fa-solid fa-circle-info"></i>
                        รับรหัสห้องจากครูผู้สอนของคุณ
                    </div>
                </div>
                <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, var(--good), #34d399); color: #ffffff;">
                    <i class="fa-solid fa-play"></i> เริ่มเล่นเกม
                </button>
            </form>
        </div>

        <!-- ADMIN/EXISTING LOGIN TAB -->
        <div id="tab-admin" class="tab-content">
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email"><i class="fa-solid fa-envelope" style="color:var(--muted)"></i> อีเมล</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-input"
                        value="{{ old('email') }}"
                        placeholder="your@email.com"
                        required
                    />
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password"><i class="fa-solid fa-lock" style="color:var(--muted)"></i> รหัสผ่าน</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-input"
                        placeholder="••••••••"
                        required
                    />
                </div>

                <label class="remember-row">
                    <input type="checkbox" name="remember" id="remember"/>
                    จดจำการเข้าสู่ระบบ
                </label>

                <button type="submit" class="btn-submit" id="loginBtn" style="color:#ffffff">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> เข้าสู่ระบบ
                </button>
            </form>

        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    
    if (tab === 'quick') {
        document.querySelectorAll('.tab-btn')[0].classList.add('active');
        document.getElementById('tab-quick').classList.add('active');
    } else {
        document.querySelectorAll('.tab-btn')[1].classList.add('active');
        document.getElementById('tab-admin').classList.add('active');
    }
}

function fillLogin(email, pass) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = pass;
    document.getElementById('loginForm').submit();
}

@if($errors->any())
    switchTab('admin');
@endif
</script>
</body>
</html>
