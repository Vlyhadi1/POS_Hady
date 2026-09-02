@extends('layouts.app')

@section('title', 'Detail Produk - POS HADI')

@section('content')
@include('layouts.navbar')

<style>
.detail-hero{background:linear-gradient(135deg,#0b1f36 0%,#064e3b 55%,#22c55e 100%);border-radius:22px;color:#fff;box-shadow:0 18px 45px rgba(21,128,61,.16)}
.detail-card{background:#fff;border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 10px 28px rgba(15,23,42,.05)}
.detail-image{width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:16px;border:1px solid #e5e7eb;background:#f8fafc}.detail-placeholder{aspect-ratio:1/1;border-radius:16px;background:#f1f5f9;display:grid;place-items:center;color:#94a3b8;font-size:56px}
.info-box{height:100%;background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;padding:15px}.info-label{font-size:.72rem;color:#64748b;display:block;margin-bottom:5px}.info-value{font-weight:700;color:#1e293b}.price-box{background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;padding:16px}.price-sale{background:#f0fdf4;border-color:#bbf7d0}.description{white-space:pre-line;color:#475569;line-height:1.7}
@media print{.no-print,.pos-sidebar,.pos-mobile-toggle,.pos-sidebar-backdrop{display:none!important}.pos-page{margin:0!important;padding:0!important}.detail-hero{background:#fff!important;color:#000!important;box-shadow:none;border-bottom:2px solid #111;border-radius:0}.detail-hero *{color:#000!important}.detail-card{box-shadow:none}}
</style>

<main class="pos-page">
<div class="pos-container">
    <section class="detail-hero p-4 p-md-5 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="small opacity-75 mb-1">INVENTAR TOKO</div>
                <h1 class="h3 fw-bold mb-1"><i class="bi bi-box-seam-fill me-2"></i>Detail Produk</h1>
                <p class="mb-0 opacity-75 small">Informasi lengkap produk dan kondisi stok saat ini.</p>
            </div>
            <div class="d-flex gap-2 no-print">
                <button type="button" onclick="window.print()" class="btn btn-light rounded-pill px-3 fw-semibold" style="color:#15803d"><i class="bi bi-printer me-1"></i>Cetak</button>
                <a href="{{ route('produk.index') }}" class="btn btn-outline-light rounded-pill px-3"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            </div>
        </div>
    </section>

    <section class="detail-card p-3 p-md-4 p-lg-5">
        <div class="row g-4 g-lg-5">
            <div class="col-md-5 col-lg-4">
                @if($produk->foto)
                    <img src="{{ asset('storage/'.$produk->foto) }}" alt="{{ $produk->nama }}" class="detail-image">
                @else
                    <div class="detail-placeholder"><i class="bi bi-box-seam"></i></div>
                @endif
            </div>
            <div class="col-md-7 col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    @if($produk->status)
                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                    @else
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border px-3 py-2"><i class="bi bi-pause-circle-fill me-1"></i>Nonaktif</span>
                    @endif
                    @if($produk->stok <= 0)
                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">Stok Habis</span>
                    @elseif($produk->stok <= $produk->minimum_stok)
                        <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">Stok Kritis</span>
                    @else
                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">Stok Aman</span>
                    @endif
                </div>

                <h2 class="fw-bold text-dark mb-1">{{ $produk->nama }}</h2>
                <div class="text-muted small mb-4">
                    <i class="{{ $produk->category?->icon ?: 'bi bi-tags' }} me-1"></i>{{ $produk->category?->nama ?? 'Tanpa kategori' }} • {{ $produk->satuan }}
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6"><div class="price-box"><span class="info-label">Harga Beli / Modal</span><div class="fs-5 fw-bold text-dark">Rp {{ number_format($produk->harga_beli,0,',','.') }}</div></div></div>
                    <div class="col-sm-6"><div class="price-box price-sale"><span class="info-label text-success">Harga Jual</span><div class="fs-4 fw-bold text-success">Rp {{ number_format($produk->harga_jual,0,',','.') }}</div></div></div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-4"><div class="info-box"><span class="info-label">Stok Saat Ini</span><div class="info-value">{{ number_format($produk->stok,0,',','.') }} {{ $produk->satuan }}</div></div></div>
                    <div class="col-6 col-lg-4"><div class="info-box"><span class="info-label">Minimum Stok</span><div class="info-value">{{ number_format($produk->minimum_stok,0,',','.') }} {{ $produk->satuan }}</div></div></div>
                    <div class="col-12 col-lg-4"><div class="info-box"><span class="info-label">Estimasi Untung / {{ $produk->satuan }}</span><div class="info-value text-success">Rp {{ number_format($produk->profit,0,',','.') }}</div></div></div>
                </div>

                @if($produk->deskripsi)
                    <div class="border-top pt-3 mb-4"><span class="info-label">Deskripsi</span><div class="description">{{ $produk->deskripsi }}</div></div>
                @endif

                <div class="d-flex flex-wrap gap-2 no-print">
                    @can('update', $produk)
                        <a href="{{ route('produk.edit', $produk) }}" class="btn btn-success rounded-3 px-4 fw-semibold"><i class="bi bi-pencil-square me-1"></i>Edit Produk</a>
                    @endcan
                    <a href="{{ route('produk.index') }}" class="btn btn-light border rounded-3 px-4 fw-semibold"><i class="bi bi-arrow-left me-1"></i>Kembali ke Produk</a>
                </div>
            </div>
        </div>
    </section>
</div>
</main>
@endsection
