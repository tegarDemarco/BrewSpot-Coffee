@extends('layouts.app')
@section('title','Tambah Lokasi')
@section('page-title','Tambah Titik Mangkal')
@section('content')
<div class="page-header"><div><h1>Tambah Titik Mangkal</h1></div><a href="{{ route('owner.lokasi.index') }}" class="btn btn-outline">← Kembali</a></div>
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.lokasi.store') }}">
            @csrf
            <div class="form-group"><label class="form-label">Nama Lokasi *</label><input type="text" name="nama_lokasi" class="form-control" value="{{ old('nama_lokasi') }}" placeholder="cth: Area Kampus Universitas" required></div>
            <div class="form-group"><label class="form-label">Alamat *</label><textarea name="alamat" class="form-control" placeholder="Alamat lengkap lokasi">{{ old('alamat') }}</textarea></div>
            <div class="form-group"><label class="form-label">Deskripsi / Catatan</label><textarea name="deskripsi" class="form-control" placeholder="Informasi tambahan tentang lokasi ini">{{ old('deskripsi') }}</textarea></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Latitude <small style="color:var(--gray-400)">(opsional)</small></label><input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude') }}" placeholder="-7.123456"></div>
                <div class="form-group"><label class="form-label">Longitude <small style="color:var(--gray-400)">(opsional)</small></label><input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude') }}" placeholder="112.345678"></div>
            </div>
            <button type="submit" class="btn btn-primary">💾 Simpan Lokasi</button>
        </form>
    </div>
</div>
@endsection
