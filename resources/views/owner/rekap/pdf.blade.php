<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Penjualan BrewSpot ({{ $dari }} s/d {{ $sampai }})</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #0f172a; line-height: 1.45; padding: 24px; }
        .header { border-bottom: 2px solid #0d1b3e; padding-bottom: 12px; margin-bottom: 18px; display: table; width: 100%; }
        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; text-align: right; vertical-align: middle; font-size: 10px; color: #475569; }
        .brand { font-size: 20px; font-weight: bold; color: #0d1b3e; letter-spacing: 0.5px; }
        .sub { font-size: 10px; color: #1a56db; margin-top: 2px; }
        .meta { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; font-size: 10px; }
        .meta strong { color: #0d1b3e; }
        .summary-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin: 0 -8px 18px -8px; }
        .summary-box { background: #f1f5f9; border-left: 4px solid #1a56db; padding: 10px 12px; border-radius: 4px; width: 25%; }
        .summary-label { font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: bold; }
        .summary-value { font-size: 14px; font-weight: bold; color: #0d1b3e; margin-top: 3px; }
        h2 { font-size: 13px; color: #0d1b3e; margin: 16px 0 8px; border-left: 3px solid #1a56db; padding-left: 8px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.data th { background: #0d1b3e; color: #ffffff; padding: 7px 8px; text-align: left; font-size: 9.5px; text-transform: uppercase; }
        table.data td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        table.data tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .blue { color: #1a56db; font-weight: bold; }
        .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #cbd5e1; font-size: 9px; color: #64748b; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="brand">BREWSPOT</div>
            <div class="sub">Laporan Rekap Penjualan Kopi Keliling</div>
        </div>
        <div class="header-right">
            Dicetak: {{ now()->format('d M Y, H:i') }}<br>
            Oleh: {{ auth()->user()->name ?? 'Pemilik Usaha' }}
        </div>
    </div>

    <div class="meta">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Lokasi:</strong> {{ $lokasiTerpilih ? $lokasiTerpilih->nama_lokasi : 'Semua Lokasi' }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Driver:</strong> {{ $driverTerpilih ? $driverTerpilih->name : 'Semua Driver' }}
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-box">
                <div class="summary-label">Total Penjualan</div>
                <div class="summary-value">Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
            </td>
            <td class="summary-box">
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">{{ number_format($totalTransaksi) }}</div>
            </td>
            <td class="summary-box">
                <div class="summary-label">Total Kunjungan</div>
                <div class="summary-value">{{ number_format($totalKunjungan) }}x</div>
            </td>
            <td class="summary-box">
                <div class="summary-label">Rata-rata / Transaksi</div>
                <div class="summary-value">Rp {{ number_format($rataRata,0,',','.') }}</div>
            </td>
        </tr>
    </table>

    <h2>Rekap Penjualan Berdasarkan Lokasi</h2>
    <table class="data">
        <thead>
            <tr>
                <th class="text-center" style="width:28px">#</th>
                <th>Nama Lokasi</th>
                <th class="text-center">Kunjungan</th>
                <th class="text-center">Transaksi</th>
                <th class="text-right">Rata-rata / Kunjungan</th>
                <th class="text-right">Kontribusi</th>
                <th class="text-right">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
        @forelse($rekapLokasi as $i => $l)
            @php
                $persen = $totalPenjualan > 0 ? round(($l->total_penjualan / $totalPenjualan) * 100, 1) : 0;
                $avgKunj = $l->jumlah_kunjungan > 0 ? $l->total_penjualan / $l->jumlah_kunjungan : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td><span class="bold">{{ $l->nama_lokasi }}</span><br><span style="font-size:8.5px;color:#64748b">{{ $l->alamat }}</span></td>
                <td class="text-center">{{ $l->jumlah_kunjungan }}x</td>
                <td class="text-center">{{ $l->jumlah_transaksi }}</td>
                <td class="text-right">Rp {{ number_format($avgKunj,0,',','.') }}</td>
                <td class="text-right">{{ $persen }}%</td>
                <td class="text-right blue">Rp {{ number_format($l->total_penjualan,0,',','.') }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Tidak ada data penjualan pada periode ini.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Rekap Penjualan per Menu</h2>
    <table class="data">
        <thead>
            <tr>
                <th class="text-center" style="width:28px">#</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th class="text-center">Qty Terjual</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
        @forelse($rekapMenu as $i => $m)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="bold">{{ $m->nama_menu }}</td>
                <td>{{ ucfirst($m->kategori) }}</td>
                <td class="text-center">{{ number_format($m->total_qty) }}</td>
                <td class="text-right bold">Rp {{ number_format($m->total_pendapatan,0,',','.') }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center">Tidak ada data menu terjual.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Ringkasan Metode Pembayaran</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Metode Pembayaran</th>
                <th class="text-center">Jumlah Transaksi</th>
                <th class="text-right">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
        @forelse($rekapMetode as $m)
            <tr>
                <td class="bold" style="text-transform:uppercase">{{ $m->metode_pembayaran }}</td>
                <td class="text-center">{{ $m->jumlah_transaksi }}</td>
                <td class="text-right blue">Rp {{ number_format($m->total_penjualan,0,',','.') }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center">Tidak ada transaksi.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="footer">
        BrewSpot Coffee | on the move — Dokumen Rekap Penjualan Resmi
    </div>
</body>
</html>

