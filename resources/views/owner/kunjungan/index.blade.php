@extends('layouts.app')
@section('title','Pemantauan Kunjungan')
@section('page-title','Pemantauan Kunjungan')
@section('page-sub','Monitor aktivitas driver secara real-time')
@section('content')
<div class="page-header">
    <div><h1>Pemantauan Kunjungan</h1><p>Monitor aktivitas driver secara real-time</p></div>
</div>

<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="padding:14px 18px">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Driver</label>
                <select name="driver_id" class="form-control">
                    <option value="">Semua Driver</option>
                    @foreach($drivers as $d) <option value="{{ $d->id }}" {{ request('driver_id')==$d->id?'selected':'' }}>{{ $d->name }}</option> @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:110px">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('status')==='aktif'?'selected':'' }}>Aktif</option>
                    <option value="selesai" {{ request('status')==='selesai'?'selected':'' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status')==='dibatalkan'?'selected':'' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
            </div>
            <button class="btn btn-primary btn-sm">🔍 Filter</button>
            <a href="{{ route('owner.kunjungan.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Driver</th><th>Lokasi</th><th>Mulai</th><th>Selesai</th><th>Durasi</th><th>Transaksi</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($kunjungan as $k)
            <tr>
                <td><strong>{{ $k->driver->name ?? '–' }}</strong></td>
                <td style="font-size:.82rem">{{ $k->lokasiMangkal->nama_lokasi ?? '–' }}</td>
                <td style="font-size:.78rem;white-space:nowrap">{{ $k->waktu_mulai->format('d M, H:i') }}</td>
                <td style="font-size:.78rem">{{ $k->waktu_selesai ? $k->waktu_selesai->format('H:i') : '–' }}</td>
                <td style="font-size:.78rem;color:var(--gray-500)">{{ $k->durasi ?? '–' }}</td>
                <td><span class="badge badge-blue">{{ $k->sales->count() }}</span></td>
                <td style="font-weight:700;color:var(--blue);font-size:.82rem">Rp {{ number_format($k->sales->where('status','valid')->sum('total'),0,',','.') }}</td>
                <td>
                    @if($k->status==='aktif') <span class="badge badge-green">🟢 Aktif</span>
                    @elseif($k->status==='selesai') <span class="badge badge-blue">Selesai</span>
                    @else <span class="badge badge-red">Batal</span> @endif
                </td>
                <td>
                    <div style="display:flex;gap:5px">
                        <a href="{{ route('owner.kunjungan.show',$k) }}" class="btn btn-xs btn-outline">Detail</a>
                        @if($k->status==='aktif')
                        <form method="POST" action="{{ route('owner.kunjungan.force-selesai',$k) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-danger" onclick="return confirm('Paksa selesaikan?')">⏹</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9"><div class="empty-state" style="padding:40px"><div class="ei">🏃</div><p>Tidak ada kunjungan ditemukan</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px 16px">{{ $kunjungan->appends(request()->query())->links('vendor.pagination.simple-bs') }}</div>
</div>
@endsection
