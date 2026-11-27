@extends('layouts.app')
@section('title','Rute Saya')
@section('page-title','Rute Saya')
@section('page-sub','Daftar penugasan rute dari pemilik')
@section('content')
<div class="page-header"><div><h1>Rute Saya</h1><p>Daftar penugasan rute dari pemilik</p></div></div>
<div style="display:flex;flex-direction:column;gap:14px">
    @forelse($rute as $r)
    <div class="card">
        <div style="display:flex;align-items:center;gap:16px;padding:16px 20px">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:grid;place-items:center;font-size:1.3rem;flex-shrink:0">🗺️</div>
            <div style="flex:1">
                <div style="font-weight:800;font-size:.95rem">{{ $r->nama_rute }}</div>
                <div style="font-size:.78rem;color:var(--gray-500);margin-top:2px">📅 {{ $r->tanggal_berlaku->format('d M Y') }} • {{ $r->details->count() }} lokasi</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                @if($r->status==='aktif') <span class="badge badge-green">Aktif</span>
                @elseif($r->status==='selesai') <span class="badge badge-blue">Selesai</span>
                @else <span class="badge badge-gray">Nonaktif</span> @endif
                <a href="{{ route('driver.rute.show',$r) }}" class="btn btn-primary btn-sm">Lihat →</a>
            </div>
        </div>
    </div>
    @empty
    <div class="card"><div class="empty-state" style="padding:60px"><div class="ei">🗺️</div><p>Belum ada rute yang ditugaskan</p></div></div>
    @endforelse
</div>
<div style="margin-top:16px">{{ $rute->links('vendor.pagination.simple-bs') }}</div>
@endsection
