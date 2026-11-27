<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\LokasiMangkal;
use Illuminate\Http\Request;

class LokasiController extends Controller {
    public function index() {
        $lokasi = LokasiMangkal::orderByDesc('created_at')->paginate(10);
        return view('owner.lokasi.index', compact('lokasi'));
    }
    public function create() { return view('owner.lokasi.create'); }
    public function store(Request $r) {
        $r->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat'      => 'required|string',
            'deskripsi'   => 'nullable|string',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
        ]);
        LokasiMangkal::create($r->only('nama_lokasi','alamat','deskripsi','latitude','longitude') + ['is_active'=>true]);
        return redirect()->route('owner.lokasi.index')->with('success','Lokasi berhasil ditambahkan.');
    }
    public function edit(LokasiMangkal $lokasi) { return view('owner.lokasi.edit', compact('lokasi')); }
    public function update(Request $r, LokasiMangkal $lokasi) {
        $r->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat'      => 'required|string',
            'deskripsi'   => 'nullable|string',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
        ]);
        $lokasi->update($r->only('nama_lokasi','alamat','deskripsi','latitude','longitude','is_active'));
        return redirect()->route('owner.lokasi.index')->with('success','Lokasi berhasil diperbarui.');
    }
    public function toggleStatus(LokasiMangkal $lokasi) {
        $lokasi->update(['is_active' => !$lokasi->is_active]);
        return back()->with('success','Status lokasi diperbarui.');
    }
}
