<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','BrewSpot') – BrewSpot</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
    /* ===================== DESIGN TOKENS ===================== */
    :root {
        --navy:       #0d1b3e;
        --navy-mid:   #152150;
        --navy-light: #1e3a6e;
        --blue:       #1a56db;
        --blue-mid:   #3b82f6;
        --blue-soft:  #dbeafe;
        --accent:     #f59e0b;
        --accent-soft:#fef3c7;
        --success:    #10b981;
        --danger:     #ef4444;
        --warning:    #f59e0b;
        --white:      #ffffff;
        --gray-50:    #f8fafc;
        --gray-100:   #f1f5f9;
        --gray-200:   #e2e8f0;
        --gray-400:   #94a3b8;
        --gray-500:   #64748b;
        --gray-700:   #334155;
        --gray-900:   #0f172a;
        --sidebar-w:  240px;
        --topbar-h:   64px;
        --radius:     12px;
        --radius-lg:  16px;
        --shadow:     0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
        --shadow-md:  0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
        --shadow-lg:  0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
    }
    /* ===================== RESET ===================== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--gray-50); color: var(--gray-900); min-height: 100vh; display: flex; font-size: 14px; line-height: 1.5; }
    a { text-decoration: none; color: inherit; }
    button { font-family: inherit; cursor: pointer; }
    img { max-width: 100%; }

    /* ===================== SIDEBAR ===================== */
    .sidebar {
        width: var(--sidebar-w); background: var(--navy);
        min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 200;
        display: flex; flex-direction: column; overflow: hidden;
    }
    /* Logo */
    .sidebar-brand {
        padding: 20px 20px 16px; display: flex; align-items: center; gap: 10px;
        border-bottom: 1px solid rgba(255,255,255,.07);
    }
    .brand-logo {
        width: 36px; height: 36px; flex-shrink: 0;
    }
    .brand-text h2 { font-size: .95rem; font-weight: 800; color: #fff; letter-spacing: .01em; line-height: 1.1; }
    .brand-text span { font-size: .65rem; color: rgba(255,255,255,.45); font-weight: 400; letter-spacing: .08em; }

    /* Nav */
    .sidebar-nav { flex: 1; padding: 10px 0; overflow-y: auto; }
    .sidebar-nav::-webkit-scrollbar { width: 0; }
    .nav-label {
        padding: 14px 20px 5px; font-size: .6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3);
    }
    .nav-link {
        display: flex; align-items: center; gap: 10px; padding: 9px 20px;
        color: rgba(255,255,255,.55); font-size: .825rem; font-weight: 500;
        transition: all .15s; position: relative; border-radius: 0;
    }
    .nav-link:hover { color: rgba(255,255,255,.9); background: rgba(255,255,255,.06); }
    .nav-link.active {
        color: #fff; background: rgba(255,255,255,.1);
        border-right: 3px solid var(--accent);
    }
    .nav-link svg { width: 16px; height: 16px; flex-shrink: 0; opacity: .8; }
    .nav-link.active svg { opacity: 1; }
    .nav-badge {
        margin-left: auto; background: var(--accent); color: #fff;
        font-size: .6rem; font-weight: 700; padding: 1px 6px; border-radius: 999px;
    }

    /* Sidebar footer */
    .sidebar-footer {
        padding: 12px 16px 16px; border-top: 1px solid rgba(255,255,255,.07);
    }
    .sidebar-user {
        display: flex; align-items: center; gap: 10px; padding: 8px;
        border-radius: var(--radius); margin-bottom: 8px;
        background: rgba(255,255,255,.05);
    }
    .user-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--blue); display: grid; place-items: center;
        font-size: .78rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .user-info { flex: 1; min-width: 0; }
    .user-name { font-size: .8rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-role { font-size: .65rem; color: rgba(255,255,255,.4); }
    .btn-logout {
        display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px 10px;
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);
        border-radius: 8px; color: rgba(255,255,255,.6); font-size: .78rem; font-weight: 500;
        transition: .15s;
    }
    .btn-logout:hover { background: rgba(239,68,68,.2); border-color: rgba(239,68,68,.3); color: #fff; }

    /* ===================== MAIN ===================== */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    /* ===================== TOPBAR ===================== */
    .topbar {
        height: var(--topbar-h); background: #fff; border-bottom: 1px solid var(--gray-200);
        display: flex; align-items: center; padding: 0 28px; gap: 16px;
        position: sticky; top: 0; z-index: 100; box-shadow: var(--shadow);
    }
    .topbar-left { flex: 1; }
    .topbar-title { font-size: 1.05rem; font-weight: 800; color: var(--gray-900); }
    .topbar-sub { font-size: .75rem; color: var(--gray-500); margin-top: 1px; }
    .topbar-right { display: flex; align-items: center; gap: 10px; }
    .topbar-btn {
        width: 36px; height: 36px; border-radius: 50%; background: var(--gray-100);
        border: none; display: grid; place-items: center; color: var(--gray-500);
        transition: .15s; cursor: pointer; position: relative;
    }
    .topbar-btn:hover { background: var(--gray-200); color: var(--gray-700); }
    .notif-dot {
        position: absolute; top: 6px; right: 6px; width: 7px; height: 7px;
        background: var(--danger); border-radius: 50%; border: 1.5px solid #fff;
    }
    .topbar-user {
        display: flex; align-items: center; gap: 8px; padding: 5px 10px;
        background: var(--gray-100); border-radius: 999px; cursor: pointer;
    }
    .topbar-user .avatar { width: 28px; height: 28px; border-radius: 50%; background: var(--navy); display: grid; place-items: center; font-size: .7rem; font-weight: 700; color: #fff; }
    .topbar-user .uname { font-size: .8rem; font-weight: 600; color: var(--gray-700); }
    .role-badge {
        font-size: .65rem; font-weight: 700; padding: 2px 8px; border-radius: 999px;
        background: var(--navy); color: #fff;
    }
    .role-badge.driver { background: var(--blue); }

    /* ===================== CONTENT ===================== */
    .content { padding: 24px 28px; flex: 1; }

    /* ===================== PAGE HEADER ===================== */
    .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 22px; gap: 12px; flex-wrap: wrap; }
    .page-header h1 { font-size: 1.3rem; font-weight: 800; color: var(--gray-900); line-height: 1.2; }
    .page-header p { font-size: .8rem; color: var(--gray-500); margin-top: 3px; }

    /* ===================== STAT CARDS ===================== */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 16px; margin-bottom: 20px; }
    .stat-card {
        background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);
        padding: 18px 20px; box-shadow: var(--shadow); position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute; right: -16px; top: -16px;
        width: 64px; height: 64px; border-radius: 50%;
    }
    .stat-card.blue::before { background: rgba(26,86,219,.08); }
    .stat-card.green::before { background: rgba(16,185,129,.08); }
    .stat-card.orange::before { background: rgba(245,158,11,.08); }
    .stat-card.purple::before { background: rgba(139,92,246,.08); }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: grid; place-items: center; margin-bottom: 12px;
    }
    .stat-card.blue .stat-icon { background: var(--blue-soft); color: var(--blue); }
    .stat-card.green .stat-icon { background: #d1fae5; color: #065f46; }
    .stat-card.orange .stat-icon { background: var(--accent-soft); color: #92400e; }
    .stat-card.purple .stat-icon { background: #ede9fe; color: #5b21b6; }
    .stat-value { font-size: 1.45rem; font-weight: 800; color: var(--gray-900); line-height: 1; }
    .stat-label { font-size: .72rem; color: var(--gray-500); font-weight: 500; margin-top: 4px; }
    .stat-trend { font-size: .72rem; font-weight: 600; margin-top: 6px; display: flex; align-items: center; gap: 3px; }
    .stat-trend.up { color: var(--success); }
    .stat-trend.down { color: var(--danger); }

    /* ===================== CARDS ===================== */
    .card { background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow); overflow: hidden; }
    .card-header { padding: 16px 20px; border-bottom: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .card-header h3 { font-size: .9rem; font-weight: 700; color: var(--gray-900); }
    .card-body { padding: 20px; }

    /* ===================== GRID LAYOUTS ===================== */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; }
    .grid-col-7-5 { display: grid; grid-template-columns: 7fr 5fr; gap: 18px; }
    .grid-col-6-6 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    @media(max-width: 1100px) { .grid-2, .grid-3, .grid-col-7-5, .grid-col-6-6 { grid-template-columns: 1fr; } }

    /* ===================== BADGES ===================== */
    .badge { padding: 3px 10px; border-radius: 999px; font-size: .68rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
    .badge-green { background: #d1fae5; color: #065f46; }
    .badge-blue { background: var(--blue-soft); color: #1e40af; }
    .badge-red { background: #fee2e2; color: #991b1b; }
    .badge-orange { background: var(--accent-soft); color: #92400e; }
    .badge-gray { background: var(--gray-100); color: var(--gray-700); }
    .badge-navy { background: var(--navy); color: #fff; }

    /* ===================== BUTTONS ===================== */
    .btn { padding: 9px 18px; border-radius: 10px; font-size: .82rem; font-weight: 600; cursor: pointer; border: none; transition: all .15s; display: inline-flex; align-items: center; gap: 7px; white-space: nowrap; }
    .btn-primary { background: var(--navy); color: #fff; }
    .btn-primary:hover { background: var(--navy-mid); box-shadow: 0 4px 12px rgba(13,27,62,.3); transform: translateY(-1px); }
    .btn-blue { background: var(--blue); color: #fff; }
    .btn-blue:hover { background: #1d4ed8; transform: translateY(-1px); }
    .btn-success { background: var(--success); color: #fff; }
    .btn-success:hover { background: #059669; }
    .btn-danger { background: var(--danger); color: #fff; }
    .btn-danger:hover { background: #dc2626; }
    .btn-outline { background: #fff; color: var(--navy); border: 1.5px solid var(--gray-200); }
    .btn-outline:hover { background: var(--gray-50); border-color: var(--navy); }
    .btn-ghost { background: transparent; color: var(--gray-500); }
    .btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }
    .btn-sm { padding: 6px 12px; font-size: .75rem; border-radius: 8px; }
    .btn-xs { padding: 4px 9px; font-size: .7rem; border-radius: 6px; }

    /* ===================== FORMS ===================== */
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: .8rem; font-weight: 600; color: var(--gray-700); margin-bottom: 6px; }
    .form-control {
        width: 100%; padding: 10px 13px; border: 1.5px solid var(--gray-200);
        border-radius: 10px; font-size: .85rem; font-family: inherit; transition: .15s;
        background: #fff; color: var(--gray-900);
    }
    .form-control:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
    .form-control::placeholder { color: var(--gray-400); }
    select.form-control { cursor: pointer; }
    textarea.form-control { resize: vertical; min-height: 90px; }
    .form-hint { font-size: .72rem; color: var(--gray-400); margin-top: 4px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    /* ===================== ALERTS ===================== */
    .alert { padding: 12px 16px; border-radius: 10px; font-size: .82rem; margin-bottom: 18px; display: flex; align-items: flex-start; gap: 10px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .alert-warning { background: var(--accent-soft); color: #92400e; border: 1px solid #fcd34d; }
    .alert-info { background: var(--blue-soft); color: #1e40af; border: 1px solid #bfdbfe; }

    /* ===================== TABLE ===================== */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: .825rem; }
    thead th {
        background: var(--gray-50); padding: 10px 16px; text-align: left;
        font-size: .68rem; font-weight: 700; color: var(--gray-500);
        text-transform: uppercase; letter-spacing: .06em; white-space: nowrap;
        border-bottom: 1px solid var(--gray-200);
    }
    tbody td { padding: 12px 16px; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--gray-50); }

    /* ===================== PAGINATION ===================== */
    .pagination { display: flex; gap: 4px; align-items: center; justify-content: flex-end; margin-top: 16px; }
    .page-link { padding: 6px 11px; border-radius: 7px; font-size: .78rem; border: 1px solid var(--gray-200); color: var(--gray-700); transition: .15s; }
    .page-link:hover, .page-link.active { background: var(--navy); color: #fff; border-color: var(--navy); }

    /* ===================== RUTE STEPS ===================== */
    .rute-steps { display: flex; flex-direction: column; gap: 0; }
    .rute-step { display: flex; gap: 14px; position: relative; }
    .rute-step:not(:last-child)::before { content: ''; position: absolute; left: 15px; top: 36px; bottom: -4px; width: 2px; background: var(--gray-200); }
    .step-num { width: 32px; height: 32px; border-radius: 50%; background: var(--blue-soft); color: var(--blue); font-weight: 700; font-size: .78rem; display: grid; place-items: center; flex-shrink: 0; margin-top: 6px; border: 2px solid var(--blue-soft); }
    .step-num.done { background: var(--success); color: #fff; border-color: var(--success); }
    .step-num.active { background: var(--blue); color: #fff; border-color: var(--blue); }
    .step-body { flex: 1; padding: 8px 0 18px; }
    .step-body h4 { font-size: .875rem; font-weight: 700; color: var(--gray-900); }
    .step-body p { font-size: .78rem; color: var(--gray-500); margin-top: 2px; }

    /* ===================== SPECIAL: ACTIVE KUNJUNGAN ===================== */
    .kunjungan-hero {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        border-radius: var(--radius-lg); padding: 22px 24px; color: #fff;
        position: relative; overflow: hidden; margin-bottom: 20px;
    }
    .kunjungan-hero::after { content: '☕'; position: absolute; right: -8px; bottom: -16px; font-size: 6rem; opacity: .07; }
    .kunjungan-hero .kj-status { font-size: .68rem; font-weight: 700; letter-spacing: .1em; color: #4ade80; margin-bottom: 4px; }
    .kunjungan-hero h2 { font-size: 1.3rem; font-weight: 800; margin-bottom: 2px; }
    .kunjungan-hero p { font-size: .8rem; opacity: .75; }

    /* ===================== TIPS CARD ===================== */
    .tips-card {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        border-radius: var(--radius-lg); padding: 18px 20px; color: #fff;
        display: flex; align-items: center; gap: 16px; margin-top: 20px;
    }
    .tips-icon { font-size: 1.6rem; flex-shrink: 0; }
    .tips-body { flex: 1; }
    .tips-body h4 { font-size: .875rem; font-weight: 700; margin-bottom: 4px; }
    .tips-body p { font-size: .78rem; opacity: .8; line-height: 1.5; }

    /* ===================== EMPTY STATE ===================== */
    .empty-state { text-align: center; padding: 48px 20px; color: var(--gray-400); }
    .empty-state .ei { font-size: 2.8rem; margin-bottom: 10px; opacity: .5; }
    .empty-state p { font-size: .85rem; }

    /* ===================== LOCATION CARD (wireframe style) ===================== */
    .lokasi-list-item {
        display: flex; align-items: center; gap: 12px; padding: 12px;
        border: 1px solid var(--gray-200); border-radius: 10px; margin-bottom: 10px;
        background: #fff; transition: .15s;
    }
    .lokasi-list-item:hover { border-color: var(--blue); box-shadow: var(--shadow-md); }
    .lokasi-thumb {
        width: 52px; height: 52px; border-radius: 8px; background: var(--blue-soft);
        display: grid; place-items: center; font-size: 1.3rem; flex-shrink: 0;
    }
    .lokasi-info { flex: 1; }
    .lokasi-info strong { font-size: .875rem; font-weight: 700; display: block; }
    .lokasi-info span { font-size: .75rem; color: var(--gray-500); }

    /* ===================== INPUT GROUP ===================== */
    .input-group { position: relative; }
    .input-group .form-control { padding-left: 36px; }
    .input-group .ig-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: .9rem; }

    /* ===================== EXPORT / ACTION BAR ===================== */
    .action-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    /* ===================== SCROLLBAR ===================== */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 999px; }

    @media(max-width:768px) {
        .sidebar { transform: translateX(-100%); }
        .main { margin-left: 0; }
        .content { padding: 16px; }
    }
    .notif-dropdown {
        display: none; position: absolute; right: 0; top: 46px; width: 330px;
        background: #fff; border: 1px solid var(--gray-200); border-radius: 14px;
        box-shadow: var(--shadow-lg); z-index: 999; overflow: hidden;
    }
    .notif-dropdown.open { display: block; }
    </style>
    @stack('styles')
</head>
<body>
<!-- ========== SIDEBAR ========== -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <!-- SVG LOGO: White Map Pin + Coffee Bean (Wireframe) -->
        <svg class="brand-logo" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 3C11.925 3 7 7.925 7 14C7 21.2 18 33 18 33C18 33 29 21.2 29 14C29 7.925 24.075 3 18 3Z" fill="#ffffff"/>
            <ellipse cx="18" cy="14" rx="5.2" ry="6.5" transform="rotate(-18 18 14)" fill="#0d1b3e"/>
            <path d="M16.2 8.2C18.2 11.5 17.8 16.5 19.8 19.8" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round"/>
        </svg>
        <div class="brand-text">
            <h2>BrewSpot</h2>
            <span>coffee | on the move</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        @auth
        @if(auth()->user()->isOwner())
            <div class="nav-label">Main Menu</div>
            <a href="{{ route('owner.dashboard') }}" class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="{{ route('owner.menu.index') }}" class="nav-link {{ request()->routeIs('owner.menu.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/><path d="M12 6v6l4 2"/></svg>
                Menu
            </a>
            <a href="{{ route('owner.bahanbaku.index') }}" class="nav-link {{ request()->routeIs('owner.bahanbaku.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                Bahan Baku
            </a>
            <a href="{{ route('owner.lokasi.index') }}" class="nav-link {{ request()->routeIs('owner.lokasi.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Titik Mangkal
            </a>
            <a href="{{ route('owner.rute.index') }}" class="nav-link {{ request()->routeIs('owner.rute.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Rute & Jadwal
            </a>
            <a href="{{ route('owner.transaksi.index') }}" class="nav-link {{ request()->routeIs('owner.transaksi.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Transaksi
            </a>
            <a href="{{ route('owner.laporan.index') }}" class="nav-link {{ request()->routeIs('owner.laporan.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Laporan
            </a>
            <a href="{{ route('owner.rekap.index') }}" class="nav-link {{ request()->routeIs('owner.rekap.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Rekap Penjualan
            </a>
            <div class="nav-label">Lainnya</div>
            <a href="{{ route('owner.driver.index') }}" class="nav-link {{ request()->routeIs('owner.driver.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Driver
            </a>
            <a href="{{ route('owner.kunjungan.index') }}" class="nav-link {{ request()->routeIs('owner.kunjungan.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Kunjungan
            </a>
            <a href="{{ route('owner.pengaturan') }}" class="nav-link {{ request()->routeIs('owner.pengaturan') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Pengaturan
            </a>
        @else
            <div class="nav-label">Main Menu</div>
            <a href="{{ route('driver.dashboard') }}" class="nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="{{ route('driver.rute.index') }}" class="nav-link {{ request()->routeIs('driver.rute.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Rute Saya
            </a>
            <a href="{{ route('driver.kunjungan.aktif') }}" class="nav-link {{ request()->routeIs('driver.kunjungan.aktif') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Kunjungan Aktif
            </a>
            <a href="{{ route('driver.riwayat') }}" class="nav-link {{ request()->routeIs('driver.riwayat') || request()->routeIs('driver.kunjungan.show') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Riwayat Transaksi
            </a>
        @endif
        @endauth
    </nav>

    <div class="sidebar-footer">
        @auth
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Str::limit(auth()->user()->name, 16) }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </button>
        </form>
        @endauth
    </div>
</aside>

<!-- ========== MAIN ========== -->
<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">@yield('page-title', 'BrewSpot')</div>
            <div class="topbar-sub">@yield('page-sub', '')</div>
        </div>
        <div class="topbar-right">
            @auth
            @php
                $navNotifs = \App\Models\AppNotification::where('user_id', auth()->id())
                    ->orderByDesc('created_at')->limit(8)->get();
                $unreadNotifCount = \App\Models\AppNotification::where('user_id', auth()->id())
                    ->where('is_read', false)->count();
            @endphp
            <div style="position:relative" id="notifWrapper">
                <button type="button" class="topbar-btn" onclick="document.getElementById('notifDropdown').classList.toggle('open')" title="Notifikasi" style="position:relative">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    @if($unreadNotifCount > 0)
                    <span style="position:absolute;top:-4px;right:-4px;min-width:17px;height:17px;padding:0 4px;border-radius:999px;background:#ef4444;color:#fff;font-size:.62rem;font-weight:800;display:grid;place-items:center;border:2px solid #fff">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span>
                    @endif
                </button>
                <div id="notifDropdown" class="notif-dropdown">
                    <div style="padding:12px 16px;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between">
                        <strong style="font-size:.85rem;color:var(--navy)">Notifikasi @if($unreadNotifCount > 0)<span class="badge badge-blue" style="margin-left:4px">{{ $unreadNotifCount }} baru</span>@endif</strong>
                        @if($unreadNotifCount > 0)
                        <form method="POST" action="{{ route('notifications.read-all') }}">
                            @csrf
                            <button type="submit" style="background:none;border:none;color:var(--blue);font-size:.72rem;font-weight:600;cursor:pointer">Tandai dibaca</button>
                        </form>
                        @endif
                    </div>
                    <div style="max-height:320px;overflow-y:auto">
                        @forelse($navNotifs as $nf)
                        <form method="POST" action="{{ route('notifications.read', $nf) }}" style="margin:0">
                            @csrf
                            <button type="submit" style="width:100%;text-align:left;background:{{ $nf->is_read ? '#fff' : '#eff6ff' }};border:none;border-bottom:1px solid var(--gray-100);padding:11px 16px;display:flex;gap:10px;align-items:flex-start;cursor:pointer;transition:.15s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='{{ $nf->is_read ? '#fff' : '#eff6ff' }}'">
                                <span style="font-size:1.1rem;line-height:1.2">{{ $nf->icon }}</span>
                                <div style="flex:1;min-width:0">
                                    <div style="display:flex;justify-content:space-between;align-items:center;gap:6px">
                                        <span style="font-size:.78rem;font-weight:{{ $nf->is_read ? '600' : '800' }};color:var(--gray-900)">{{ $nf->title }}</span>
                                        @if(!$nf->is_read)<span style="width:7px;height:7px;border-radius:50%;background:var(--blue);flex-shrink:0"></span>@endif
                                    </div>
                                    <div style="font-size:.73rem;color:var(--gray-500);margin-top:2px;line-height:1.35">{{ $nf->message }}</div>
                                    <div style="font-size:.66rem;color:var(--gray-400);margin-top:3px">{{ $nf->created_at->diffForHumans() }}</div>
                                </div>
                            </button>
                        </form>
                        @empty
                        <div style="padding:28px 16px;text-align:center;color:var(--gray-400);font-size:.8rem">
                            🔔 Belum ada notifikasi
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="topbar-user">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                <span class="uname">{{ Str::words(auth()->user()->name, 1, '') }}</span>
                <span class="role-badge {{ auth()->user()->isDriver() ? 'driver':'' }}">{{ auth()->user()->isOwner() ? 'Pemilik Usaha':'Driver' }}</span>
            </div>
            @endauth
        </div>
    </header>

    <div class="content">
        @if(session('success'))
        <div class="alert alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error') || $errors->has('error'))
        <div class="alert alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') ?? $errors->first('error') }}
        </div>
        @endif
        @if($errors->any() && !$errors->has('error'))
        <div class="alert alert-danger">
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul style="margin-top:6px;padding-left:18px">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        </div>
        @endif

        @yield('content')
    </div>
</div>
<script>
document.addEventListener('click', function(e) {
    const w = document.getElementById('notifWrapper');
    const d = document.getElementById('notifDropdown');
    if (w && d && !w.contains(e.target)) {
        d.classList.remove('open');
    }
});
</script>
@stack('scripts')
</body>
</html>
