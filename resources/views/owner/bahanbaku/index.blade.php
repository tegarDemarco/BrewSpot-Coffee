@extends('layouts.app')
@section('title','Bahan Baku')
@section('page-title','Bahan Baku')
@section('page-sub','Kelola stok bahan baku kamu')
@section('content')
<div class="page-header">
    <div><h1>Bahan Baku</h1><p>Kelola stok bahan baku kamu</p></div>
    <a href="{{ route('owner.bahanbaku.create') }}" class="btn btn-primary">+ Tambah Bahan Baku</a>
</div>

<div class="stat-grid" style="margin-bottom:20px">
    <div class="stat-card blue">
        <div class="stat-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
        <div class="stat-value">{{ $bahanBaku->total() }}</div>
        <div class="stat-label">Total Bahan Baku</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon">⚠️</div>
        <div class="stat-value">{{ $stokRendah }}</div>
        <div class="stat-label">Stok Rendah</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $stokCukup }}</div>
        <div class="stat-label">Stok Cukup</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Bahan Baku</h3>
        <div class="action-bar">
            <select name="satuan_filter" id="satuanFilter" class="form-control" style="width:130px">
                <option value="">Semua Satuan</option>
                <option>kg</option><option>gram</option><option>liter</option><option>ml</option><option>pcs</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Nama Bahan</th><th>Stok</th><th>Satuan</th><th>Stok Min.</th><th>Harga/Satuan</th><th>Keterangan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($bahanBaku as $i => $b)
            @php $isLow = $b->stok <= $b->stok_minimum; @endphp
            <tr>
                <td style="color:var(--gray-400)">{{ $bahanBaku->firstItem()+$i }}</td>
                <td><strong>{{ $b->nama_bahan }}</strong></td>
                <td>
                    <span style="font-weight:700;color:{{ $isLow ? 'var(--danger)':'var(--success)' }}">{{ number_format($b->stok,2) }}</span>
                </td>
                <td style="font-size:.8rem;color:var(--gray-500)">{{ $b->satuan }}</td>
                <td style="font-size:.8rem;color:var(--gray-400)">{{ number_format($b->stok_minimum,2) }}</td>
                <td style="font-weight:600">Rp {{ number_format($b->harga_satuan,0,',','.') }}</td>
                <td style="font-size:.78rem;color:var(--gray-500)">{{ Str::limit($b->keterangan,30) ?? '–' }}</td>
                <td>
                    @if($isLow) <span class="badge badge-orange">⚠ Stok Rendah</span>
                    @else <span class="badge badge-green">Cukup</span> @endif
                </td>
                <td>
                    <div style="display:flex;gap:5px">
                        <a href="{{ route('owner.bahanbaku.edit',$b) }}" class="btn btn-xs btn-outline">Edit</a>
                        <form method="POST" action="{{ route('owner.bahanbaku.destroy',$b) }}" onsubmit="return confirm('Hapus bahan baku ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">🗑</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9"><div class="empty-state" style="padding:40px 0"><div class="ei">🌿</div><p>Belum ada bahan baku</p><a href="{{ route('owner.bahanbaku.create') }}" class="btn btn-primary btn-sm" style="margin-top:10px">Tambah Sekarang</a></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px 16px">{{ $bahanBaku->links('vendor.pagination.simple-bs') }}</div>
</div>
@endsection
