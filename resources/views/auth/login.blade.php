<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login – BrewSpot</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;display:flex;background:#0d1b3e;color:#0f172a}
    .left{flex:1.1;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between;padding:52px 56px;background:radial-gradient(circle at 85% 20%,rgba(59,130,246,.22),transparent 45%),radial-gradient(circle at 15% 85%,rgba(26,86,219,.28),transparent 50%),linear-gradient(155deg,#091430 0%,#0d1b3e 55%,#162b5c 100%)}
    .grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:44px 44px;pointer-events:none}
    .left-top,.left-main,.left-foot{position:relative;z-index:1}
    .logo{display:flex;align-items:center;gap:12px}
    .logo-icon{width:44px;height:44px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.16);backdrop-filter:blur(8px);border-radius:12px;display:grid;place-items:center}
    .logo-text h1{font-size:1.25rem;font-weight:800;color:#fff;letter-spacing:-.01em}
    .logo-text span{font-size:.72rem;color:rgba(255,255,255,.55);display:block;letter-spacing:.04em}
    .pill-tag{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;background:rgba(59,130,246,.16);border:1px solid rgba(96,165,250,.3);color:#bfdbfe;font-size:.74rem;font-weight:600;letter-spacing:.02em;margin-bottom:22px}
    .pill-dot{width:6px;height:6px;border-radius:50%;background:#60a5fa}
    .left-main h2{font-size:2.85rem;font-weight:800;color:#fff;line-height:1.12;letter-spacing:-.03em;max-width:480px;margin-bottom:18px}
    .left-main h2 span{color:#60a5fa}
    .left-main p{font-size:.96rem;color:rgba(255,255,255,.68);max-width:410px;line-height:1.65}
    .left-foot{display:flex;align-items:center;gap:20px;font-size:.78rem;color:rgba(255,255,255,.45)}
    .left-foot span{display:inline-flex;align-items:center;gap:8px}
    .left-foot span::before{content:'';width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.35)}
    .left-foot span:first-child::before{display:none}
    /* RIGHT */
    .right{width:440px;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:56px 46px;min-height:100vh}
    .right h3{font-size:1.6rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;margin-bottom:6px}
    .right .sub{font-size:.875rem;color:#64748b;margin-bottom:32px}
    .form-group{margin-bottom:18px}
    label{display:block;font-size:.8rem;font-weight:600;color:#334155;margin-bottom:7px}
    .input-wrap{position:relative}
    .input-wrap input{width:100%;padding:12px 14px 12px 40px;border:1.5px solid #e2e8f0;border-radius:11px;font-size:.875rem;font-family:inherit;transition:.15s;color:#0f172a;background:#f8fafc}
    .input-wrap input:focus{outline:none;border-color:#1a56db;background:#fff;box-shadow:0 0 0 3px rgba(26,86,219,.1)}
    .input-wrap .i-icon{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;width:16px;height:16px}
    .eye-btn{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;display:grid;place-items:center}
    .eye-btn:hover{color:#334155}
    .remember-row{display:flex;align-items:center;justify-content:space-between;margin:16px 0 24px;font-size:.82rem;color:#64748b}
    .remember-row label{font-weight:500;display:flex;align-items:center;gap:7px;cursor:pointer;margin:0}
    .btn-login{width:100%;padding:13px;background:#0d1b3e;color:#fff;border:none;border-radius:11px;font-size:.95rem;font-weight:700;cursor:pointer;transition:.2s;font-family:inherit}
    .btn-login:hover{background:#1a56db;box-shadow:0 8px 20px rgba(26,86,219,.25)}
    .err-box{background:#fee2e2;border:1px solid #fca5a5;border-radius:9px;padding:11px 14px;font-size:.8rem;color:#991b1b;margin-bottom:18px}
    @media(max-width:860px){.left{display:none}.right{width:100%;padding:36px 24px}}
    </style>
</head>
<body>
<div class="left">
    <div class="grid-bg"></div>
    <div class="left-top">
        <div class="logo">
            <div class="logo-icon">
                <svg width="26" height="26" viewBox="0 0 36 36" fill="none"><path d="M18 3C11.925 3 7 7.925 7 14C7 21.2 18 33 18 33C18 33 29 21.2 29 14C29 7.925 24.075 3 18 3Z" fill="#ffffff"/><ellipse cx="18" cy="14" rx="5.2" ry="6.5" transform="rotate(-18 18 14)" fill="#1a56db"/><path d="M16.2 8.2C18.2 11.5 17.8 16.5 19.8 19.8" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round"/></svg>
            </div>
            <div class="logo-text"><h1>BrewSpot</h1><span>coffee | on the move</span></div>
        </div>
    </div>

    <div class="left-main">
        <div class="pill-tag"><span class="pill-dot"></span> Coffee on the move</div>
        <h2>Stay caffeinated, <span>keep moving.</span></h2>
        <p>Ngopi sat-set, rute anti ribet. Pantau titik mangkal, driver, dan cuan harian dalam satu dashboard simpel.</p>
    </div>

    <div class="left-foot">
        <span>Smart Route</span>
        <span>Live Sales</span>
        <span>Zero Ribet</span>
    </div>
</div>

<div class="right">
    <h3>Welcome Back</h3>
    <p class="sub">Masuk ke akun BrewSpot kamu untuk lanjut.</p>

    @if($errors->any())
    <div class="err-box">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <div class="input-wrap">
                <svg class="i-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <div class="input-wrap">
                <svg class="i-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" id="passwordInput" name="password" placeholder="Masukkan password" style="padding-right:38px" required>
                <button type="button" class="eye-btn" onclick="const p=document.getElementById('passwordInput');p.type=p.type==='password'?'text':'password'" title="Tampilkan/Sembunyikan Password">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>
        <div class="remember-row">
            <label><input type="checkbox" name="remember"> Ingat saya</label>
        </div>
        <button type="submit" class="btn-login">Masuk</button>
    </form>
</div>
</body>
</html>
