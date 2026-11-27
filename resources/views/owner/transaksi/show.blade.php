@extends('layouts.app')
@section('title','Detail Transaksi')
@section('page-title','Detail Transaksi')
@section('content')
<div class="page-header">
    <div><h1>Detail Transaksi</h1><p>{{ $sale->nomor_transaksi }}</p></div>
    <a href="{{ route('owner.transaksi.index') }}" class="btn btn-outline">← Kembali</a>
</div>
<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3>ℹ️ Informasi Transaksi</h3></div>
        <div class="card-body">
            <table style="width:100%;font-size:.85rem">
                <tr><td style="padding:8px 0;color:var(--gray-500);width:40%">No. Transaksi</td><td><strong>{{ $sale->nomor_transaksi }}</strong></td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Driver</td><td>{{ $sale->kunjungan->driver->name ?? '–' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Lokasi</td><td>{{ $sale->kunjungan->lokasiMangkal->nama_lokasi ?? '–' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Waktu</td><td>{{ $sale->created_at->format('d M Y, H:i') }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Pembayaran</td><td><span class="badge badge-blue">{{ ucfirst($sale->metode_pembayaran) }}</span></td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Status</td><td><span class="badge {{ $sale->status==='valid'?'badge-green':'badge-red' }}">{{ ucfirst($sale->status) }}</span></td></tr>
                <tr><td style="padding:8px 0;color:var(--gray-500)">Total</td><td><strong style="color:var(--blue);font-size:1.2rem">Rp {{ number_format($sale->total,0,',','.') }}</strong></td></tr>
            </table>
            @if($sale->catatan) <div class="alert alert-info" style="margin-top:14px;font-size:.8rem">📝 {{ $sale->catatan }}</div> @endif
            @if($sale->status==='valid')
            <form method="POST" action="{{ route('owner.transaksi.cancel',$sale) }}" style="margin-top:14px">
                @csrf @method('PATCH')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Batalkan transaksi ini?')">❌ Batalkan Transaksi</button>
            </form>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>🛒 Item Pesanan</h3></div>
        <div class="card-body">
            @foreach($sale->details as $d)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--gray-100)">
                <div>
                    <div style="font-weight:600;font-size:.875rem">{{ $d->menu->nama_menu ?? '–' }}</div>
                    <div style="font-size:.75rem;color:var(--gray-500)">Rp {{ number_format($d->harga_satuan,0,',','.') }} × {{ $d->qty }}</div>
                </div>
                <div style="font-weight:700;color:var(--blue)">Rp {{ number_format($d->subtotal,0,',','.') }}</div>
            </div>
            @endforeach
            <div style="display:flex;justify-content:space-between;font-weight:800;font-size:1rem;margin-top:12px;padding-top:10px">
                <span>Total</span>
                <span style="color:var(--blue)">Rp {{ number_format($sale->total,0,',','.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
