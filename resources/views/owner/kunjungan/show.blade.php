@extends('layouts.app')
@section('title','Detail Kunjungan')
@section('page-title','Detail Kunjungan')
@section('content')
<div class="page-header">
    <div><h1>Detail Kunjungan</h1><p>{{ $kunjungan->lokasiMangkal->nama_lokasi ?? '–' }} • {{ $kunjungan->driver->name ?? '–' }}</p></div>
    <a href="{{ route('owner.kunjungan.index') }}" class="btn btn-outline">← Kembali</a>
</div>
<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3>ℹ️ Informasi Kunjungan</h3></div>
        <div class="card-body">
            <table style="width:100%;font-size:.85rem">
                <tr><td style="padding:8px 0;color:var(--gray-500);width:40%">Driver</td><td><strong>{{ $kunjungan->driver->name }}</strong></td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Lokasi</td><td><strong>{{ $kunjungan->lokasiMangkal->nama_lokasi ?? '–' }}</strong></td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Waktu Mulai</td><td>{{ $kunjungan->waktu_mulai->format('d M Y, H:i') }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Waktu Selesai</td><td>{{ $kunjungan->waktu_selesai ? $kunjungan->waktu_selesai->format('H:i') : '–' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Durasi</td><td>{{ $kunjungan->durasi ?? '–' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Status</td><td>
                    @if($kunjungan->status==='aktif') <span class="badge badge-green">Aktif</span>
                    @elseif($kunjungan->status==='selesai') <span class="badge badge-blue">Selesai</span>
                    @else <span class="badge badge-red">Dibatalkan</span> @endif
                </td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Total Penjualan</td><td><strong style="color:var(--blue);font-size:1.1rem">Rp {{ number_format($kunjungan->sales->where('status','valid')->sum('total'),0,',','.') }}</strong></td></tr>
            </table>
            @if($kunjungan->catatan) <div class="alert alert-info" style="margin-top:14px;font-size:.8rem">📝 {{ $kunjungan->catatan }}</div> @endif
            @if($kunjungan->status==='aktif')
            <form method="POST" action="{{ route('owner.kunjungan.force-selesai',$kunjungan) }}" style="margin-top:14px">
                @csrf @method('PATCH')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Paksa selesaikan kunjungan ini?')">⏹ Paksa Selesai</button>
            </form>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>🧾 Transaksi ({{ $kunjungan->sales->count() }})</h3></div>
        <div class="card-body">
            @forelse($kunjungan->sales as $s)
            <div style="border:1px solid var(--gray-200);border-radius:10px;padding:12px;margin-bottom:10px">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span style="font-size:.72rem;color:var(--gray-400)">{{ $s->nomor_transaksi }}</span>
                    <span class="badge {{ $s->status==='valid'?'badge-green':'badge-red' }}">{{ $s->status }}</span>
                </div>
                @foreach($s->details as $d)
                <div style="font-size:.8rem;display:flex;justify-content:space-between;padding:4px 0">
                    <span>{{ $d->menu->nama_menu ?? '–' }} x{{ $d->qty }}</span>
                    <span>Rp {{ number_format($d->subtotal,0,',','.') }}</span>
                </div>
                @endforeach
                <div style="margin-top:8px;padding-top:8px;border-top:1px solid var(--gray-100);display:flex;justify-content:space-between;font-weight:700;font-size:.85rem">
                    <span>{{ ucfirst($s->metode_pembayaran) }}</span>
                    <span style="color:var(--blue)">Rp {{ number_format($s->total,0,',','.') }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:30px 0"><div class="ei">🧾</div><p>Tidak ada transaksi</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
