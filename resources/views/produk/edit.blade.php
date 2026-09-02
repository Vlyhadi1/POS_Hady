@extends('layouts.app')

@section('title', 'Edit Produk - POS HADI')

@section('content')
@include('layouts.navbar')

<main class="pos-page">
<div class="pos-container">
    <section class="product-hero p-4 p-md-5 mb-4" style="background:linear-gradient(135deg,#0b1f36 0%,#064e3b 55%,#22c55e 100%);border-radius:22px;color:#fff">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <div>
                <div class="small opacity-75 mb-1">INVENTAR TOKO</div>
                <h1 class="h3 fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Produk</h1>
                <p class="mb-0 opacity-75 small">Perbarui data produk: {{ $produk->nama }}.</p>
            </div>
            <a href="{{ route('produk.index') }}" class="btn btn-outline-light rounded-pill px-3"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>
    </section>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('produk._form')
            </form>
        </div>
    </div>
</div>
</main>
@endsection
