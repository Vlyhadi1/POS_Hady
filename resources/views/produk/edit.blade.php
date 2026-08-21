@extends('layouts.app')

@section('title', 'Edit Produk')

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
            <i class="bi bi-pencil-square fs-2"></i> Edit Data Produk
        </h2>
        <p class="text-white opacity-75 small mb-0">Perbarui informasi, harga, stok, atau foto produk yang dipilih.</p>
    </div>

    {{-- FORM CARD --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('produk.update', $produk) }}"
                  method="POST"
                  enctype="multipart/form-data">

                  @csrf
                @method('PUT')

                @include('produk._form')

            </form>
        </div>
    </div>

</div>

@endsection