@extends('layouts.app')
@section('title','Buat Rute')
@section('page-title','Buat Rute Baru')
@section('content')
<div class="page-header">
    <div><h1>Buat Rute Baru</h1><p>Susun urutan titik mangkal dan tetapkan ke driver</p></div>
    <a href="{{ route('owner.rute.index') }}" class="btn btn-outline">← Kembali</a>
</div>
<form method="POST" action="{{ route('owner.rute.store') }}">
    @csrf
    <div class="grid-2">
        <div class="card">
            <div class="card-header"><h3>📋 Informasi Rute</h3></div>
            <div class="card-body">
                <div class="form-group"><label class="form-label">Nama Rute *</label><input type="text" name="nama_rute" class="form-control" value="{{ old('nama_rute') }}" placeholder="cth: Rute Senin Pagi" required></div>
                <div class="form-group"><label class="form-label">Driver *</label>
                    <select name="driver_id" class="form-control" required>
                        <option value="">-- Pilih Driver --</option>
                        @foreach($drivers as $d) <option value="{{ $d->id }}" {{ old('driver_id')==$d->id?'selected':'' }}>{{ $d->name }}</option> @endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Tanggal Berlaku *</label><input type="date" name="tanggal_berlaku" class="form-control" value="{{ old('tanggal_berlaku', date('Y-m-d')) }}" required></div>
                <div class="form-group"><label class="form-label">Catatan untuk Driver</label><textarea name="catatan" class="form-control" placeholder="Instruksi tambahan...">{{ old('catatan') }}</textarea></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3>📍 Susun Titik Mangkal</h3></div>
            <div class="card-body">
                <div class="alert alert-info" style="font-size:.78rem;margin-bottom:14px">💡 Tambahkan lokasi sesuai urutan kunjungan. Drag untuk mengubah urutan.</div>
                <div id="lokasi-container"></div>
                <button type="button" onclick="addLokasi()" class="btn btn-outline" style="width:100%;border-style:dashed;margin-top:4px">➕ Tambah Lokasi</button>
            </div>
        </div>
    </div>
    <div style="margin-top:16px;display:flex;justify-content:flex-end;gap:10px">
        <a href="{{ route('owner.rute.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">🗺️ Simpan Rute</button>
    </div>
</form>
@endsection
@push('scripts')
<script>
const lokasi = {!! json_encode($lokasi->map(fn($l)=>['id'=>$l->id,'nama'=>$l->nama_lokasi,'alamat'=>$l->alamat])) !!};
let count = 0;
function addLokasi() {
    count++;
    const opts = lokasi.map(l=>`<option value="${l.id}">${l.nama}</option>`).join('');
    document.getElementById('lokasi-container').insertAdjacentHTML('beforeend',`
    <div style="border:1.5px solid var(--gray-200);border-radius:10px;padding:12px;margin-bottom:10px;position:relative">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
            <div style="width:26px;height:26px;background:var(--navy);color:#fff;border-radius:50%;display:grid;place-items:center;font-size:.75rem;font-weight:700;flex-shrink:0">${count}</div>
            <strong style="font-size:.82rem;color:var(--gray-700)">Lokasi ke-${count}</strong>
            <button type="button" onclick="this.closest('div[style]').remove();renumber()" style="margin-left:auto;background:#fee2e2;border:none;border-radius:6px;padding:3px 8px;cursor:pointer;font-size:.72rem;color:#991b1b">✕ Hapus</button>
        </div>
        <select name="lokasi_ids[]" class="form-control" required style="margin-bottom:8px"><option value="">-- Pilih Lokasi --</option>${opts}</select>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div><label style="font-size:.75rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:4px">Jam Rencana Mulai</label><input type="time" name="jam_mulai[]" class="form-control"></div>
            <div><label style="font-size:.75rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:4px">Jam Rencana Selesai</label><input type="time" name="jam_selesai[]" class="form-control"></div>
        </div>
    </div>`);
}
function renumber() {
    document.querySelectorAll('#lokasi-container>div').forEach((el,i) => {
        const sp = el.querySelector('div[style*="border-radius:50%"]'); if(sp) sp.textContent=i+1;
        const str = el.querySelector('strong'); if(str) str.textContent=`Lokasi ke-${i+1}`;
    });
    count = document.querySelectorAll('#lokasi-container>div').length;
}
addLokasi();
</script>
@endpush
