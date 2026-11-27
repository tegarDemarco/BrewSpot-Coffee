<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\{BahanBaku, AppNotification};
use Illuminate\Http\Request;

class BahanBakuController extends Controller {
    public function index() {
        $bahanBaku  = BahanBaku::orderBy('nama_bahan')->paginate(15);
        $stokRendah = BahanBaku::whereRaw('stok <= stok_minimum')->count();
        $stokCukup  = BahanBaku::whereRaw('stok > stok_minimum')->count();
        return view('owner.bahanbaku.index', compact('bahanBaku','stokRendah','stokCukup'));
    }
    public function create() { return view('owner.bahanbaku.create'); }
    public function store(Request $r) {
        $r->validate([
            'nama_bahan'   => 'required|string|max:255',
            'stok'         => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'satuan'       => 'required|string|max:50',
            'harga_satuan' => 'nullable|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ]);
        $bahan = BahanBaku::create($r->only('nama_bahan','stok','stok_minimum','satuan','harga_satuan','keterangan'));
        if ($bahan->is_low) {
            AppNotification::notifyOwners(
                'Stok Bahan Baku Menipis',
                "Stok {$bahan->nama_bahan} tersisa {$bahan->stok} {$bahan->satuan} (minimum {$bahan->stok_minimum} {$bahan->satuan}).",
                'warning',
                '⚠️',
                route('owner.bahanbaku.edit', $bahan)
            );
        }
        return redirect()->route('owner.bahanbaku.index')->with('success','Bahan baku berhasil ditambahkan.');
    }
    public function edit(BahanBaku $bahanbaku) {
        return view('owner.bahanbaku.edit', ['bahanBaku' => $bahanbaku]);
    }
    public function update(Request $r, BahanBaku $bahanbaku) {
        $r->validate([
            'nama_bahan'   => 'required|string|max:255',
            'stok'         => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'satuan'       => 'required|string|max:50',
            'harga_satuan' => 'nullable|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ]);
        $bahanbaku->update($r->only('nama_bahan','stok','stok_minimum','satuan','harga_satuan','keterangan'));
        if ($bahanbaku->fresh()->is_low) {
            AppNotification::notifyOwners(
                'Stok Bahan Baku Menipis',
                "Stok {$bahanbaku->nama_bahan} tersisa {$bahanbaku->stok} {$bahanbaku->satuan} (minimum {$bahanbaku->stok_minimum} {$bahanbaku->satuan}).",
                'warning',
                '⚠️',
                route('owner.bahanbaku.edit', $bahanbaku)
            );
        }
        return redirect()->route('owner.bahanbaku.index')->with('success','Bahan baku diperbarui.');
    }
    public function destroy(BahanBaku $bahanbaku) {
        $bahanbaku->delete();
        return redirect()->route('owner.bahanbaku.index')->with('success','Bahan baku dihapus.');
    }
}
