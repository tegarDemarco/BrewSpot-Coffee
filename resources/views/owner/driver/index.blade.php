@extends('layouts.app')
@section('title','Manajemen Driver')
@section('page-title','Manajemen Driver')
@section('page-sub','Kelola akun dan aktivitas driver')
@section('content')
<div class="page-header">
    <div><h1>Manajemen Driver</h1><p>Kelola akun dan aktivitas driver</p></div>
    <a href="{{ route('owner.driver.create') }}" class="btn btn-primary">+ Tambah Driver</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Driver</th><th>Email</th><th>No. HP</th><th>Status</th><th>Bergabung</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($drivers as $i => $d)
            <tr>
                <td style="color:var(--gray-400)">{{ $drivers->firstItem()+$i }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--navy);color:#fff;display:grid;place-items:center;font-size:.78rem;font-weight:700;flex-shrink:0">{{ strtoupper(substr($d->name,0,2)) }}</div>
                        <div><strong>{{ $d->name }}</strong></div>
                    </div>
                </td>
                <td style="font-size:.82rem;color:var(--gray-500)">{{ $d->email }}</td>
                <td style="font-size:.82rem">{{ $d->phone ?? '–' }}</td>
                <td>@if($d->is_active)<span class="badge badge-green">Aktif</span>@else<span class="badge badge-red">Nonaktif</span>@endif</td>
                <td style="font-size:.78rem;color:var(--gray-400)">{{ $d->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:5px">
                        <a href="{{ route('owner.driver.edit',$d) }}" class="btn btn-xs btn-outline">Edit</a>
                        <form method="POST" action="{{ route('owner.driver.toggle-status',$d) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs {{ $d->is_active ? 'btn-danger':'btn-success' }}">{{ $d->is_active ? 'Nonaktifkan':'Aktifkan' }}</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state" style="padding:40px"><div class="ei">🚴</div><p>Belum ada driver</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px 16px">{{ $drivers->links('vendor.pagination.simple-bs') }}</div>
</div>
@endsection
