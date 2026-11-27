@extends('layouts.app')
@section('title','Pengaturan')
@section('page-title','Pengaturan')
@section('page-sub','Kelola pengaturan akun dan sistem')
@section('content')
<div class="page-header">
    <div><h1>Pengaturan</h1><p>Kelola pengaturan akun dan sistem</p></div>
</div>

<div class="grid-col-6-6">
    <!-- PROFILE -->
    <div class="card">
        <div class="card-header"><h3>👤 Profil Akun</h3></div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding:16px;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200)">
                <div style="width:60px;height:60px;border-radius:50%;background:var(--navy);display:grid;place-items:center;font-size:1.3rem;font-weight:800;color:#fff">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                <div>
                    <div style="font-weight:800;font-size:1rem">{{ auth()->user()->name }}</div>
                    <div style="font-size:.8rem;color:var(--gray-500)">{{ auth()->user()->email }}</div>
                    <span class="badge badge-navy" style="margin-top:4px">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('owner.pengaturan.update-profile') }}">
                @csrf @method('PUT')
                <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required></div>
                <div class="form-group"><label class="form-label">No. HP</label><input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" placeholder="081234567890"></div>
                <button type="submit" class="btn btn-primary">💾 Simpan Profil</button>
            </form>
        </div>
    </div>

    <!-- PASSWORD -->
    <div class="card">
        <div class="card-header"><h3>🔒 Ubah Password</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('owner.pengaturan.update-password') }}">
                @csrf @method('PUT')
                <div class="form-group"><label class="form-label">Password Saat Ini</label><input type="password" name="current_password" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Password Baru</label><input type="password" name="password" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Konfirmasi Password Baru</label><input type="password" name="password_confirmation" class="form-control" required></div>
                <button type="submit" class="btn btn-primary">🔒 Ubah Password</button>
            </form>
        </div>
    </div>

    <!-- USAHA INFO -->
    <div class="card">
        <div class="card-header"><h3>☕ Informasi Usaha</h3></div>
        <div class="card-body">
            <div class="form-group"><label class="form-label">Nama Usaha</label><input type="text" class="form-control" value="BrewSpot Kopi Keliling" placeholder="Nama usaha Anda"></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea class="form-control" placeholder="Deskripsi usaha...">Kopi keliling berkualitas</textarea></div>
            <div class="form-group"><label class="form-label">Nomor WhatsApp</label><input type="text" class="form-control" placeholder="081234567890"></div>
            <button class="btn btn-primary">💾 Simpan Info Usaha</button>
        </div>
    </div>

    <!-- SYSTEM INFO -->
    <div class="card">
        <div class="card-header"><h3>ℹ️ Informasi Sistem</h3></div>
        <div class="card-body">
            <table style="width:100%;font-size:.85rem">
                <tr><td style="padding:8px 0;color:var(--gray-500);width:40%">Versi Aplikasi</td><td style="font-weight:600">BrewSpot v1.0</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Framework</td><td style="font-weight:600">Laravel 13</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">PHP Version</td><td style="font-weight:600">{{ PHP_VERSION }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Database</td><td style="font-weight:600">MySQL (MariaDB)</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Total Driver</td><td style="font-weight:600">{{ $totalDriver }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Total Lokasi</td><td style="font-weight:600">{{ $totalLokasi }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Bergabung Sejak</td><td style="font-weight:600">{{ auth()->user()->created_at->format('d M Y') }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection
