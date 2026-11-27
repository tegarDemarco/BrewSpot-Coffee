@extends('layouts.app')
@section('title','Transaksi Penjualan')
@section('page-title','Transaksi Penjualan')
@section('page-sub','Catat dan pantau performa transaksi dengan mudah')
@section('content')
<div class="page-header">
    <div><h1>Transaksi Penjualan</h1><p>Catat dan pantau performa transaksi dengan mudah</p></div>
    <div class="action-bar">
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
            <input type="date" name="dari" class="form-control" value="{{ request('dari', date('Y-m-01')) }}" style="width:140px">
            <input type="date" name="sampai" class="form-control" value="{{ request('sampai', date('Y-m-d')) }}" style="width:140px">
            <select name="status" class="form-control" style="width:120px">
                <option value="">Semua Status</option>
                <option value="valid" {{ request('status')==='valid'?'selected':'' }}>Valid</option>
                <option value="dibatalkan" {{ request('status')==='dibatalkan'?'selected':'' }}>Dibatalkan</option>
            </select>
            <button class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
</div>

<!-- TOP INFO: active kunjungan + total -->
@if($kunjunganAktif)
<div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap">
    <div class="card" style="flex:1;min-width:200px">
        <div class="card-body" style="display:flex;align-items:center;gap:14px">
            <div style="width:44px;height:44px;background:#d1fae5;border-radius:10px;display:grid;place-items:center;font-size:1.3rem">📍</div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em">Lokasi Aktif</div>
                <div style="font-weight:800;font-size:.95rem">{{ $kunjunganAktif->lokasiMangkal->nama_lokasi }}</div>
                <span class="badge badge-green" style="margin-top:3px">Sedang Mangkal</span>
            </div>
        </div>
    </div>
    <div class="card" style="flex:1;min-width:200px">
        <div class="card-body">
            <div style="font-size:.7rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Total Penjualan Sesi Ini</div>
            <div style="font-size:1.6rem;font-weight:800;color:var(--blue)">Rp {{ number_format($kunjunganAktif->sales->where('status','valid')->sum('total'),0,',','.') }}</div>
        </div>
    </div>
</div>
@endif

<div class="grid-col-6-6">
    <div class="card">
        <div class="card-header">
            <h3>Riwayat Transaksi</h3>
            <a href="{{ route('owner.laporan.index') }}" class="btn btn-ghost btn-sm">Lihat Semua →</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Waktu</th><th>Lokasi</th><th>Menu</th><th>Jumlah</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($transaksi as $s)
                <tr>
                    <td style="font-size:.75rem;color:var(--gray-500);white-space:nowrap">{{ $s->created_at->format('H:i') }}<br><span style="font-size:.7rem">{{ $s->created_at->format('d M') }}</span></td>
                    <td style="font-size:.8rem">{{ $s->kunjungan->lokasiMangkal->nama_lokasi ?? '–' }}</td>
                    <td style="font-size:.78rem">{{ $s->details->first()?->menu?->nama_menu ?? '–' }}@if($s->details->count()>1) <span style="color:var(--gray-400)">+{{ $s->details->count()-1 }}</span>@endif</td>
                    <td>{{ $s->details->sum('qty') }}</td>
                    <td style="font-weight:700;color:var(--blue)">Rp {{ number_format($s->total,0,',','.') }}</td>
                    <td>
                        @if($s->status==='valid') <span class="badge badge-green">Valid</span>
                        @else <span class="badge badge-red">Batal</span> @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state" style="padding:30px 0"><div class="ei">🧾</div><p>Belum ada transaksi</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px">{{ $transaksi->appends(request()->query())->links('vendor.pagination.simple-bs') }}</div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Ringkasan Periode</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
                <div style="background:var(--gray-50);border-radius:10px;padding:14px;border:1px solid var(--gray-200)">
                    <div style="font-size:.7rem;color:var(--gray-500);font-weight:600;margin-bottom:4px">Total Penjualan</div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--navy)">Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
                </div>
                <div style="background:var(--gray-50);border-radius:10px;padding:14px;border:1px solid var(--gray-200)">
                    <div style="font-size:.7rem;color:var(--gray-500);font-weight:600;margin-bottom:4px">Total Transaksi</div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--navy)">{{ $totalTranCount }}</div>
                </div>
                <div style="background:var(--gray-50);border-radius:10px;padding:14px;border:1px solid var(--gray-200)">
                    <div style="font-size:.7rem;color:var(--gray-500);font-weight:600;margin-bottom:4px">Rata-rata/Transaksi</div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--navy)">Rp {{ $totalTranCount > 0 ? number_format($totalPenjualan/$totalTranCount,0,',','.') : 0 }}</div>
                </div>
                <div style="background:var(--gray-50);border-radius:10px;padding:14px;border:1px solid var(--gray-200)">
                    <div style="font-size:.7rem;color:var(--gray-500);font-weight:600;margin-bottom:4px">Dibatalkan</div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--danger)">{{ $dibatalkan }}</div>
                </div>
            </div>
            <canvas id="chartPie" height="200"></canvas>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const payData = {!! json_encode($byPayment) !!};
if (Object.keys(payData).length > 0) {
    new Chart(document.getElementById('chartPie').getContext('2d'), {
        type: 'doughnut',
        data: { labels: Object.keys(payData), datasets: [{ data: Object.values(payData), backgroundColor: ['#1a56db','#f59e0b','#10b981','#ef4444'], borderWidth: 0 }] },
        options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'right', labels:{ font:{size:11,family:'Plus Jakarta Sans'}, boxWidth:12, padding:14 } } } }
    });
}
</script>
@endpush
