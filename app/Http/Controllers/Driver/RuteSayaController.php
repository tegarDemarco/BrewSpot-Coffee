<?php
namespace App\Http\Controllers\Driver;
use App\Http\Controllers\Controller;
use App\Models\Rute;
use Carbon\Carbon;

class RuteSayaController extends Controller {
    public function index() {
        $driver = auth()->user();
        $rute   = Rute::where('driver_id', $driver->id)
            ->with(['details.lokasiMangkal','details.kunjungan' => fn($q) => $q->where('driver_id',$driver->id)])
            ->orderByDesc('tanggal_berlaku')
            ->paginate(10);
        return view('driver.rute.index', compact('rute'));
    }
    public function show(Rute $rute) {
        abort_unless($rute->driver_id === auth()->id(), 403);
        $rute->load(['details.lokasiMangkal','details.kunjungan' => fn($q) => $q->where('driver_id',auth()->id())]);
        return view('driver.rute.show', compact('rute'));
    }
}
