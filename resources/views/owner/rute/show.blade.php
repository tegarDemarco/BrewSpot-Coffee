@extends('layouts.app')
@section('title','Detail Rute')
@section('page-title','Detail Rute')
@section('content')
<div class="page-header">
    <div><h1>{{ $rute->nama_rute }}</h1><p>{{ $rute->tanggal_berlaku->format('d M Y') }} • Driver: {{ $rute->driver->name ?? '–' }}</p></div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('owner.rute.index') }}" class="btn btn-outline">← Kembali</a>
        <form method="POST" action="{{ route('owner.rute.destroy',$rute) }}" onsubmit="return confirm('Hapus rute ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">🗑 Hapus</button>
        </form>
    </div>
</div>
<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3>📍 Urutan Lokasi</h3></div>
        <div class="card-body">
            <div class="rute-steps">
            @foreach($rute->details as $d)
            <div class="rute-step">
                <div class="step-num {{ $d->kunjungan->where('status','selesai')->count() ? 'done' : ($d->kunjungan->where('status','aktif')->count() ? 'active' : '') }}">{{ $d->urutan }}</div>
                <div class="step-body">
                    <h4>{{ $d->lokasiMangkal->nama_lokasi }}</h4>
                    <p>{{ $d->lokasiMangkal->alamat }}</p>
                    @if($d->jam_rencana_mulai)
                    <p style="margin-top:3px;color:var(--blue);font-weight:600;font-size:.75rem">⏰ {{ substr($d->jam_rencana_mulai,0,5) }} – {{ substr($d->jam_rencana_selesai,0,5) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>🏃 Kunjungan Aktual</h3></div>
        <div class="card-body">
            @forelse($rute->kunjungan as $k)
            <div style="border:1px solid var(--gray-200);border-radius:10px;padding:12px;margin-bottom:10px">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                    <strong style="font-size:.85rem">{{ $k->lokasiMangkal->nama_lokasi ?? '–' }}</strong>
                    @if($k->status==='aktif') <span class="badge badge-green">Aktif</span>
                    @elseif($k->status==='selesai') <span class="badge badge-blue">Selesai</span>
                    @else <span class="badge badge-red">Batal</span> @endif
                </div>
                <div style="font-size:.78rem;color:var(--gray-500)">
                    Mulai: {{ $k->waktu_mulai->format('H:i') }}
                    @if($k->waktu_selesai) • Selesai: {{ $k->waktu_selesai->format('H:i') }} @endif
                </div>
                <div style="margin-top:5px;font-weight:700;font-size:.82rem;color:var(--blue)">Total: Rp {{ number_format($k->sales->where('status','valid')->sum('total'),0,',','.') }}</div>
            </div>
            @empty
            <div class="empty-state" style="padding:30px 0"><div class="ei">🏃</div><p>Belum ada kunjungan aktual</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
