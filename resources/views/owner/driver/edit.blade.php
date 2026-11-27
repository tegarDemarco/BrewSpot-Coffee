@extends('layouts.app')
@section('title','Edit Driver')
@section('page-title','Edit Driver')
@section('content')
<div class="page-header"><div><h1>Edit Driver: {{ $driver->name }}</h1></div><a href="{{ route('owner.driver.index') }}" class="btn btn-outline">← Kembali</a></div>
<div class="card" style="max-width:540px">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.driver.update',$driver) }}">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama Lengkap *</label><input type="text" name="name" class="form-control" value="{{ old('name',$driver->name) }}" required></div>
            <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email',$driver->email) }}" required></div>
            <div class="form-group"><label class="form-label">No. HP</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$driver->phone) }}"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Password Baru <small style="font-weight:400;color:var(--gray-400)">(kosongkan jika tidak diubah)</small></label><input type="password" name="password" class="form-control"></div>
                <div class="form-group"><label class="form-label">Konfirmasi</label><input type="password" name="password_confirmation" class="form-control"></div>
            </div>
            <div class="form-group"><label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--gray-700)"><input type="checkbox" name="is_active" value="1" {{ old('is_active',$driver->is_active)?'checked':'' }} style="accent-color:var(--navy)"> Akun Aktif</label></div>
            <button type="submit" class="btn btn-primary">💾 Perbarui Data</button>
        </form>
    </div>
</div>
@endsection
