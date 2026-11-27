<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\{LokasiMangkal, User, Kunjungan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapController extends Controller {
    private function buildRekapData(Request $r): array {
        $dari     = $r->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai   = $r->input('sampai', Carbon::now()->format('Y-m-d'));
        $lokasiId = $r->input('lokasi_id');
        $driverId = $r->input('driver_id');

        $daftarLokasi = LokasiMangkal::orderBy('nama_lokasi')->get();
        $daftarDriver = User::where('role', 'driver')->orderBy('name')->get();

        // Rekap per lokasi
        $rekapLokasi = DB::table('lokasi_mangkal')
            ->leftJoin('kunjungan', function ($join) use ($dari, $sampai, $driverId) {
                $join->on('kunjungan.lokasi_mangkal_id', '=', 'lokasi_mangkal.id')
                     ->whereBetween(DB::raw('DATE(kunjungan.waktu_mulai)'), [$dari, $sampai]);
                if ($driverId) {
                    $join->where('kunjungan.driver_id', '=', $driverId);
                }
            })
            ->leftJoin('sales', function ($join) {
                $join->on('sales.kunjungan_id', '=', 'kunjungan.id')
                     ->where('sales.status', '=', 'valid');
            })
            ->when($lokasiId, fn($q) => $q->where('lokasi_mangkal.id', $lokasiId))
            ->groupBy('lokasi_mangkal.id', 'lokasi_mangkal.nama_lokasi', 'lokasi_mangkal.alamat')
            ->select(
                'lokasi_mangkal.id',
                'lokasi_mangkal.nama_lokasi',
                'lokasi_mangkal.alamat',
                DB::raw('COUNT(DISTINCT kunjungan.id) as jumlah_kunjungan'),
                DB::raw('COUNT(sales.id) as jumlah_transaksi'),
                DB::raw('COALESCE(SUM(sales.total), 0) as total_penjualan')
            )
            ->orderByDesc('total_penjualan')
            ->get();

        $totalPenjualan = $rekapLokasi->sum('total_penjualan');
        $totalTransaksi = $rekapLokasi->sum('jumlah_transaksi');
        $totalKunjungan = $rekapLokasi->sum('jumlah_kunjungan');
        $rataRata       = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;

        // Rekap per menu
        $rekapMenu = DB::table('sale_details')
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->join('kunjungan', 'kunjungan.id', '=', 'sales.kunjungan_id')
            ->join('menus', 'menus.id', '=', 'sale_details.menu_id')
            ->where('sales.status', 'valid')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$dari, $sampai])
            ->when($lokasiId, fn($q) => $q->where('kunjungan.lokasi_mangkal_id', $lokasiId))
            ->when($driverId, fn($q) => $q->where('kunjungan.driver_id', $driverId))
            ->groupBy('menus.id', 'menus.nama_menu', 'menus.kategori')
            ->select(
                'menus.nama_menu',
                'menus.kategori',
                DB::raw('SUM(sale_details.qty) as total_qty'),
                DB::raw('SUM(sale_details.subtotal) as total_pendapatan')
            )
            ->orderByDesc('total_pendapatan')
            ->get();

        // Rekap per metode pembayaran
        $rekapMetode = DB::table('sales')
            ->join('kunjungan', 'kunjungan.id', '=', 'sales.kunjungan_id')
            ->where('sales.status', 'valid')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$dari, $sampai])
            ->when($lokasiId, fn($q) => $q->where('kunjungan.lokasi_mangkal_id', $lokasiId))
            ->when($driverId, fn($q) => $q->where('kunjungan.driver_id', $driverId))
            ->groupBy('sales.metode_pembayaran')
            ->select(
                'sales.metode_pembayaran',
                DB::raw('COUNT(sales.id) as jumlah_transaksi'),
                DB::raw('SUM(sales.total) as total_penjualan')
            )
            ->orderByDesc('total_penjualan')
            ->get();

        $lokasiTerpilih = $lokasiId ? LokasiMangkal::find($lokasiId) : null;
        $driverTerpilih = $driverId ? User::find($driverId) : null;

        return compact(
            'dari', 'sampai', 'lokasiId', 'driverId',
            'daftarLokasi', 'daftarDriver',
            'rekapLokasi', 'rekapMenu', 'rekapMetode',
            'totalPenjualan', 'totalTransaksi', 'totalKunjungan', 'rataRata',
            'lokasiTerpilih', 'driverTerpilih'
        );
    }

    public function index(Request $r) {
        $data = $this->buildRekapData($r);
        return view('owner.rekap.index', $data);
    }

    public function pdf(Request $r) {
        $data = $this->buildRekapData($r);
        $pdf = Pdf::loadView('owner.rekap.pdf', $data)->setPaper('a4', 'portrait');
        $filename = 'rekap-penjualan-brewspot-' . $data['dari'] . '-sd-' . $data['sampai'] . '.pdf';

        if ($r->boolean('preview')) {
            return $pdf->stream($filename);
        }
        return $pdf->download($filename);
    }
}

