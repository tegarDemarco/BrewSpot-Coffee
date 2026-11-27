@extends('layouts.app')
@section('title','Kunjungan Aktif')
@section('page-title','Kunjungan Aktif')
@section('page-sub','Kelola transaksi sesi mangkal')
@section('content')
@if(!$kunjungan)
<div class="alert alert-warning">⚠️ Tidak ada kunjungan aktif. <a href="{{ route('driver.rute.index') }}" style="font-weight:700">Lihat rute →</a></div>
@else
<!-- Header info -->
<div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap">
    <div class="card" style="flex:2;min-width:220px">
        <div class="card-body" style="display:flex;align-items:center;gap:14px">
            <div style="width:48px;height:48px;background:#d1fae5;border-radius:12px;display:grid;place-items:center;font-size:1.4rem">📍</div>
            <div>
                <div style="font-size:.68rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em">Lokasi Aktif</div>
                <div style="font-weight:800;font-size:1rem">{{ $kunjungan->lokasiMangkal->nama_lokasi }}</div>
                <span class="badge badge-green" style="margin-top:3px">Sedang Mangkal</span>
            </div>
        </div>
    </div>
    <div class="card" style="flex:1;min-width:160px">
        <div class="card-body">
            <div style="font-size:.68rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Total Penjualan</div>
            <div style="font-size:1.5rem;font-weight:800;color:var(--blue)">Rp {{ number_format($kunjungan->sales->where('status','valid')->sum('total'),0,',','.') }}</div>
        </div>
    </div>
    <div class="card" style="flex:1;min-width:140px">
        <div class="card-body">
            <div style="font-size:.68rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Mulai Mangkal</div>
            <div style="font-size:1.3rem;font-weight:800;color:var(--navy)">{{ $kunjungan->waktu_mulai->format('H:i') }}</div>
        </div>
    </div>
</div>

<div class="grid-col-6-6">
    <!-- INPUT TRANSAKSI -->
    <div class="card">
        <div class="card-header"><h3>➕ Transaksi Baru</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('driver.kunjungan.transaksi') }}" id="form-transaksi">
                @csrf
                <div id="items-container"></div>
                <button type="button" onclick="addItem()" class="btn btn-outline" style="width:100%;margin-bottom:16px;border-style:dashed">➕ Tambah Item</button>
                <div class="form-group">
                    <label class="form-label">Metode Pembayaran</label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        @foreach(['tunai'=>'💵 Tunai','qris'=>'📱 QRIS','transfer'=>'🏦 Transfer','lainnya'=>'🔄 Lainnya'] as $val => $label)
                        <label style="display:flex;align-items:center;gap:8px;padding:9px 12px;border:1.5px solid var(--gray-200);border-radius:9px;cursor:pointer;font-size:.82rem;transition:.15s" onmouseover="this.style.borderColor='var(--blue)'" onmouseout="this.style.borderColor=''">
                            <input type="radio" name="metode_pembayaran" value="{{ $val }}" {{ $val==='tunai'?'checked':'' }} style="accent-color:var(--navy)"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <input type="text" name="catatan" class="form-control" placeholder="cth: pesanan khusus...">
                </div>
                <div style="background:var(--gray-50);border-radius:10px;padding:12px 14px;margin-bottom:14px;border:1px solid var(--gray-200)">
                    <div style="display:flex;justify-content:space-between;font-size:.85rem;font-weight:700">
                        <span>Total Transaksi</span>
                        <span id="totalDisplay" style="color:var(--blue)">Rp 0</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">💾 Simpan Transaksi</button>
            </form>
        </div>
    </div>

    <!-- RIWAYAT SESI + SELESAI -->
    <div>
        <div class="card" style="margin-bottom:14px">
            <div class="card-header">
                <h3>🧾 Transaksi Sesi Ini</h3>
                <span class="badge badge-blue">{{ $kunjungan->sales->count() }} transaksi</span>
            </div>
            <div style="max-height:380px;overflow-y:auto">
                @forelse($kunjungan->sales as $s)
                <div style="padding:12px 16px;border-bottom:1px solid var(--gray-100)">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <div>
                            <span style="font-size:.72rem;color:var(--gray-400)">{{ $s->created_at->format('H:i') }}</span>
                            <span class="badge badge-{{ $s->status==='valid'?'green':'red' }}" style="margin-left:6px">{{ $s->status }}</span>
                        </div>
                        <span style="font-size:.8rem;font-weight:700;color:var(--blue)">Rp {{ number_format($s->total,0,',','.') }}</span>
                    </div>
                    @foreach($s->details as $d)
                    <div style="font-size:.78rem;display:flex;justify-content:space-between;color:var(--gray-700)">
                        <span>{{ $d->menu->nama_menu ?? '–' }} x{{ $d->qty }}</span>
                        <span>Rp {{ number_format($d->subtotal,0,',','.') }}</span>
                    </div>
                    @endforeach
                </div>
                @empty
                <div class="empty-state" style="padding:30px"><div class="ei">🧾</div><p>Belum ada transaksi</p></div>
                @endforelse
            </div>
        </div>
        <form method="POST" action="{{ route('driver.kunjungan.selesai',$kunjungan) }}">
            @csrf
            <button class="btn btn-danger" style="width:100%;padding:13px;font-size:.9rem" onclick="return confirm('Selesaikan kunjungan di {{ $kunjungan->lokasiMangkal->nama_lokasi }}?')">
                ⏹ Selesai Mangkal
            </button>
        </form>
    </div>
</div>
@endif
@endsection
@push('scripts')
<script>
const menus = {!! json_encode($menus->map(fn($m)=>['id'=>$m->id,'nama'=>$m->nama_menu,'harga'=>$m->harga,'kategori'=>$m->kategori])->values()) !!};
let ic = 0;
function fmt(n) { return 'Rp ' + Number(n).toLocaleString('id'); }
function addItem() {
    ic++;
    const opts = menus.map(m=>`<option value="${m.id}" data-harga="${m.harga}">${m.nama} (${m.kategori}) – ${fmt(m.harga)}</option>`).join('');
    document.getElementById('items-container').insertAdjacentHTML('beforeend',`
    <div class="item-row" style="border:1.5px solid var(--gray-200);border-radius:10px;padding:12px;margin-bottom:10px;position:relative">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <span style="font-size:.78rem;font-weight:700;color:var(--blue)">Item ${ic}</span>
            <button type="button" onclick="this.closest('.item-row').remove();calcTotal()" style="width:22px;height:22px;background:#fee2e2;border:none;border-radius:5px;cursor:pointer;font-size:.75rem;color:#991b1b;display:grid;place-items:center">✕</button>
        </div>
        <select name="items[${ic}][menu_id]" class="form-control" onchange="calcTotal()" style="margin-bottom:8px" required>
            <option value="">-- Pilih Menu --</option>${opts}
        </select>
        <div style="display:flex;align-items:center;gap:8px">
            <label style="font-size:.78rem;white-space:nowrap;margin:0;font-weight:600">Qty</label>
            <input type="number" name="items[${ic}][qty]" class="form-control" value="1" min="1" style="width:80px" onchange="calcTotal()" oninput="calcTotal()" required>
            <span class="item-subtotal" style="font-size:.8rem;font-weight:600;color:var(--blue);margin-left:auto">Rp 0</span>
        </div>
    </div>`);
}
function calcTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const sel = row.querySelector('select'); const qty = row.querySelector('input[type=number]');
        if (sel && sel.value && qty) {
            const harga = parseFloat(sel.options[sel.selectedIndex]?.dataset?.harga || 0);
            const sub = harga * parseInt(qty.value || 1);
            total += sub;
            const sp = row.querySelector('.item-subtotal');
            if (sp) sp.textContent = 'Rp ' + sub.toLocaleString('id');
        }
    });
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id');
}
addItem();
</script>
@endpush
