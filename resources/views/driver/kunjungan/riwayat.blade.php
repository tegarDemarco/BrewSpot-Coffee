@extends('layouts.app')
@section('title','Riwayat Transaksi')
@section('page-title','Riwayat Transaksi')
@section('page-sub','Semua kunjungan dan transaksi yang pernah kamu catat')
@section('content')
<div class="page-header"><div><h1>Riwayat Kunjungan Saya</h1><p>Semua riwayat kunjungan dan transaksi</p></div></div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Lokasi</th><th>Tanggal</th><th>Waktu</th><th>Durasi</th><th>Transaksi</th><th>Total</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($kunjungan as $k)
            <tr>
                <td><strong>{{ $k->lokasiMangkal->nama_lokasi }}</strong></td>
                <td style="font-size:.8rem">{{ $k->waktu_mulai->format('d M Y') }}</td>
                <td style="font-size:.78rem;color:var(--gray-500)">{{ $k->waktu_mulai->format('H:i') }}@if($k->waktu_selesai) – {{ $k->waktu_selesai->format('H:i') }}@endif</td>
                <td style="font-size:.78rem;color:var(--gray-500)">{{ $k->durasi ?? '–' }}</td>
                <td><span class="badge badge-blue">{{ $k->sales->count() }}</span></td>
                <td style="font-weight:700;color:var(--blue)">Rp {{ number_format($k->sales->where('status','valid')->sum('total'),0,',','.') }}</td>
                <td><a href="{{ route('driver.kunjungan.show',$k) }}" class="btn btn-xs btn-outline">Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state" style="padding:40px"><div class="ei">📋</div><p>Belum ada riwayat kunjungan</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px 16px">{{ $kunjungan->links('vendor.pagination.simple-bs') }}</div>
</div>
@endsection
