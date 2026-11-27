@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-sub','Ringkasan aktivitas usaha kamu hari ini')

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Ringkasan aktivitas usaha kamu hari ini</p>
    </div>
    <div class="action-bar">
        <span style="font-size:.8rem;color:var(--gray-500);background:#fff;border:1px solid var(--gray-200);padding:7px 14px;border-radius:8px;display:flex;align-items:center;gap:6px">
            📅 {{ now()->locale('id')->isoFormat('D MMMM Y') }}
        </span>
        <a href="{{ route('owner.rute.create') }}" class="btn btn-primary btn-sm">+ Buat Rute</a>
    </div>
</div>

<!-- STAT CARDS -->
<div class="stat-grid">
    <div class="stat-card blue">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div class="stat-value">Rp {{ number_format($totalPenjualanBulanIni,0,',','.') }}</div>
        <div class="stat-label">Total Penjualan</div>
        <div class="stat-trend up">▲ 12% dari bulan lalu</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalTransaksiBulanIni) }}</div>
        <div class="stat-label">Jumlah Transaksi</div>
        <div class="stat-trend up">▲ 8% dari bulan lalu</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="stat-value">{{ $totalLokasi }}</div>
        <div class="stat-label">Lokasi Aktif</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="stat-value">{{ $totalDriver }}</div>
        <div class="stat-label">Driver Aktif</div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="grid-col-7-5" style="margin-bottom:20px">
    <div class="card">
        <div class="card-header">
            <h3>Penjualan Berdasarkan Lokasi</h3>
            <select style="font-size:.75rem;border:1px solid var(--gray-200);border-radius:7px;padding:4px 10px;color:var(--gray-700);background:#fff;cursor:pointer">
                <option>7 Hari Terakhir</option>
                <option>30 Hari Terakhir</option>
            </select>
        </div>
        <div class="card-body">
            <canvas id="chartLokasi" height="180"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>Lokasi Terlaris</h3>
            <a href="{{ route('owner.laporan.index') }}" class="btn btn-ghost btn-sm">Lihat Semua →</a>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($lokasiteratas->take(3) as $i => $l)
            <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--gray-100)">
                <div style="width:40px;height:40px;border-radius:8px;background:var(--blue-soft);overflow:hidden;display:grid;place-items:center;font-size:1.1rem;flex-shrink:0">📍</div>
                <div style="flex:1">
                    <div style="font-weight:700;font-size:.85rem">#{{ $i+1 }} {{ $l->nama_lokasi }}</div>
                    <div style="font-size:.73rem;color:var(--gray-500)">Rp {{ number_format($l->total_penjualan,0,',','.') }} • {{ $l->jumlah_transaksi }} Transaksi</div>
                </div>
                <div style="width:28px;height:28px;border-radius:50%;background:var(--navy);color:#fff;display:grid;place-items:center;font-size:.7rem">→</div>
            </div>
            @empty
            <div class="empty-state" style="padding:30px 0"><div class="ei">📊</div><p>Belum ada data</p></div>
            @endforelse
        </div>
    </div>
</div>

<!-- RECENT ACTIVITY + ACTIVE VISITS -->
<div class="grid-col-6-6" style="margin-bottom:20px">
    <div class="card">
        <div class="card-header">
            <h3>Kunjungan Terbaru</h3>
            <a href="{{ route('owner.kunjungan.index') }}" class="btn btn-ghost btn-sm">Lihat Semua →</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Driver</th><th>Lokasi</th><th>Status</th><th>Total</th></tr></thead>
                <tbody>
                @forelse($recentKunjungan as $k)
                <tr>
                    <td><strong>{{ Str::words($k->driver->name ?? '–', 1, '') }}</strong></td>
                    <td style="font-size:.78rem">{{ $k->lokasiMangkal->nama_lokasi ?? '–' }}</td>
                    <td>
                        @if($k->status==='aktif') <span class="badge badge-green">Aktif</span>
                        @elseif($k->status==='selesai') <span class="badge badge-blue">Selesai</span>
                        @else <span class="badge badge-red">Batal</span> @endif
                    </td>
                    <td style="font-weight:600;font-size:.8rem">Rp {{ number_format($k->sales->where('status','valid')->sum('total'),0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4"><div class="empty-state" style="padding:20px 0"><p>Belum ada kunjungan</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>Penjualan Harian (7 Hari)</h3>
        </div>
        <div class="card-body">
            <canvas id="chartHarian" height="180"></canvas>
        </div>
    </div>
</div>

<!-- TIPS -->
<div class="tips-card">
    <div class="tips-icon">💡</div>
    <div class="tips-body">
        <h4>Tips untuk kamu</h4>
        <p>
            @if($lokasiteratas->count() > 0)
                Pertimbangkan untuk menambah jadwal di <strong>{{ $lokasiteratas->first()->nama_lokasi }}</strong>, karena menjadi lokasi dengan penjualan cukup tinggi dalam periode ini.
            @else
                Mulai catat kunjungan dan transaksi untuk mendapatkan rekomendasi lokasi terlaris.
            @endif
        </p>
    </div>
    <a href="{{ route('owner.laporan.index') }}" class="btn btn-outline btn-sm" style="white-space:nowrap;flex-shrink:0">Lihat Rekomendasi →</a>
</div>
@endsection

@push('scripts')
<script>
const lokLabels = {!! json_encode($lokasiteratas->take(5)->pluck('nama_lokasi')->map(fn($n) => Str::limit($n, 10))) !!};
const lokData   = {!! json_encode($lokasiteratas->take(5)->pluck('total_penjualan')) !!};
new Chart(document.getElementById('chartLokasi').getContext('2d'), {
    type: 'bar',
    data: { labels: lokLabels, datasets: [{ data: lokData, backgroundColor: '#1a56db', borderRadius: 6, barThickness: 32 }] },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{callback:v=>'Rp'+Math.round(v/1000)+'K',font:{size:10}}}, x:{grid:{display:false},ticks:{font:{size:10}}} } }
});
new Chart(document.getElementById('chartHarian').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{ data: {!! json_encode($chartData) !!}, borderColor:'#1a56db', backgroundColor:'rgba(26,86,219,.06)', borderWidth:2.5, pointBackgroundColor:'#1a56db', pointRadius:3, tension:.4, fill:true }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{callback:v=>'Rp'+Math.round(v/1000)+'K',font:{size:10}}}, x:{grid:{display:false},ticks:{font:{size:10}}} } }
});
</script>
@endpush
