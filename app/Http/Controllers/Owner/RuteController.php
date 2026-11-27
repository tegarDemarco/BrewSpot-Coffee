<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\{Rute, RuteDetail, User, LokasiMangkal, AppNotification};
use Illuminate\Http\Request;

class RuteController extends Controller {
    public function index() {
        $rute = Rute::with(['driver','details.lokasiMangkal'])->orderByDesc('tanggal_berlaku')->paginate(10);
        return view('owner.rute.index', compact('rute'));
    }
    public function create() {
        $drivers = User::where('role','driver')->where('is_active',true)->get();
        $lokasi  = LokasiMangkal::where('is_active',true)->orderBy('nama_lokasi')->get();
        return view('owner.rute.create', compact('drivers','lokasi'));
    }
    public function store(Request $r) {
        $r->validate([
            'nama_rute'       => 'required|string|max:255',
            'driver_id'       => 'required|exists:users,id',
            'tanggal_berlaku' => 'required|date',
            'catatan'         => 'nullable|string',
            'lokasi_ids'      => 'required|array|min:1',
            'lokasi_ids.*'    => 'exists:lokasi_mangkal,id',
        ]);
        $rute = Rute::create([
            'owner_id'       => auth()->id(),
            'driver_id'      => $r->driver_id,
            'nama_rute'      => $r->nama_rute,
            'tanggal_berlaku'=> $r->tanggal_berlaku,
            'status'         => 'aktif',
            'catatan'        => $r->catatan,
        ]);
        foreach ($r->lokasi_ids as $idx => $lokasiId) {
            RuteDetail::create([
                'rute_id'           => $rute->id,
                'lokasi_mangkal_id' => $lokasiId,
                'urutan'            => $idx + 1,
                'jam_rencana_mulai' => $r->jam_mulai[$idx] ?? null,
                'jam_rencana_selesai'=> $r->jam_selesai[$idx] ?? null,
            ]);
        }
        AppNotification::notifyUser(
            (int) $r->driver_id,
            'Rute Baru Ditugaskan',
            "Anda mendapat rute baru: {$rute->nama_rute} (" . count($r->lokasi_ids) . " lokasi) untuk tanggal {$rute->tanggal_berlaku->format('d M Y')}.",
            'info',
            '🗺️',
            route('driver.rute.show', $rute)
        );
        return redirect()->route('owner.rute.index')->with('success','Rute berhasil dibuat.');
    }
    public function show(Rute $rute) {
        $rute->load(['driver','owner','details.lokasiMangkal','kunjungan.driver','kunjungan.sales']);
        return view('owner.rute.show', compact('rute'));
    }
    public function destroy(Rute $rute) {
        $rute->delete();
        return redirect()->route('owner.rute.index')->with('success','Rute dihapus.');
    }
}
