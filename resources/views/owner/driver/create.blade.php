@extends('layouts.app')
@section('title','Tambah Driver')
@section('page-title','Tambah Driver')
@section('content')
<div class="page-header"><div><h1>Tambah Driver Baru</h1></div><a href="{{ route('owner.driver.index') }}" class="btn btn-outline">← Kembali</a></div>
<div class="card" style="max-width:540px">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.driver.store') }}">
            @csrf
            <div class="form-group"><label class="form-label">Nama Lengkap *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
            <div class="form-group"><label class="form-label">No. HP</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="081234567890"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Konfirmasi *</label><input type="password" name="password_confirmation" class="form-control" required></div>
            </div>
            <button type="submit" class="btn btn-primary">💾 Simpan Driver</button>
        </form>
    </div>
</div>
@endsection
