<?php
namespace App\Http\Controllers\Driver;
use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Rute};
use Carbon\Carbon;

class DashboardController extends Controller {
    public function index() {
        $driver  = auth()->user();
        $today   = Carbon::today();

        $kunjunganAktif = $driver->kunjunganAktif()->with('lokasiMangkal')->first();
        $ruteHariIni    = Rute::where('driver_id', $driver->id)
            ->where('tanggal_berlaku', $today)
            ->where('status','aktif')
            ->with(['details.lokasiMangkal'])
            ->first();

        $riwayatSingkat = Kunjungan::where('driver_id', $driver->id)
            ->where('status','selesai')
            ->with(['lokasiMangkal','sales'])
            ->orderByDesc('waktu_mulai')
            ->limit(5)->get();

        $totalPenjualanHariIni = Kunjungan::where('driver_id', $driver->id)
            ->whereDate('waktu_mulai', $today)
            ->join('sales','sales.kunjungan_id','=','kunjungan.id')
            ->where('sales.status','valid')
            ->sum('sales.total');

        return view('driver.dashboard', compact(
            'kunjunganAktif','ruteHariIni','riwayatSingkat','totalPenjualanHariIni'
        ));
    }
}
