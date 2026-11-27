<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login – BrewSpot</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;display:flex;background:#0d1b3e}
    .left{flex:1;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:center;padding:52px}
    .left-bg{position:absolute;inset:0;background:linear-gradient(160deg,#0d1b3e 0%,#152150 40%,#1e3a6e 100%)}
    .left-overlay{position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><circle cx="200" cy="200" r="180" fill="none" stroke="%23ffffff" stroke-width="0.3" opacity="0.1"/><circle cx="200" cy="200" r="120" fill="none" stroke="%23ffffff" stroke-width="0.3" opacity="0.08"/><circle cx="200" cy="200" r="60" fill="none" stroke="%23ffffff" stroke-width="0.3" opacity="0.05"/></svg>') center/cover}
    .left-content{position:relative;z-index:1}
    .left .logo{display:flex;align-items:center;gap:12px;margin-bottom:52px}
    .left .logo-icon{width:48px;height:48px;background:#1a56db;border-radius:14px;display:grid;place-items:center}
    .left .logo-text h1{font-size:1.4rem;font-weight:800;color:#fff}
    .left .logo-text span{font-size:.75rem;color:rgba(255,255,255,.45);display:block;margin-top:1px}
    .left h2{font-size:2.4rem;font-weight:800;color:#fff;line-height:1.2;margin-bottom:14px}
    .left p{font-size:.9rem;color:rgba(255,255,255,.65);max-width:380px;line-height:1.65;margin-bottom:40px}
    .features{display:flex;flex-direction:column;gap:14px}
    .feature{display:flex;align-items:center;gap:12px;color:rgba(255,255,255,.8);font-size:.85rem}
    .feat-icon{width:36px;height:36px;background:rgba(255,255,255,.1);border-radius:9px;display:grid;place-items:center;flex-shrink:0}
    /* RIGHT */
    .right{width:420px;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:52px 44px;min-height:100vh}
    .right h3{font-size:1.5rem;font-weight:800;color:#0f172a;margin-bottom:4px}
    .right .sub{font-size:.85rem;color:#64748b;margin-bottom:32px}
    .form-group{margin-bottom:16px}
    label{display:block;font-size:.8rem;font-weight:600;color:#334155;margin-bottom:6px}
    .input-wrap{position:relative}
    .input-wrap input{width:100%;padding:11px 14px 11px 40px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.875rem;font-family:inherit;transition:.15s;color:#0f172a}
    .input-wrap input:focus{outline:none;border-color:#1a56db;box-shadow:0 0 0 3px rgba(26,86,219,.1)}
    .input-wrap .i-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;width:16px;height:16px}
    .remember-row{display:flex;align-items:center;justify-content:space-between;margin:16px 0;font-size:.8rem;color:#64748b}
    .remember-row label{font-weight:400;display:flex;align-items:center;gap:6px;cursor:pointer;margin:0}
    .btn-login{width:100%;padding:13px;background:#0d1b3e;color:#fff;border:none;border-radius:10px;font-size:.95rem;font-weight:700;cursor:pointer;transition:.2s;font-family:inherit}
    .btn-login:hover{background:#152150;box-shadow:0 6px 20px rgba(13,27,62,.3)}
    .or-row{display:flex;align-items:center;gap:12px;margin:18px 0;font-size:.78rem;color:#94a3b8}
    .or-row::before,.or-row::after{content:'';flex:1;height:1px;background:#e2e8f0}
    .err-box{background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:11px 14px;font-size:.8rem;color:#991b1b;margin-bottom:16px}
    .demo-box{margin-top:28px;padding:14px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;font-size:.78rem}
    .demo-box strong{display:block;font-size:.8rem;font-weight:700;color:#0d1b3e;margin-bottom:8px}
    .demo-row{display:flex;justify-content:space-between;padding:4px 0;color:#64748b;border-bottom:1px solid #f1f5f9}
    .demo-row:last-child{border:none}
    @media(max-width:800px){.left{display:none}.right{width:100%;padding:36px 24px}}
    </style>
</head>
<body>
<div class="left">
    <div class="left-bg"></div><div class="left-overlay"></div>
    <div class="left-content">
        <div class="logo">
            <div class="logo-icon">
                <svg width="30" height="30" viewBox="0 0 36 36" fill="none"><path d="M18 3C11.925 3 7 7.925 7 14C7 21.2 18 33 18 33C18 33 29 21.2 29 14C29 7.925 24.075 3 18 3Z" fill="#ffffff"/><ellipse cx="18" cy="14" rx="5.2" ry="6.5" transform="rotate(-18 18 14)" fill="#1a56db"/><path d="M16.2 8.2C18.2 11.5 17.8 16.5 19.8 19.8" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round"/></svg>
            </div>
            <div class="logo-text"><h1>BrewSpot</h1><span>coffee | on the move</span></div>
        </div>
        <h2>Welcome Back!</h2>
        <p>Login to your account to continue your coffee journey.</p>
        <div class="features">
            <div class="feature"><div class="feat-icon">📍</div>Manajemen Titik Mangkal & Rute</div>
            <div class="feature"><div class="feat-icon">📊</div>Analisis Penjualan Per Lokasi & Jam</div>
            <div class="feature"><div class="feat-icon">🚴</div>Monitoring Driver Real-time</div>
            <div class="feature"><div class="feat-icon">💰</div>Pencatatan Transaksi Terintegrasi</div>
        </div>
    </div>
</div>
<div class="right">
    <h3>Welcome Back!</h3>
    <p class="sub">Login to your account to continue your coffee journey.</p>

    @if($errors->any())
    <div class="err-box">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label>Email address</label>
            <div class="input-wrap">
                <svg class="i-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <div class="input-wrap">
                <svg class="i-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" id="passwordInput" name="password" placeholder="Password" style="padding-right:38px" required>
                <button type="button" onclick="const p=document.getElementById('passwordInput');p.type=p.type==='password'?'text':'password'" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px" title="Tampilkan/Sembunyikan Password">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>
        <div class="remember-row">
            <label><input type="checkbox" name="remember"> Ingat saya</label>
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="demo-box">
        <strong>🔑 Akun Demo</strong>
        <div class="demo-row"><span>Pemilik Usaha:</span><span>owner@brewspot.id / password</span></div>
        <div class="demo-row"><span>Driver:</span><span>budi@brewspot.id / password</span></div>
    </div>
</div>
</body>
</html>
