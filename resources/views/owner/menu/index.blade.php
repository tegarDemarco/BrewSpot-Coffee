@extends('layouts.app')
@section('title','Menu & Harga')
@section('page-title','Menu')
@section('page-sub','Kelola daftar menu dan harga yang dijual')
@section('content')
<div class="page-header">
    <div><h1>Menu & Harga</h1><p>Kelola daftar menu dan harga yang dijual</p></div>
    <a href="{{ route('owner.menu.create') }}" class="btn btn-primary">+ Tambah Menu</a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px">
    @forelse($menus as $m)
    <div class="card" style="transition:.2s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="padding:16px;border-bottom:1px solid var(--gray-100)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:grid;place-items:center;font-size:1.4rem">☕</div>
                <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-end">
                    @if($m->is_active) <span class="badge badge-green">Aktif</span>
                    @else <span class="badge badge-red">Nonaktif</span> @endif
                    @if($m->kategori) <span class="badge badge-blue">{{ $m->kategori }}</span> @endif
                </div>
            </div>
            <div style="font-weight:800;font-size:.95rem;margin-bottom:4px">{{ $m->nama_menu }}</div>
            @if($m->deskripsi)<div style="font-size:.75rem;color:var(--gray-500);margin-bottom:8px">{{ Str::limit($m->deskripsi,55) }}</div>@endif
            <div style="font-size:1.2rem;font-weight:800;color:var(--blue)">Rp {{ number_format($m->harga,0,',','.') }}</div>
        </div>
        <div style="padding:10px 16px;display:flex;gap:7px">
            <a href="{{ route('owner.menu.edit',$m) }}" class="btn btn-xs btn-outline" style="flex:1;justify-content:center">✏️ Edit</a>
            <form method="POST" action="{{ route('owner.menu.toggle-status',$m) }}" style="flex:1">
                @csrf @method('PATCH')
                <button class="btn btn-xs {{ $m->is_active ? 'btn-danger':'btn-success' }}" style="width:100%">{{ $m->is_active ? 'Nonaktifkan':'Aktifkan' }}</button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1"><div class="empty-state card" style="padding:60px"><div class="ei">☕</div><p>Belum ada menu</p><a href="{{ route('owner.menu.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px">Tambah Menu</a></div></div>
    @endforelse
</div>
<div style="margin-top:16px">{{ $menus->links('vendor.pagination.simple-bs') }}</div>
@endsection
