<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\{Sale, Kunjungan, LokasiMangkal};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller {
    public function index(Request $r) {
        $dari     = $r->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai   = $r->input('sampai', Carbon::now()->format('Y-m-d'));
        $lokasiId = $r->input('lokasi_id');
        $daftarLokasi = LokasiMangkal::orderBy('nama_lokasi')->get();

        // Per lokasi
        $perLokasi = DB::table('kunjungan')
            ->join('sales','sales.kunjungan_id','=','kunjungan.id')
            ->join('lokasi_mangkal','lokasi_mangkal.id','=','kunjungan.lokasi_mangkal_id')
            ->where('sales.status','valid')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$dari, $sampai])
            ->when($lokasiId, fn($q) => $q->where('kunjungan.lokasi_mangkal_id', $lokasiId))
            ->groupBy('lokasi_mangkal.id','lokasi_mangkal.nama_lokasi')
            ->select(
                'lokasi_mangkal.nama_lokasi',
                DB::raw('SUM(sales.total) as total_penjualan'),
                DB::raw('COUNT(sales.id) as jumlah_transaksi'),
                DB::raw('COUNT(DISTINCT kunjungan.id) as jumlah_kunjungan')
            )
            ->orderByDesc('total_penjualan')
            ->get();

        // Per jam
        $perJam = DB::table('sales')
            ->join('kunjungan','kunjungan.id','=','sales.kunjungan_id')
            ->where('sales.status','valid')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$dari, $sampai])
            ->when($lokasiId, fn($q) => $q->where('kunjungan.lokasi_mangkal_id', $lokasiId))
            ->select(
                DB::raw('HOUR(sales.created_at) as jam'),
                DB::raw('SUM(sales.total) as total_penjualan'),
                DB::raw('COUNT(sales.id) as jumlah_transaksi')
            )
            ->groupBy(DB::raw('HOUR(sales.created_at)'))
            ->orderBy('jam')
            ->get();

        // Ringkasan
        $salesBase = DB::table('sales')
            ->join('kunjungan','kunjungan.id','=','sales.kunjungan_id')
            ->where('sales.status','valid')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$dari, $sampai])
            ->when($lokasiId, fn($q) => $q->where('kunjungan.lokasi_mangkal_id', $lokasiId));

        $totalPenjualan = (clone $salesBase)->sum('sales.total');
        $totalTransaksi = (clone $salesBase)->count('sales.id');
        $totalKunjungan = Kunjungan::whereBetween(DB::raw('DATE(waktu_mulai)'), [$dari, $sampai])
            ->when($lokasiId, fn($q) => $q->where('lokasi_mangkal_id', $lokasiId))
            ->count();

        // Chart data per hari
        $chartHarian = DB::table('sales')
            ->join('kunjungan','kunjungan.id','=','sales.kunjungan_id')
            ->where('sales.status','valid')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$dari, $sampai])
            ->when($lokasiId, fn($q) => $q->where('kunjungan.lokasi_mangkal_id', $lokasiId))
            ->select(DB::raw('DATE(sales.created_at) as tanggal'), DB::raw('SUM(sales.total) as total'))
            ->groupBy(DB::raw('DATE(sales.created_at)'))
            ->orderBy('tanggal')
            ->get();

        $chartLabels = $chartHarian->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $chartData   = $chartHarian->pluck('total')->toArray();
        $jamLabels   = $perJam->pluck('jam')->map(fn($j) => sprintf('%02d:00', $j))->toArray();
        $jamData     = $perJam->pluck('total_penjualan')->toArray();

        return view('owner.laporan.index', compact(
            'dari','sampai','lokasiId','daftarLokasi','perLokasi','perJam','totalPenjualan','totalTransaksi',
            'totalKunjungan','chartLabels','chartData','jamLabels','jamData'
        ));
    }
}
