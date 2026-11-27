@extends('layouts.app')
@section('title','Rute & Jadwal')
@section('page-title','Manajemen Rute')
@section('page-sub','Atur rute dan jadwal kunjungan driver')
@section('content')
<div class="page-header">
    <div><h1>Manajemen Rute</h1><p>Atur rute dan jadwal kunjungan driver</p></div>
    <a href="{{ route('owner.rute.create') }}" class="btn btn-primary">+ Buat Rute</a>
</div>

<div class="grid-col-6-6">
    <!-- RUTE LIST -->
    <div class="card">
        <div class="card-header"><h3>Daftar Rute</h3></div>
        <div style="max-height:520px;overflow-y:auto">
            @forelse($rute as $r)
            <a href="{{ route('owner.rute.show',$r) }}" style="display:block;padding:14px 18px;border-bottom:1px solid var(--gray-100);transition:.15s" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background=''">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div style="font-weight:700;font-size:.875rem">{{ $r->nama_rute }}</div>
                        <div style="font-size:.75rem;color:var(--gray-500);margin-top:2px">{{ $r->details->count() }} Lokasi • {{ $r->tanggal_berlaku->format('d M Y') }}</div>
                        <div style="font-size:.73rem;color:var(--gray-400);margin-top:2px">Driver: {{ $r->driver->name ?? '–' }}</div>
                    </div>
                    @if($r->status==='aktif') <span class="badge badge-green">Aktif</span>
                    @elseif($r->status==='selesai') <span class="badge badge-blue">Selesai</span>
                    @else <span class="badge badge-gray">Nonaktif</span> @endif
                </div>
            </a>
            @empty
            <div class="empty-state"><div class="ei">🗺️</div><p>Belum ada rute</p></div>
            @endforelse
        </div>
        <div style="padding:12px 16px;border-top:1px solid var(--gray-100)">{{ $rute->links('vendor.pagination.simple-bs') }}</div>
    </div>

    <!-- DETAIL PLACEHOLDER / LATEST -->
    <div class="card">
        <div class="card-header"><h3>Detail Rute Terbaru</h3></div>
        @php $latest = $rute->first(); @endphp
        @if($latest)
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
                <div>
                    <div style="font-weight:800;font-size:1rem">{{ $latest->nama_rute }}</div>
                    <div style="font-size:.78rem;color:var(--gray-500)">{{ $latest->tanggal_berlaku->format('d M Y') }}</div>
                </div>
                <span class="badge {{ $latest->status==='aktif' ? 'badge-green':'badge-gray' }}">{{ ucfirst($latest->status) }}</span>
            </div>
            <div class="rute-steps">
                @foreach($latest->details as $d)
                <div class="rute-step">
                    <div class="step-num">{{ $d->urutan }}</div>
                    <div class="step-body">
                        <h4>{{ $d->lokasiMangkal->nama_lokasi }}</h4>
                        <p>{{ $d->lokasiMangkal->alamat }}</p>
                        @if($d->jam_rencana_mulai)
                        <p style="margin-top:3px;color:var(--blue);font-weight:600;font-size:.75rem">{{ substr($d->jam_rencana_mulai,0,5) }} – {{ substr($d->jam_rencana_selesai,0,5) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Mini map visualization -->
            <div style="margin-top:16px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-radius:10px;padding:16px;min-height:120px;position:relative;overflow:hidden">
                <div style="position:absolute;inset:0;opacity:.1;background:repeating-linear-gradient(0deg,transparent,transparent 19px,#94a3b8 19px,#94a3b8 20px),repeating-linear-gradient(90deg,transparent,transparent 19px,#94a3b8 19px,#94a3b8 20px)"></div>
                <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-around;padding:10px 0">
                    @foreach($latest->details->take(4) as $d)
                    <div style="text-align:center">
                        <div style="width:28px;height:28px;background:var(--navy);border-radius:50%;border:2px solid #fff;margin:0 auto 4px;display:grid;place-items:center;font-size:.65rem;color:#fff;font-weight:700">{{ $d->urutan }}</div>
                        <div style="font-size:.6rem;font-weight:600;color:var(--navy);max-width:50px;text-align:center">{{ Str::limit($d->lokasiMangkal->nama_lokasi,8) }}</div>
                    </div>
                    @if(!$loop->last) <div style="flex:1;height:2px;background:var(--navy);opacity:.3;max-width:30px"></div> @endif
                    @endforeach
                </div>
            </div>
            <div style="margin-top:12px;display:flex;gap:8px">
                <a href="{{ route('owner.rute.show',$latest) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">Lihat Detail</a>
                <form method="POST" action="{{ route('owner.rute.destroy',$latest) }}" onsubmit="return confirm('Hapus rute ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline btn-sm btn-danger">🗑</button>
                </form>
            </div>
        </div>
        @else
        <div class="card-body"><div class="empty-state"><div class="ei">🗺️</div><p>Pilih rute untuk melihat detail</p></div></div>
        @endif
    </div>
</div>
@endsection
