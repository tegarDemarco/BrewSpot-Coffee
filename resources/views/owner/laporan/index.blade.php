@extends('layouts.app')
@section('title','Laporan & Analitik')
@section('page-title','Laporan & Analitik')
@section('page-sub','Pantau performa usaha kamu secara menyeluruh')
@section('content')
<div class="page-header">
    <div><h1>Laporan & Analitik</h1><p>Pantau performa usaha kamu secara menyeluruh</p></div>
    <div class="action-bar">
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <div style="display:flex;align-items:center;gap:6px;background:#fff;border:1px solid var(--gray-200);border-radius:9px;padding:5px 12px;font-size:.8rem">
                📅
                <input type="date" name="dari" value="{{ $dari }}" style="border:none;outline:none;font-size:.8rem;font-family:inherit;color:var(--gray-700)">
                <span style="color:var(--gray-400)">–</span>
                <input type="date" name="sampai" value="{{ $sampai }}" style="border:none;outline:none;font-size:.8rem;font-family:inherit;color:var(--gray-700)">
            </div>
            <select name="lokasi_id" class="form-control" style="width:165px;padding:6px 10px;font-size:.8rem">
                <option value="">Semua Lokasi</option>
                @foreach($daftarLokasi as $lok)
                <option value="{{ $lok->id }}" {{ (string)$lokasiId === (string)$lok->id ? 'selected' : '' }}>{{ $lok->nama_lokasi }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary btn-sm">Tampilkan</button>
            <a href="{{ route('owner.rekap.index', request()->query()) }}" class="btn btn-outline btn-sm">📑 Rekap Penjualan</a>
            <a href="{{ route('owner.rekap.pdf', request()->query()) }}" class="btn btn-outline btn-sm" style="background:var(--navy);color:#fff;border-color:var(--navy)">📄 Export PDF</a>
        </form>
    </div>
</div>

<!-- STAT CARDS -->
<div class="stat-grid" style="margin-bottom:20px">
    <div class="stat-card blue">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
            <div class="stat-icon" style="margin:0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
        </div>
        <div class="stat-value">Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
        <div class="stat-label">Total Penjualan</div>
        <div class="stat-trend up">▲ 15% dari periode lalu</div>
    </div>
    <div class="stat-card green">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
            <div class="stat-icon" style="margin:0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
        </div>
        <div class="stat-value">{{ number_format($totalTransaksi) }}</div>
        <div class="stat-label">Total Transaksi</div>
        <div class="stat-trend up">▲ 12% dari periode lalu</div>
    </div>
    @if($perLokasi->count() > 0)
    <div class="stat-card orange">
        <div style="font-size:.7rem;color:var(--gray-500);font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Lokasi Terlaris</div>
        <div class="stat-value" style="font-size:1.1rem">{{ $perLokasi->first()->nama_lokasi }}</div>
        <div class="stat-label">Rp {{ number_format($perLokasi->first()->total_penjualan,0,',','.') }}</div>
    </div>
    @endif
</div>

<!-- CHARTS -->
<div class="grid-col-6-6" style="margin-bottom:20px">
    <div class="card">
        <div class="card-header"><h3>Penjualan per Lokasi</h3></div>
        <div class="card-body"><canvas id="chartLokasi" height="220"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>Penjualan per Jam</h3>
        </div>
        <div class="card-body"><canvas id="chartJam" height="220"></canvas></div>
    </div>
</div>

<!-- RECOMMENDATION BOX -->
@if($perLokasi->count() > 0)
<div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));border-radius:var(--radius-lg);padding:20px 24px;color:#fff;display:flex;align-items:center;gap:20px;margin-bottom:20px;flex-wrap:wrap">
    <div style="font-size:1.6rem">⭐</div>
    <div style="flex:1">
        <div style="font-weight:800;font-size:.95rem;margin-bottom:4px">Rekomendasi</div>
        <div style="font-size:.82rem;opacity:.85;line-height:1.6">
            Pertahankan jadwal di <strong>{{ $perLokasi->first()->nama_lokasi }}</strong> karena menjadi lokasi dengan penjualan tertinggi dalam periode ini ({{ $perLokasi->first()->jumlah_transaksi }} transaksi).
        </div>
    </div>
    <a href="{{ route('owner.rute.create') }}" class="btn btn-outline btn-sm" style="color:var(--navy);flex-shrink:0">Lihat Detail →</a>
</div>
@endif

<!-- TABLES -->
<div class="grid-col-6-6">
    <div class="card">
        <div class="card-header"><h3>🏆 Performa Per Lokasi</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Rank</th><th>Lokasi</th><th>Transaksi</th><th>Kunjungan</th><th>Total</th></tr></thead>
                <tbody>
                @forelse($perLokasi as $i => $l)
                <tr>
                    <td><div style="width:24px;height:24px;background:{{ ['var(--navy)','#3b82f6','#60a5fa'][$i] ?? 'var(--gray-300)' }};color:#fff;border-radius:50%;display:grid;place-items:center;font-size:.7rem;font-weight:700">{{ $i+1 }}</div></td>
                    <td><strong>{{ $l->nama_lokasi }}</strong></td>
                    <td>{{ $l->jumlah_transaksi }}</td>
                    <td>{{ $l->jumlah_kunjungan }}</td>
                    <td style="font-weight:700;color:var(--blue)">Rp {{ number_format($l->total_penjualan,0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state" style="padding:20px 0"><p>Tidak ada data</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>⏰ Jam Terlaris</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Jam</th><th>Transaksi</th><th>Total Penjualan</th></tr></thead>
                <tbody>
                @forelse($perJam->sortByDesc('total_penjualan') as $j)
                <tr>
                    <td><strong>{{ sprintf('%02d:00', $j->jam) }}</strong></td>
                    <td>{{ $j->jumlah_transaksi }}</td>
                    <td style="font-weight:600;color:var(--blue)">Rp {{ number_format($j->total_penjualan,0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:20px;color:var(--gray-400)">Tidak ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($totalTransaksi < 20)
        <div style="padding:12px 16px"><div class="alert alert-warning" style="margin:0;font-size:.75rem">📊 Data masih terbatas. Hasil adalah ringkasan historis dan belum cukup untuk menyimpulkan pola yang stabil.</div></div>
        @endif
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('chartLokasi').getContext('2d'), {
    type: 'bar',
    data: { labels: {!!json_encode($perLokasi->pluck('nama_lokasi')->map(fn($n)=>Str::limit($n,10)))!!}, datasets:[{data:{!!json_encode($perLokasi->pluck('total_penjualan'))!!},backgroundColor:'#1a56db',borderRadius:6,barThickness:28}] },
    options: { responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{callback:v=>'Rp'+Math.round(v/1000)+'K',font:{size:10}}},x:{grid:{display:false},ticks:{font:{size:10}}}} }
});
new Chart(document.getElementById('chartJam').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!!json_encode($perJam->sortBy('jam')->pluck('jam')->map(fn($j)=>sprintf('%02d:00',$j)))!!},
        datasets:[{data:{!!json_encode($perJam->sortBy('jam')->pluck('total_penjualan'))!!},borderColor:'#f59e0b',backgroundColor:'rgba(245,158,11,.08)',borderWidth:2.5,pointBackgroundColor:'#f59e0b',pointRadius:4,tension:.4,fill:true}]
    },
    options: { responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{callback:v=>'Rp'+Math.round(v/1000)+'K',font:{size:10}}},x:{grid:{display:false},ticks:{font:{size:10}}}} }
});
</script>
@endpush
