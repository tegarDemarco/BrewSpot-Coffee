@extends('layouts.app')
@section('title','Edit Menu')
@section('page-title','Edit Menu')
@section('content')
<div class="page-header">
    <div><h1>Edit: {{ $menu->nama_menu }}</h1></div>
    <a href="{{ route('owner.menu.index') }}" class="btn btn-outline">← Kembali</a>
</div>
<div class="card" style="max-width:560px">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.menu.update',$menu) }}">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama Menu *</label><input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu',$menu->nama_menu) }}" required></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Harga (Rp) *</label><input type="number" name="harga" class="form-control" value="{{ old('harga',$menu->harga) }}" min="0" step="500" required></div>
                <div class="form-group"><label class="form-label">Kategori</label>
                    <select name="kategori" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['kopi','non-kopi','makanan','minuman lain'] as $k) <option value="{{ $k }}" {{ old('kategori',$menu->kategori)===$k?'selected':'' }}>{{ ucfirst($k) }}</option> @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control">{{ old('deskripsi',$menu->deskripsi) }}</textarea></div>
            <div class="form-group"><label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--gray-700)"><input type="checkbox" name="is_active" value="1" {{ old('is_active',$menu->is_active)?'checked':'' }} style="accent-color:var(--navy)"> Menu Aktif</label></div>
            <button type="submit" class="btn btn-primary">💾 Perbarui Menu</button>
        </form>
    </div>
</div>
@endsection
