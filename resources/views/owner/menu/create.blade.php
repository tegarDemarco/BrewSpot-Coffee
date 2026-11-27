@extends('layouts.app')
@section('title','Tambah Menu')
@section('page-title','Tambah Menu')
@section('content')
<div class="page-header">
    <div><h1>Tambah Menu</h1></div>
    <a href="{{ route('owner.menu.index') }}" class="btn btn-outline">← Kembali</a>
</div>
<div class="card" style="max-width:560px">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.menu.store') }}">
            @csrf
            <div class="form-group"><label class="form-label">Nama Menu *</label><input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu') }}" placeholder="cth: Kopi Susu" required></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Harga (Rp) *</label><input type="number" name="harga" class="form-control" value="{{ old('harga') }}" min="0" step="500" placeholder="12000" required></div>
                <div class="form-group"><label class="form-label">Kategori</label>
                    <select name="kategori" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['kopi','non-kopi','makanan','minuman lain'] as $k) <option value="{{ $k }}" {{ old('kategori')===$k?'selected':'' }}>{{ ucfirst($k) }}</option> @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" placeholder="Deskripsi singkat menu...">{{ old('deskripsi') }}</textarea></div>
            <button type="submit" class="btn btn-primary">💾 Simpan Menu</button>
        </form>
    </div>
</div>
@endsection
