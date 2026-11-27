@extends('layouts.app')
@section('title', isset($bahanBaku) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')
@section('page-title', isset($bahanBaku) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')
@section('content')
<div class="page-header">
    <div><h1>{{ isset($bahanBaku) ? 'Edit' : 'Tambah' }} Bahan Baku</h1></div>
    <a href="{{ route('owner.bahanbaku.index') }}" class="btn btn-outline">← Kembali</a>
</div>
<div class="card" style="max-width:580px">
    <div class="card-body">
        <form method="POST" action="{{ isset($bahanBaku) ? route('owner.bahanbaku.update',$bahanBaku) : route('owner.bahanbaku.store') }}">
            @csrf
            @if(isset($bahanBaku)) @method('PUT') @endif
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Bahan Baku *</label>
                    <input type="text" name="nama_bahan" class="form-control" value="{{ old('nama_bahan', $bahanBaku->nama_bahan ?? '') }}" placeholder="cth: Kopi Arabika" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan *</label>
                    <select name="satuan" class="form-control" required>
                        @foreach(['kg','gram','liter','ml','pcs','karton'] as $s)
                        <option value="{{ $s }}" {{ old('satuan', $bahanBaku->satuan ?? '')===$s ? 'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Stok Saat Ini *</label>
                    <input type="number" name="stok" step="0.01" class="form-control" value="{{ old('stok', $bahanBaku->stok ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Stok Minimum *</label>
                    <input type="number" name="stok_minimum" step="0.01" class="form-control" value="{{ old('stok_minimum', $bahanBaku->stok_minimum ?? '') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Harga per Satuan (Rp)</label>
                <input type="number" name="harga_satuan" class="form-control" value="{{ old('harga_satuan', $bahanBaku->harga_satuan ?? '') }}" placeholder="0">
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" placeholder="Catatan tambahan...">{{ old('keterangan', $bahanBaku->keterangan ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">💾 {{ isset($bahanBaku) ? 'Perbarui' : 'Simpan' }} Bahan Baku</button>
        </form>
    </div>
</div>
@endsection
