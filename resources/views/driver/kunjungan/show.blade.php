@extends('layouts.app')
@section('title','Detail Kunjungan')
@section('page-title','Detail Kunjungan')
@section('content')
<div class="page-header">
    <div><h1>Detail Kunjungan</h1><p>{{ $kunjungan->lokasiMangkal->nama_lokasi ?? '–' }}</p></div>
    <a href="{{ route('driver.riwayat') }}" class="btn btn-outline">← Kembali</a>
</div>
<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3>ℹ️ Info Kunjungan</h3></div>
        <div class="card-body">
            <table style="width:100%;font-size:.85rem">
                <tr><td style="padding:8px 0;color:var(--gray-500);width:40%">Lokasi</td><td><strong>{{ $kunjungan->lokasiMangkal->nama_lokasi }}</strong></td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Tanggal</td><td>{{ $kunjungan->waktu_mulai->format('d M Y') }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Waktu Mulai</td><td>{{ $kunjungan->waktu_mulai->format('H:i') }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Waktu Selesai</td><td>{{ $kunjungan->waktu_selesai ? $kunjungan->waktu_selesai->format('H:i') : '–' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Durasi</td><td>{{ $kunjungan->durasi ?? '–' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Total</td><td><strong style="color:var(--blue)">Rp {{ number_format($kunjungan->sales->where('status','valid')->sum('total'),0,',','.') }}</strong></td></tr>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>🧾 Transaksi</h3></div>
        <div class="card-body">
            @forelse($kunjungan->sales as $s)
            <div style="border:1px solid var(--gray-200);border-radius:10px;padding:12px;margin-bottom:10px">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                    <span style="font-size:.72rem;color:var(--gray-400)">{{ $s->created_at->format('H:i') }}</span>
                    <span class="badge {{ $s->status==='valid'?'badge-green':'badge-red' }}">{{ $s->status }}</span>
                </div>
                @foreach($s->details as $d)
                <div style="font-size:.8rem;display:flex;justify-content:space-between;padding:3px 0">
                    <span>{{ $d->menu->nama_menu ?? '–' }} x{{ $d->qty }}</span>
                    <span>Rp {{ number_format($d->subtotal,0,',','.') }}</span>
                </div>
                @endforeach
                <div style="margin-top:8px;text-align:right;font-weight:700;color:var(--blue)">Rp {{ number_format($s->total,0,',','.') }}</div>
            </div>
            @empty
            <div class="empty-state" style="padding:30px 0"><div class="ei">🧾</div><p>Tidak ada transaksi</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
