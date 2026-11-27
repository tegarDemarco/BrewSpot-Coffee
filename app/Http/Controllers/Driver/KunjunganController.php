<?php
namespace App\Http\Controllers\Driver;
use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, LokasiMangkal, Rute, RuteDetail, Menu, Sale, SaleDetail, AppNotification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KunjunganController extends Controller {
    public function mulai(Request $r) {
        $driver = auth()->user();
        // AB-04: hanya satu kunjungan aktif
        if ($driver->kunjunganAktif) {
            return back()->withErrors(['error' => 'Anda masih memiliki kunjungan aktif. Selesaikan terlebih dahulu.']);
        }
        $r->validate(['lokasi_mangkal_id' => 'required|exists:lokasi_mangkal,id']);
        $kunjungan = Kunjungan::create([
            'driver_id'        => $driver->id,
            'lokasi_mangkal_id'=> $r->lokasi_mangkal_id,
            'rute_id'          => $r->rute_id,
            'rute_detail_id'   => $r->rute_detail_id,
            'waktu_mulai'      => now(),
            'status'           => 'aktif',
        ]);
        $lokasi = LokasiMangkal::find($r->lokasi_mangkal_id);
        AppNotification::notifyOwners(
            'Driver Mulai Mangkal',
            "{$driver->name} mulai mangkal di {$lokasi->nama_lokasi}.",
            'info',
            '📍',
            route('owner.kunjungan.show', $kunjungan)
        );
        return redirect()->route('driver.kunjungan.aktif')->with('success','Mangkal dimulai!');
    }

    public function aktif() {
        $kunjungan = auth()->user()->kunjunganAktif()->with(['lokasiMangkal','sales.details.menu'])->first();
        $menus     = Menu::aktif()->orderBy('kategori')->orderBy('nama_menu')->get();
        return view('driver.kunjungan.aktif', compact('kunjungan','menus'));
    }

    public function selesai(Kunjungan $kunjungan) {
        abort_unless($kunjungan->driver_id === auth()->id() && $kunjungan->status === 'aktif', 403);
        $kunjungan->update(['waktu_selesai' => now(), 'status' => 'selesai']);
        AppNotification::notifyOwners(
            'Kunjungan Selesai',
            auth()->user()->name . " telah menyelesaikan mangkal di {$kunjungan->lokasiMangkal->nama_lokasi}.",
            'success',
            '✅',
            route('owner.kunjungan.show', $kunjungan)
        );
        return redirect()->route('driver.dashboard')->with('success','Kunjungan selesai!');
    }

    public function inputTransaksi(Request $r) {
        $driver    = auth()->user();
        $kunjungan = $driver->kunjunganAktif;
        if (!$kunjungan) {
            return back()->withErrors(['error' => 'Tidak ada kunjungan aktif.']);
        }
        $r->validate([
            'items'                   => 'required|array|min:1',
            'items.*.menu_id'         => 'required|exists:menus,id',
            'items.*.qty'             => 'required|integer|min:1',
            'metode_pembayaran'       => 'required|in:tunai,transfer,qris,lainnya',
        ]);

        DB::transaction(function() use ($r, $kunjungan) {
            $total = 0;
            $details = [];
            foreach ($r->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $subtotal  = $menu->harga * $item['qty'];
                $total    += $subtotal;
                $details[] = ['menu_id' => $menu->id, 'qty' => $item['qty'], 'harga_satuan' => $menu->harga, 'subtotal' => $subtotal];
            }
            $sale = Sale::create([
                'kunjungan_id'      => $kunjungan->id,
                'total'             => $total,
                'metode_pembayaran' => $r->metode_pembayaran,
                'status'            => 'valid',
                'catatan'           => $r->catatan,
            ]);
            foreach ($details as $d) {
                $d['sale_id'] = $sale->id;
                SaleDetail::create($d);
            }
            AppNotification::notifyOwners(
                'Transaksi Baru',
                auth()->user()->name . ' mencatat penjualan Rp ' . number_format($total, 0, ',', '.') . " di {$kunjungan->lokasiMangkal->nama_lokasi}.",
                'success',
                '💰',
                route('owner.transaksi.show', $sale)
            );
        });

        return back()->with('success','Transaksi berhasil dicatat!');
    }

    public function riwayat() {
        $kunjungan = Kunjungan::where('driver_id', auth()->id())
            ->with(['lokasiMangkal','sales'])
            ->orderByDesc('waktu_mulai')
            ->paginate(15);
        return view('driver.kunjungan.riwayat', compact('kunjungan'));
    }

    public function showKunjungan(Kunjungan $kunjungan) {
        abort_unless($kunjungan->driver_id === auth()->id(), 403);
        $kunjungan->load(['lokasiMangkal','sales.details.menu']);
        return view('driver.kunjungan.show', compact('kunjungan'));
    }
}
