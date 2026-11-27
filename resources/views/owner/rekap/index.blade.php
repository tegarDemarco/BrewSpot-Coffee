@extends('layouts.app')
@section('title','Rekap Penjualan')
@section('page-title','Rekap Penjualan')
@section('page-sub','Analisis penjualan berdasarkan lokasi dan cetak laporan PDF')

@section('content')
<div class="page-header">
    <div>
        <h1>Rekap Penjualan</h1>
        <p>Analisis performa penjualan berdasarkan lokasi, menu, dan metode pembayaran</p>
    </div>
    <div class="action-bar">
        <a href="{{ route('owner.rekap.pdf', array_merge(request()->query(), ['preview' => 1])) }}" target="_blank" class="btn btn-outline btn-sm">👁️ Preview PDF</a>
        <a href="{{ route('owner.rekap.pdf', request()->query()) }}" class="btn btn-primary btn-sm">📄 Download PDF</a>
    </div>
</div>

<!-- FILTER BAR -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body" style="padding:14px 18px">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
            <div>
                <label class="form-label" style="font-size:.72rem;margin-bottom:4px">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari }}" class="form-control" style="padding:7px 10px;font-size:.8rem">
            </div>
            <div>
                <label class="form-label" style="font-size:.72rem;margin-bottom:4px">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai }}" class="form-control" style="padding:7px 10px;font-size:.8rem">
            </div>
            <div>
                <label class="form-label" style="font-size:.72rem;margin-bottom:4px">Lokasi Mangkal</label>
                <select name="lokasi_id" class="form-control" style="padding:7px 10px;font-size:.8rem;min-width:170px">
                    <option value="">Semua Lokasi</option>
                    @foreach($daftarLokasi as $lok)
                    <option value="{{ $lok->id }}" {{ (string)$lokasiId === (string)$lok->id ? 'selected' : '' }}>{{ $lok->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" style="font-size:.72rem;margin-bottom:4px">Driver</label>
                <select name="driver_id" class="form-control" style="padding:7px 10px;font-size:.8rem;min-width:150px">
                    <option value="">Semua Driver</option>
                    @foreach($daftarDriver as $drv)
                    <option value="{{ $drv->id }}" {{ (string)$driverId === (string)$drv->id ? 'selected' : '' }}>{{ $drv->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">🔍 Tampilkan Rekap</button>
            <a href="{{ route('owner.rekap.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>
</div>

<!-- STAT CARDS -->
<div class="stat-grid" style="margin-bottom:20px">
    <div class="stat-card blue">
        <div class="stat-icon">💰</div>
        <div class="stat-value">Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
        <div class="stat-label">Total Penjualan</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">🧾</div>
        <div class="stat-value">{{ number_format($totalTransaksi) }}</div>
        <div class="stat-label">Total Transaksi</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon">📍</div>
        <div class="stat-value">{{ number_format($totalKunjungan) }}</div>
        <div class="stat-label">Total Kunjungan</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon">📈</div>
        <div class="stat-value">Rp {{ number_format($rataRata,0,',','.') }}</div>
        <div class="stat-label">Rata-rata / Transaksi</div>
    </div>
</div>

<!-- CHART + METODE PEMBAYARAN -->
<div class="grid-col-7-5" style="margin-bottom:20px">
    <div class="card">
        <div class="card-header"><h3>📊 Kontribusi Penjualan per Lokasi</h3></div>
        <div class="card-body">
            <canvas id="chartRekapLokasi" height="210"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>💳 Metode Pembayaran</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Metode</th><th>Transaksi</th><th>Total</th></tr></thead>
                <tbody>
                @forelse($rekapMetode as $m)
                <tr>
                    <td style="text-transform:uppercase;font-weight:700;font-size:.78rem">{{ $m->metode_pembayaran }}</td>
                    <td>{{ $m->jumlah_transaksi }}</td>
                    <td style="font-weight:700;color:var(--blue)">Rp {{ number_format($m->total_penjualan,0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--gray-400)">Belum ada transaksi pada periode ini</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- REKAP PER LOKASI -->
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h3>📍 Rekap Penjualan Berdasarkan Lokasi</h3>
        <span class="badge badge-blue">{{ $rekapLokasi->count() }} lokasi</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lokasi Mangkal</th>
                    <th>Kunjungan</th>
                    <th>Transaksi</th>
                    <th>Rata-rata / Kunjungan</th>
                    <th>Kontribusi</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($rekapLokasi as $i => $l)
            @php
                $persen = $totalPenjualan > 0 ? round(($l->total_penjualan / $totalPenjualan) * 100, 1) : 0;
                $avgKunjungan = $l->jumlah_kunjungan > 0 ? $l->total_penjualan / $l->jumlah_kunjungan : 0;
            @endphp
            <tr>
                <td style="color:var(--gray-400)">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $l->nama_lokasi }}</strong>
                    <div style="font-size:.72rem;color:var(--gray-400)">{{ Str::limit($l->alamat, 45) }}</div>
                </td>
                <td><span class="badge badge-gray">{{ $l->jumlah_kunjungan }}x</span></td>
                <td>{{ $l->jumlah_transaksi }}</td>
                <td style="font-size:.8rem">Rp {{ number_format($avgKunjungan,0,',','.') }}</td>
                <td style="min-width:130px">
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="flex:1;height:7px;background:var(--gray-100);border-radius:4px;overflow:hidden">
                            <div style="width:{{ $persen }}%;height:100%;background:var(--blue)"></div>
                        </div>
                        <span style="font-size:.75rem;font-weight:700;color:var(--navy)">{{ $persen }}%</span>
                    </div>
                </td>
                <td style="font-weight:800;color:var(--blue)">Rp {{ number_format($l->total_penjualan,0,',','.') }}</td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state" style="padding:28px"><p>Belum ada data penjualan</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- REKAP PER MENU -->
<div class="card">
    <div class="card-header"><h3>☕ Rekap Penjualan per Menu</h3></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Nama Menu</th><th>Kategori</th><th>Qty Terjual</th><th>Total Pendapatan</th></tr></thead>
            <tbody>
            @forelse($rekapMenu as $i => $m)
            <tr>
                <td style="color:var(--gray-400)">{{ $i + 1 }}</td>
                <td><strong>{{ $m->nama_menu }}</strong></td>
                <td><span class="badge badge-blue">{{ ucfirst($m->kategori) }}</span></td>
                <td>{{ number_format($m->total_qty) }}</td>
                <td style="font-weight:700;color:var(--navy)">Rp {{ number_format($m->total_pendapatan,0,',','.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--gray-400)">Belum ada penjualan menu pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const rlLabels = {!! json_encode($rekapLokasi->pluck('nama_lokasi')->map(fn($n) => Str::limit($n, 14))) !!};
const rlData   = {!! json_encode($rekapLokasi->pluck('total_penjualan')) !!};
new Chart(document.getElementById('chartRekapLokasi').getContext('2d'), {
    type: 'bar',
    data: {
        labels: rlLabels,
        datasets: [{
            data: rlData,
            backgroundColor: '#1a56db',
            borderRadius: 6,
            barThickness: 30
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'Rp' + Math.round(v / 1000) + 'K', font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});
</script>
@endpush

