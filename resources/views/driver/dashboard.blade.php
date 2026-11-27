@extends('layouts.app')
@section('title','Dashboard Driver')
@section('page-title','Dashboard')
@section('page-sub', 'Halo ' . auth()->user()->name . ', selamat bertugas!')
@section('content')
@php $driver = auth()->user(); @endphp
<div class="page-header">
    <div>
        <h1>Halo, {{ Str::words($driver->name,1,'') }}! 👋</h1>
        <p>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
</div>

@if($kunjunganAktif)
<div class="kunjungan-hero">
    <div class="kj-status">🟢 SEDANG MANGKAL</div>
    <h2>{{ $kunjunganAktif->lokasiMangkal->nama_lokasi }}</h2>
    <p>{{ $kunjunganAktif->lokasiMangkal->alamat }}</p>
    <div style="display:flex;gap:16px;margin:14px 0;flex-wrap:wrap">
        <div style="background:rgba(255,255,255,.12);border-radius:8px;padding:8px 14px">
            <div style="font-size:.65rem;opacity:.7;margin-bottom:2px">Mulai</div>
            <div style="font-weight:700">{{ $kunjunganAktif->waktu_mulai->format('H:i') }}</div>
        </div>
        <div style="background:rgba(255,255,255,.12);border-radius:8px;padding:8px 14px">
            <div style="font-size:.65rem;opacity:.7;margin-bottom:2px">Transaksi</div>
            <div style="font-weight:700">{{ $kunjunganAktif->sales->count() }}</div>
        </div>
        <div style="background:rgba(255,255,255,.12);border-radius:8px;padding:8px 14px">
            <div style="font-size:.65rem;opacity:.7;margin-bottom:2px">Total</div>
            <div style="font-weight:700">Rp {{ number_format($kunjunganAktif->sales->where('status','valid')->sum('total'),0,',','.') }}</div>
        </div>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('driver.kunjungan.aktif') }}" class="btn btn-success">🧾 Input Transaksi</a>
        <form method="POST" action="{{ route('driver.kunjungan.selesai',$kunjunganAktif) }}">
            @csrf
            <button class="btn btn-outline" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3);color:#fff" onclick="return confirm('Selesaikan kunjungan?')">⏹ Selesai Mangkal</button>
        </form>
    </div>
</div>
@else
<div class="alert alert-info">
    📍 Belum ada kunjungan aktif.
    @if($ruteHariIni) <a href="{{ route('driver.rute.show',$ruteHariIni) }}" style="font-weight:700;color:var(--navy)"> Lihat rute hari ini →</a>
    @else Tunggu penugasan rute dari pemilik.@endif
</div>
@endif

<div class="stat-grid">
    <div class="stat-card blue"><div class="stat-icon">💰</div><div class="stat-value">Rp {{ number_format($totalPenjualanHariIni,0,',','.') }}</div><div class="stat-label">Penjualan Hari Ini</div></div>
    <div class="stat-card green"><div class="stat-icon">🏃</div><div class="stat-value">{{ $riwayatSingkat->count() }}</div><div class="stat-label">Kunjungan Terakhir</div></div>
</div>

@if($ruteHariIni)
<div class="card" style="margin-bottom:18px">
    <div class="card-header"><h3>🗺️ Rute Hari Ini: {{ $ruteHariIni->nama_rute }}</h3><a href="{{ route('driver.rute.show',$ruteHariIni) }}" class="btn btn-ghost btn-sm">Lihat Detail →</a></div>
    <div class="card-body">
        <div class="rute-steps">
        @foreach($ruteHariIni->details as $d)
        <div class="rute-step">
            <div class="step-num {{ $d->kunjungan->where('status','selesai')->count() ? 'done' : '' }}">{{ $d->urutan }}</div>
            <div class="step-body">
                <h4>{{ $d->lokasiMangkal->nama_lokasi }}</h4>
                <p>{{ $d->lokasiMangkal->alamat }}</p>
                @if($d->jam_rencana_mulai)<p style="color:var(--blue);font-size:.73rem;font-weight:600;margin-top:2px">⏰ {{ substr($d->jam_rencana_mulai,0,5) }} – {{ substr($d->jam_rencana_selesai,0,5) }}</p>@endif
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header"><h3>📋 Riwayat Terakhir</h3><a href="{{ route('driver.riwayat') }}" class="btn btn-ghost btn-sm">Lihat Semua →</a></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Lokasi</th><th>Tanggal</th><th>Durasi</th><th>Transaksi</th><th>Total</th></tr></thead>
            <tbody>
            @forelse($riwayatSingkat as $k)
            <tr>
                <td><strong>{{ $k->lokasiMangkal->nama_lokasi }}</strong></td>
                <td style="font-size:.78rem">{{ $k->waktu_mulai->format('d M Y') }}</td>
                <td style="font-size:.78rem;color:var(--gray-500)">{{ $k->durasi ?? '–' }}</td>
                <td>{{ $k->sales->count() }}</td>
                <td style="font-weight:600;color:var(--blue)">Rp {{ number_format($k->sales->where('status','valid')->sum('total'),0,',','.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5"><div class="empty-state" style="padding:20px 0"><p>Belum ada riwayat</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
