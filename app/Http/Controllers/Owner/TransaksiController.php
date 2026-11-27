<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\{Sale, Kunjungan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller {
    public function index(Request $r) {
        $dari   = $r->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $r->input('sampai', Carbon::now()->format('Y-m-d'));

        $query = Sale::with(['kunjungan.lokasiMangkal','kunjungan.driver','details.menu'])
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$dari, $sampai]);

        if ($r->filled('status')) $query->where('status', $r->status);

        $transaksi     = $query->orderByDesc('created_at')->paginate(20);
        $totalPenjualan= Sale::where('status','valid')->whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])->sum('total');
        $totalTranCount= Sale::where('status','valid')->whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])->count();
        $dibatalkan    = Sale::where('status','dibatalkan')->whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])->count();
        $kunjunganAktif= Kunjungan::where('status','aktif')->with(['lokasiMangkal','sales'])->first();

        $byPayment = DB::table('sales')
            ->where('status','valid')
            ->whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->select('metode_pembayaran', DB::raw('SUM(total) as total'))
            ->groupBy('metode_pembayaran')
            ->pluck('total','metode_pembayaran')
            ->toArray();

        return view('owner.transaksi.index', compact(
            'transaksi','totalPenjualan','totalTranCount','dibatalkan',
            'kunjunganAktif','byPayment','dari','sampai'
        ));
    }

    public function show(Sale $sale) {
        $sale->load(['kunjungan.lokasiMangkal','kunjungan.driver','details.menu']);
        return view('owner.transaksi.show', compact('sale'));
    }

    public function cancel(Sale $sale) {
        abort_unless($sale->status === 'valid', 400, 'Transaksi sudah dibatalkan.');
        $sale->update(['status' => 'dibatalkan']);
        return back()->with('success','Transaksi berhasil dibatalkan.');
    }
}
