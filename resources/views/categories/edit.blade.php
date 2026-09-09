@extends('layouts.app')
@section('title', isset($category->id) ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')
@include('layouts.navbar')
<main class="pos-page"><div class="pos-container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4">{{ isset($category->id) ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
            <form method="POST" action="{{ isset($category->id) ? route('admin.categories.update',$category) : route('admin.categories.store') }}">
                @csrf @if(isset($category->id)) @method('PUT') @endif
                @if($category->exists)
                    <div class="alert alert-light border d-flex align-items-center gap-2 mb-3"><i class="bi bi-shield-check text-success"></i><span class="small">Dibuat oleh <strong>{{ $category->user?->name ?? "Admin POS HADI" }}</strong>. Pembuat kategori hanya dapat berasal dari akun <strong>Admin</strong>.</span></div>
                @endif
                <div class="mb-3"><label class="form-label">Nama Kategori</label><input name="nama" value="{{ old('nama',$category->nama) }}" class="form-control" required></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Icon Bootstrap</label><input name="icon" value="{{ old('icon',$category->icon) }}" class="form-control" placeholder="bi-tag"></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Warna</label><input type="color" name="warna" value="{{ old('warna',$category->warna ?: '#22c55e') }}" class="form-control form-control-color"></div>
                </div>
                <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi',$category->deskripsi) }}</textarea></div>
                <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="status" value="1" id="status" @checked(old('status',$category->exists ? $category->status : true))><label class="form-check-label" for="status">Kategori aktif</label></div>
                <button class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Kembali</a>
            </form>
        </div>
    </div>
</div></main>@endsection
