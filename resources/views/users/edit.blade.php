@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

@include('layouts.navbar')

<style>
    <>
    .banner-green-gradient {
        background:
        linear-gradient(
            135deg,
            #020617,
            #064e3b 55%,
            #22c55e
        ) !important;

        color:white !important;
        border-radius:20px;
        box-shadow:0 20px 50px rgba(34,197,94,.25);
        position:relative;
        overflow:hidden;
    }

</style>

<div class="container py-4">

    {{-- HEADER BANNER --}}
    <div class="banner-green-gradient p-4 rounded-4 mb-4 shadow-sm">
        <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
            <i class="bi bi-person-gear fs-2"></i> Edit Data User
        </h2>
        <p class="text-white opacity-75 small mb-0">Perbarui informasi pengguna, email, hak akses role, atau ganti password.</p>
    </div>

    {{-- FORM CARD --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-md-5">
            {{-- TAMBAHKAN enctype="multipart/form-data" DI SINI --}}
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('users._form')
            </form>
        </div>
    </div>

</div>

@endsection