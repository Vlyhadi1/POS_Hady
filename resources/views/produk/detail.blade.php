@extends('layouts.app')

@section('title', 'Detail Produk - ' . $produk->nama)

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

    .product-card {
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .product-img-wrapper {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 250px;
        border: 1px solid #e2e8f0;
    }

    .product-img {
        max-height: 220px;
        width: auto;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-img:hover {
        transform: scale(1.05);
    }

    .qr-card-wrapper {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 16px;
        border: 2px dashed #e2e8f0;
        text-align: center;
    }

    .info-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
    }

    .price-box {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e2e8f0;
    }

    .btn-gradient-green {
    background: linear-gradient(
        135deg,
        #15803d 0%,
        #22c55e 100%
    ) !important;
    border: none !important;
    color: white !important;
    padding: 0.6rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-gradient-green:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
    color: white !important;
}
    a.btn-soft-secondary {
        background-color: #f1f5f9 !important;
        color: #64748b !important;
        border: none !important;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-soft-secondary:hover {
        background-color: #e2e8f0 !important;
        color: #334155 !important;
    }

    /* CSS Khusus Mode Cetak / Print Label */
    @media print {
        .no-print, nav, .navbar {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .product-card {
            border: none !important;
            box-shadow: none !important;
        }
        .banner-green-gradient{
            background: none !important;
            color: #000000 !important;
            padding: 0 !important;
            border-bottom: 2px solid #000;
            border-radius: 0 !important;
        }
        .banner-green-gradient * {
            color: #000000 !important;
        }
    }
</style>

<div class="container py-4">

    {{-- HEADER BANNER --}}
    <div class="banner-green-gradient p-4 rounded-4 mb-4 shadow-sm">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill fs-2"></i> Detail Produk
                </h2>
                <p class="text-white opacity-75 small mb-0">Informasi lengkap spesifikasi, harga, stok, dan Barcode/QR produk.</p>
            </div>
            <div class="d-flex align-items-center gap-2 no-print">
                <button onclick="window.print()" class="btn btn-light rounded-pill px-3 fw-semibold text-success shadow-sm d-flex align-items-center gap-1">
                    <i class="bi bi-printer-fill"></i> Cetak Label
                </button>
                <a href="{{ route('produk.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card border-0 shadow-sm product-card mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="row g-4 align-items-start">
                
                {{-- FOTO PRODUK & QR CODE --}}
                <div class="col-md-5 col-lg-4">
                    {{-- FOTO PRODUK --}}
                    <div class="product-img-wrapper mb-3">
                        @if(!empty($produk->foto))
                            <img src="{{ asset('storage/' . $produk->foto) }}" 
                                 alt="{{ $produk->nama }}" 
                                 class="img-fluid product-img">
                        @else
                            <div class="text-center text-muted">
                                <i class="bi bi-image fs-1 d-block mb-2 text-secondary"></i>
                                <span>Foto Tidak Tersedia</span>
                            </div>
                        @endif
                    </div>

                    {{-- QR CODE PRODUK --}}
                    <div class="qr-card-wrapper shadow-sm">
                        <div class="mb-2">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('produk.show', $produk->id)) }}" 
                                 alt="QR Code {{ $produk->nama }}" 
                                 class="img-fluid"
                                 width="120" height="120">
                        </div>
                        <span class="d-block fw-bold text-dark small">QR Code Produk</span>
                        <span class="d-block text-muted style-code small">ID: PRD-{{ sprintf('%04d', $produk->id) }}</span>
                    </div>
                </div>

                {{-- INFORMASI PRODUK --}}
                <div class="col-md-7 col-lg-8">
                    <div class="ps-md-3">
                        
                        {{-- STATUS STOK BADGE --}}
                        <div class="mb-2">
                            @if(($produk->stok ?? 0) > 10)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Stok Tersedia ({{ $produk->stok }})
                                </span>
                            @elseif(($produk->stok ?? 0) > 0)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Stok Menipis ({{ $produk->stok }})
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-semibold">
                                    <i class="bi bi-x-circle-fill me-1"></i> Stok Habis
                                </span>
                            @endif
                        </div>

                        {{-- NAMA PRODUK --}}
                        <h2 class="fw-bold text-dark mb-4">{{ $produk->nama }}</h2>

                        {{-- HARGA BELI & HARGA JUAL --}}
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="price-box">
                                    <span class="info-label d-block mb-1">Harga Beli</span>
                                    <span class="fs-5 fw-bold text-secondary">
                                        Rp {{ number_format($produk->harga_beli ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="price-box" style="background-color:#dcfce7; border-color:#bbf7d0;">
                                    <span class="info-label d-block mb-1" style="color:#15803d;">Harga Jual</span>
                                <span class="fs-4 fw-bold" style="color:#166534;">
                                    Rp {{ number_format($produk->harga_jual ?? 0, 0, ',', '.') }}
                            </span>
                                </div>
                            </div>
                        </div>

                        {{-- RINCIAN TAMBAHAN --}}
                        <div class="row g-3 border-top pt-3 mb-4">
                            <div class="col-6 col-sm-4">
                                <span class="info-label d-block mb-1">Total Stok</span>
                                <span class="info-value">{{ $produk->stok ?? 0 }} Unit</span>
                            </div>
                            <div class="col-6 col-sm-4">
                                <span class="info-label d-block mb-1">Dibuat Pada</span>
                                <span class="info-value fs-6">{{ $produk->created_at ? $produk->created_at->format('d M Y') : '-' }}</span>
                            </div>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="d-flex flex-wrap align-items-center gap-2 pt-2 no-print">
                            <a href="{{ route('produk.edit', $produk) }}" class="btn btn-gradient-green d-inline-flex align-items-center gap-2">
                                <i class="bi bi-pencil-square"></i>
                                <span>Edit Produk</span>
                            </a>
                            <a href="{{ route('produk.index') }}" class="btn btn-soft-secondary d-inline-flex align-items-center gap-2">
                                <i class="bi bi-arrow-left"></i>
                                <span>Kembali ke Daftar</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection