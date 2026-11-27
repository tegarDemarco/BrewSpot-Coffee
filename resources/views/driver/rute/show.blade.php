@extends('layouts.app')
@section('title','Detail Rute')
@section('page-title','Detail Rute')
@section('content')
<div class="page-header">
    <div><h1>{{ $rute->nama_rute }}</h1><p>{{ $rute->tanggal_berlaku->format('d M Y') }}</p></div>
    <a href="{{ route('driver.rute.index') }}" class="btn btn-outline">← Kembali</a>
</div>
@php $driver = auth()->user(); $aktif = $driver->kunjunganAktif; @endphp
<div class="card">
    <div class="card-header"><h3>📍 Urutan Lokasi</h3></div>
    <div class="card-body">
        <div class="rute-steps">
        @foreach($rute->details as $d)
        @php $kj = $d->kunjungan->first(); @endphp
        <div class="rute-step">
            <div class="step-num {{ $kj && $kj->status==='selesai' ? 'done' : ($kj && $kj->status==='aktif' ? 'active':'') }}">{{ $d->urutan }}</div>
            <div class="step-body">
                <h4>{{ $d->lokasiMangkal->nama_lokasi }}</h4>
                <p>{{ $d->lokasiMangkal->alamat }}</p>
                @if($d->jam_rencana_mulai)<p style="color:var(--blue);font-size:.75rem;font-weight:600;margin-top:2px">⏰ {{ substr($d->jam_rencana_mulai,0,5) }} – {{ substr($d->jam_rencana_selesai,0,5) }}</p>@endif
                @if($d->lokasiMangkal->deskripsi)<p style="margin-top:2px;font-size:.75rem;color:var(--gray-400)">📝 {{ $d->lokasiMangkal->deskripsi }}</p>@endif
                <div style="margin-top:8px">
                    @if($kj && $kj->status==='selesai')
                        <span class="badge badge-blue">✅ Selesai • Rp {{ number_format($kj->sales->where('status','valid')->sum('total'),0,',','.') }}</span>
                    @elseif($kj && $kj->status==='aktif')
                        <span class="badge badge-green">🟢 Sedang Berlangsung</span>
                        <a href="{{ route('driver.kunjungan.aktif') }}" class="btn btn-sm btn-primary" style="margin-left:8px">Kelola →</a>
                    @elseif(!$aktif)
                        <form method="POST" action="{{ route('driver.kunjungan.mulai') }}" style="display:inline">
                            @csrf
                            <input type="hidden" name="lokasi_mangkal_id" value="{{ $d->lokasi_mangkal_id }}">
                            <input type="hidden" name="rute_id" value="{{ $rute->id }}">
                            <input type="hidden" name="rute_detail_id" value="{{ $d->id }}">
                            <button class="btn btn-sm btn-success">▶ Mulai Mangkal</button>
                        </form>
                    @else
                        <span class="badge badge-gray">Menunggu</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>
@endsection
