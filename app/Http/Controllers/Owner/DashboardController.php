<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Sale, LokasiMangkal, User, Rute};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller {
    public function index() {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $totalPenjualanBulanIni = Sale::where('status','valid')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->sum('total');

        $totalTransaksiBulanIni = Sale::where('status','valid')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $kunjunganAktif = Kunjungan::where('status','aktif')->count();
        $totalDriver    = User::where('role','driver')->where('is_active',true)->count();
        $totalLokasi    = LokasiMangkal::where('is_active',true)->count();

        $lokasiteratas = DB::table('kunjungan')
            ->join('sales','sales.kunjungan_id','=','kunjungan.id')
            ->join('lokasi_mangkal','lokasi_mangkal.id','=','kunjungan.lokasi_mangkal_id')
            ->where('sales.status','valid')
            ->whereMonth('sales.created_at', $today->month)
            ->groupBy('lokasi_mangkal.id','lokasi_mangkal.nama_lokasi')
            ->select('lokasi_mangkal.nama_lokasi', DB::raw('SUM(sales.total) as total_penjualan'), DB::raw('COUNT(sales.id) as jumlah_transaksi'))
            ->orderByDesc('total_penjualan')
            ->limit(5)
            ->get();

        $recentKunjungan = Kunjungan::with(['driver','lokasiMangkal'])
            ->orderByDesc('created_at')->limit(8)->get();

        // Grafik 7 hari terakhir
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[]   = Sale::where('status','valid')
                ->whereDate('created_at', $date)->sum('total');
        }

        return view('owner.dashboard', compact(
            'totalPenjualanBulanIni','totalTransaksiBulanIni','kunjunganAktif',
            'totalDriver','totalLokasi','lokasiteratas','recentKunjungan',
            'chartLabels','chartData'
        ));
    }
}
