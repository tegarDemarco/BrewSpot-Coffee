<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, User, LokasiMangkal, AppNotification};
use Illuminate\Http\Request;

class KunjunganController extends Controller {
    public function index(Request $r) {
        $query = Kunjungan::with(['driver','lokasiMangkal','sales']);
        if ($r->filled('driver_id'))    $query->where('driver_id', $r->driver_id);
        if ($r->filled('status'))       $query->where('status', $r->status);
        if ($r->filled('tanggal'))      $query->whereDate('waktu_mulai', $r->tanggal);
        $kunjungan = $query->orderByDesc('waktu_mulai')->paginate(15);
        $drivers   = User::where('role','driver')->get();
        return view('owner.kunjungan.index', compact('kunjungan','drivers'));
    }
    public function show(Kunjungan $kunjungan) {
        $kunjungan->load(['driver','lokasiMangkal','rute','sales.details.menu']);
        return view('owner.kunjungan.show', compact('kunjungan'));
    }
    public function forceSelesai(Kunjungan $kunjungan) {
        abort_unless($kunjungan->status === 'aktif', 400);
        $kunjungan->update(['waktu_selesai' => now(), 'status' => 'selesai', 'catatan' => ($kunjungan->catatan ? $kunjungan->catatan . ' | ' : '') . 'Diselesaikan oleh pemilik']);
        AppNotification::notifyUser(
            (int) $kunjungan->driver_id,
            'Kunjungan Diselesaikan Pemilik',
            "Kunjungan Anda di {$kunjungan->lokasiMangkal->nama_lokasi} telah diselesaikan oleh pemilik usaha.",
            'warning',
            '⏹️',
            route('driver.kunjungan.show', $kunjungan)
        );
        return back()->with('success','Kunjungan berhasil diselesaikan.');
    }
}
