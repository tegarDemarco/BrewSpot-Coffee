@extends('layouts.app')
@section('title','Edit Lokasi')
@section('page-title','Edit Titik Mangkal')
@section('content')
<div class="page-header"><div><h1>Edit: {{ $lokasi->nama_lokasi }}</h1></div><a href="{{ route('owner.lokasi.index') }}" class="btn btn-outline">← Kembali</a></div>
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.lokasi.update',$lokasi) }}">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama Lokasi *</label><input type="text" name="nama_lokasi" class="form-control" value="{{ old('nama_lokasi',$lokasi->nama_lokasi) }}" required></div>
            <div class="form-group"><label class="form-label">Alamat *</label><textarea name="alamat" class="form-control">{{ old('alamat',$lokasi->alamat) }}</textarea></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control">{{ old('deskripsi',$lokasi->deskripsi) }}</textarea></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Latitude</label><input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude',$lokasi->latitude) }}"></div>
                <div class="form-group"><label class="form-label">Longitude</label><input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude',$lokasi->longitude) }}"></div>
            </div>
            <div class="form-group"><label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--gray-700)"><input type="checkbox" name="is_active" value="1" {{ old('is_active',$lokasi->is_active)?'checked':'' }} style="accent-color:var(--navy)"> Lokasi Aktif</label></div>
            <button type="submit" class="btn btn-primary">💾 Perbarui Lokasi</button>
        </form>
    </div>
</div>
@endsection
