@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')

@include('layouts.navbar')

<style>
    .banner-green-gradient {
    background: linear-gradient(
        135deg,
        #020617 0%,
        #064e3b 55%,
        #22c55e 100%
    ) !important;
    color: #ffffff !important;
}
</style>

<div class="container py-4">

    {{-- HEADER BANNER --}}
    <div class="banner-green-gradient p-4 rounded-4 mb-4 shadow-sm">
        <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
            <i class="bi bi-box-seam-fill fs-2"></i> Tambah Produk Baru
        </h2>
        <p class="text-white opacity-75 small mb-0">Isi formulir di bawah ini untuk menambahkan barang baru ke inventaris toko.</p>
    </div>

    {{-- FORM CARD --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('produk.store') }}" 
                  method="POST"
                  enctype="multipart/form-data">
                @include('produk._form')
            </form>
        </div>
    </div>

</div>

@endsection