@extends('layouts.app')
@section('title','Titik Mangkal')
@section('page-title','Titik Mangkal')
@section('page-sub','Kelola lokasi tempat berjualan kamu')
@section('content')
<div class="page-header">
    <div><h1>Titik Mangkal</h1><p>Kelola lokasi tempat berjualan kamu</p></div>
    <div class="action-bar">
        <div class="input-group" style="width:200px">
            <span class="ig-icon">🔍</span>
            <input type="text" id="searchLokasi" class="form-control" placeholder="Cari lokasi..." style="padding-left:32px">
        </div>
        <a href="{{ route('owner.lokasi.create') }}" class="btn btn-primary">+ Tambah Lokasi</a>
    </div>
</div>

<div class="grid-col-7-5">
    <!-- MAP -->
    <div class="card">
        <div class="card-header"><h3>🗺️ Peta Lokasi</h3></div>
        <div style="background:linear-gradient(135deg,#dbeafe 0%,#bfdbfe 100%);height:360px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
            <!-- Decorative map background -->
            <div style="position:absolute;inset:0;opacity:.15;background:repeating-linear-gradient(0deg,transparent,transparent 29px,#94a3b8 29px,#94a3b8 30px),repeating-linear-gradient(90deg,transparent,transparent 29px,#94a3b8 29px,#94a3b8 30px)"></div>
            @foreach($lokasi as $i => $l)
            @if($l->latitude && $l->longitude)
            <div style="position:absolute;top:{{ 20 + ($i*18) }}%;left:{{ 15 + ($i*17) }}%;transform:translate(-50%,-50%)">
                <div style="background:var(--navy);color:#fff;padding:6px 10px;border-radius:8px;font-size:.7rem;font-weight:600;white-space:nowrap;position:relative">
                    {{ $l->nama_lokasi }}
                    <div style="position:absolute;bottom:-6px;left:50%;transform:translateX(-50%);width:0;height:0;border-left:6px solid transparent;border-right:6px solid transparent;border-top:6px solid var(--navy)"></div>
                </div>
            </div>
            @endif
            @endforeach
            <div style="text-align:center;position:relative;z-index:1">
                <div style="font-size:2.5rem;margin-bottom:8px">🗺️</div>
                <div style="font-size:.85rem;color:var(--gray-500);font-weight:600">Peta Interaktif</div>
                <div style="font-size:.75rem;color:var(--gray-400);margin-top:4px">{{ $lokasi->count() }} lokasi terdaftar</div>
            </div>
            <!-- Pin markers -->
            @foreach($lokasi->take(4) as $i => $l)
            <div style="position:absolute;top:{{ [30,55,45,70][$i] }}%;left:{{ [25,55,75,40][$i] }}%;transform:translate(-50%,-50%)">
                <div style="width:36px;height:36px;background:{{ $l->is_active ? 'var(--navy)':'var(--gray-400)' }};border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.2)"></div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- LIST -->
    <div class="card">
        <div class="card-header">
            <h3>Daftar Lokasi ({{ $lokasi->total() }})</h3>
        </div>
        <div class="card-body" style="padding:12px;max-height:400px;overflow-y:auto" id="lokasiList">
            @forelse($lokasi as $l)
            <div class="lokasi-list-item">
                <div class="lokasi-thumb" style="background:{{ $l->is_active ? 'var(--blue-soft)':'var(--gray-100)' }}">📍</div>
                <div class="lokasi-info">
                    <strong>{{ $l->nama_lokasi }}</strong>
                    <span>{{ Str::limit($l->alamat, 40) }}</span>
                    <div style="margin-top:4px">
                        @if($l->is_active) <span class="badge badge-green">Aktif</span>
                        @else <span class="badge badge-red">Nonaktif</span> @endif
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:5px">
                    <a href="{{ route('owner.lokasi.edit',$l) }}" class="btn btn-xs btn-outline">✏️</a>
                    <form method="POST" action="{{ route('owner.lokasi.toggle-status',$l) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-xs {{ $l->is_active ? 'btn-danger':'btn-success' }}">{{ $l->is_active ? '⏸':'▶' }}</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state"><div class="ei">📍</div><p>Belum ada lokasi</p><a href="{{ route('owner.lokasi.create') }}" class="btn btn-primary btn-sm" style="margin-top:10px">Tambah Sekarang</a></div>
            @endforelse
        </div>
        <div style="padding:12px 16px;border-top:1px solid var(--gray-100)">{{ $lokasi->links('vendor.pagination.simple-bs') }}</div>
    </div>
</div>

<!-- TABLE -->
<div class="card" style="margin-top:18px">
    <div class="card-header"><h3>Semua Titik Mangkal</h3></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Nama Lokasi</th><th>Alamat</th><th>Koordinat</th><th>Kunjungan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($lokasi as $i => $l)
            <tr>
                <td style="color:var(--gray-400)">{{ $lokasi->firstItem()+$i }}</td>
                <td><strong>{{ $l->nama_lokasi }}</strong>@if($l->deskripsi)<br><small style="color:var(--gray-400)">{{ Str::limit($l->deskripsi,45) }}</small>@endif</td>
                <td style="font-size:.8rem;color:var(--gray-500);max-width:180px">{{ $l->alamat }}</td>
                <td style="font-size:.75rem;color:var(--gray-400)">{{ $l->latitude ? $l->latitude.', '.$l->longitude : '–' }}</td>
                <td><span class="badge badge-blue">{{ $l->kunjungan->count() }}</span></td>
                <td>@if($l->is_active)<span class="badge badge-green">Aktif</span>@else<span class="badge badge-red">Nonaktif</span>@endif</td>
                <td>
                    <div style="display:flex;gap:5px">
                        <a href="{{ route('owner.lokasi.edit',$l) }}" class="btn btn-xs btn-outline">Edit</a>
                        <form method="POST" action="{{ route('owner.lokasi.toggle-status',$l) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs {{ $l->is_active ? 'btn-danger':'btn-success' }}">{{ $l->is_active ? 'Nonaktifkan':'Aktifkan' }}</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state" style="padding:30px"><div class="ei">📍</div><p>Belum ada titik mangkal</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('searchLokasi').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.lokasi-list-item').forEach(el => {
        el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
