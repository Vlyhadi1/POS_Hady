@extends('layouts.app')

@section('title', 'Dashboard - POS System')

@section('content')

@include('layouts.navbar')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #020617 0%, #064e3b 50%, #22c55e 100%);
        --accent-gradient: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
        --card-bg: #ffffff;
        --card-border: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --green-soft: #dcfce7;
        --green-text: #15803d;
        --radius-lg: 20px;
        --radius-md: 14px;
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* HEADER BANNER */
    .dashboard-header-banner {
        background: var(--primary-gradient);
        border-radius: var(--radius-lg);
        padding: 2.5rem 2.25rem;
        color: #ffffff;
        box-shadow: 0 20px 50px rgba(34,197,94,.25);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header-banner::after {
        content: '';
        position: absolute;
        top: -40%;
        right: -8%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .date-badge {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50px;
        padding: 0.4rem 1rem;
        font-size: 0.825rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* QUICK ACTIONS */
    .btn-quick-action {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        border-radius: 12px;
        padding: 0.65rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-quick-action:hover {
        background: #ffffff;
        color: var(--green-text);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* CARDS & HOVER EFFECTS */
    .dashboard-card {

    border-radius:22px;

    border:1px solid rgba(226,232,240,.8);

    background:
    linear-gradient(
        145deg,
        #ffffff,
        #f8fafc
    );

    transition:.35s;

    box-shadow:
    0 15px 35px rgba(15,23,42,.08);

    position:relative;

    overflow:hidden;
}


.dashboard-card:hover {

    transform: translateY(-8px);

    box-shadow:
    0 25px 50px rgba(34,197,94,.18);

    border-color:
    rgba(34,197,94,.35);

}

    .card-top-accent {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .icon-box-modern {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .dashboard-card:hover .icon-box-modern {
        transform: scale(1.08);
    }

    .bg-green-subtle-custom {
    background-color: var(--green-soft) !important;
    color: var(--green-text) !important;

    }

    .section-title {
        color: var(--text-primary);
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    /* TABLES STYLE */
    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-custom th {
        color: var(--text-secondary);
        font-size: 0.725rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        padding: 0.875rem 1.25rem;
        border: none;
    }

    .table-custom td {
        padding: 1rem 1.25rem;
        color: var(--text-primary);
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .table-custom tbody tr {
        transition: var(--transition);
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    /* ACTION TILE STYLE */
    .action-tile {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.875rem 1rem;
        border-radius: 12px;
        border: 1px solid var(--card-border);
        background-color: #f8fafc;
        text-decoration: none;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .action-tile:hover {
        background-color: #ffffff;
        border-color: var(--green-text);
        color: var(--green-text);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
        transform: translateX(4px);
    }
    
    /* PREMIUM CARD LIGHT EFFECT */

.dashboard-card::before {

    content:"";

    position:absolute;

    inset:0;

    background:
    linear-gradient(
        120deg,
        rgba(34,197,94,.12),
        transparent 45%
    );

    opacity:0;

    transition:.4s;

}


.dashboard-card:hover::before {

    opacity:1;

}



/* ICON PREMIUM */

.icon-box-modern {

    box-shadow:
    0 8px 20px rgba(34,197,94,.15);

}



/* HEADER GLOW */

.dashboard-header-banner::before {

    content:"";

    position:absolute;

    width:220px;

    height:220px;

    background:
    rgba(34,197,94,.25);

    filter:blur(60px);

    right:80px;

    bottom:-80px;

}



/* BUTTON LEBIH PREMIUM */

.btn-quick-action {

    box-shadow:
    0 10px 25px rgba(0,0,0,.15);

}


.btn-quick-action:hover {

    transform:
    translateY(-4px)
    scale(1.03);

}



/* TABLE ROW MODERN */

.table-custom tbody tr:hover {

    background:
    rgba(34,197,94,.05);

}



/* EMPTY DATA */

.table-custom .text-center i {

    opacity:.7;

}



/* MOBILE */

@media(max-width:768px){

    .dashboard-header-banner {

        padding:1.5rem;

    }


    .dashboard-header-banner h1 {

        font-size:1.5rem!important;

    }


    .dashboard-card {

        border-radius:18px;

    }

}

</style>


<div class="container py-4">

    {{-- HEADER BANNER + FITUR QUICK ACTIONS & LIVE CLOCK --}}
    <div class="dashboard-header-banner mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index: 1;">
            <div class="col-lg-7">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <div class="date-badge">
                        <i class="bi bi-calendar3"></i>
                        <span>Hari ini &bull; {{ $tanggalHariIni->translatedFormat('l, d F Y') }}</span>
                    </div>
                    {{-- LIVE DIGITAL CLOCK --}}
                    <div class="date-badge">
                        <i class="bi bi-clock-history"></i>
                        <span id="liveClock">00:00:00 WIB</span>
                    </div>
                </div>
                <h1 class="fw-bold text-white mb-2 fs-2">
                    Selamat Datang Di POS Vlyhadi
                </h1>
                <p class="text-white-50 mb-0">Berikut adalah ringkasan aktivitas transaksi, inventaris, dan performa toko Anda.</p>
            </div>
            
            {{-- TOMBOL AKSES CEPAT --}}
            <div class="col-lg-5 text-lg-end d-flex flex-wrap gap-2 justify-content-lg-end">
                <a href="{{ route('penjualan.index') }}" class="btn-quick-action">
                    <i class="bi bi-cart-plus fs-5"></i> Kasir / Transaksi
                </a>
                <a href="{{ route('produk.index') }}" class="btn-quick-action">
                    <i class="bi bi-box-seam fs-5"></i> Kelola Produk
                </a>
            </div>
        </div>
    </div>


    {{-- SALES OVERVIEW (ADMIN/OWNER ONLY) --}}
    @can('viewAny', App\Models\User::class)

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <div class="icon-box-modern bg-green-subtle-custom me-3 shadow-sm">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
                <h4 class="section-title mb-0 fs-5">Penjualan Hari Ini</h4>
                <span class="text-muted small">Rincian performa keuangan harian</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">

        {{-- TOTAL PENJUALAN --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card h-100 p-3">
                <div class="card-top-accent bg-green-subtle-custom" style="background-color: var(--purple-text) !important;"></div>
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted small fw-bold text-uppercase">Total Pendapatan</span>
                        <div class="icon-box-modern bg-green-subtle-custom">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark fs-3">
                        Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}
                    </h3>
                    <span class="badge bg-success-subtle text-success rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="bi bi-arrow-up-short"></i> Omset Hari Ini
                    </span>
                </div>
            </div>
        </div>

        {{-- JUMLAH TRANSAKSI --}}
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('penjualan.index') }}" class="text-decoration-none">
                <div class="card dashboard-card h-100 p-3">
                    <div class="card-top-accent bg-info"></div>
                    <div class="card-body p-0">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted small fw-bold text-uppercase">Jumlah Transaksi</span>
                            <div class="icon-box-modern bg-info-subtle text-info">
                                <i class="bi bi-receipt"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-2 text-dark fs-3">
                            {{ number_format($ringkasan['total_transaksi'], 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Transaksi</span>
                        </h3>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Total pesanan selesai</span>
                            <span class="text-info small fw-bold">Lihat Semua <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- PEMBAYARAN TUNAI --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card h-100 p-3">
                <div class="card-top-accent bg-success"></div>
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted small fw-bold text-uppercase">Tunai (Cash)</span>
                        <div class="icon-box-modern bg-success-subtle text-success">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-2 fs-3">
                        Rp {{ number_format($ringkasan['total_cash'], 0, ',', '.') }}
                    </h3>
                    <span class="text-muted small">Uang di laci kasir</span>
                </div>
            </div>
        </div>

        {{-- PEMBAYARAN NON TUNAI --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card h-100 p-3">
                <div class="card-top-accent bg-primary"></div>
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted small fw-bold text-uppercase">Non Tunai (QRIS/TF)</span>
                        <div class="icon-box-modern bg-primary-subtle text-primary">
                            <i class="bi bi-credit-card"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-2 fs-3">
                        Rp {{ number_format($ringkasan['total_non_tunai'], 0, ',', '.') }}
                    </h3>
                    <span class="text-muted small">Transfer / QRIS</span>
                </div>
            </div>
        </div>

    </div>

    @endcan


    {{-- PRODUK TERLARIS & PUSAT KENDALI OPERASIONAL KASIR --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            {{-- BEST SELLER PRODUCTS --}}
            <div class="card dashboard-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box-modern bg-green-subtle-custom" style="width: 42px; height: 42px; font-size: 1.1rem;">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark fs-5">Produk Terlaris (Best Seller)</h5>
                            <span class="text-muted small">Item dengan performa penjualan tertinggi</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 mt-2">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Nama Produk</th>
                                    <th>Sisa Stok Saat Ini</th>
                                    <th class="pe-4 text-end">Total Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($produkTerlaris as $produk)
                                <tr>
                                    <td class="fw-semibold text-dark ps-4">
                                        <i class="bi bi-box-seam me-2 text-muted"></i>{{ $produk->nama }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-3 py-1.5 fw-normal rounded-pill">
                                            {{ $produk->stok }} Unit Sisa
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-green-subtle-custom px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-bag-check-fill me-1"></i> {{ number_format($produk->total_terjual, 0, ',', '.') }} Terjual
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <span>Belum ada data penjualan produk terlaris</span>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- WIDGET PUSAT AKSES CEPAT & SHIFT KASIR --}}
        <div class="col-lg-4">
            <div class="card dashboard-card h-100 p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box-modern bg-green-subtle-custom" style="width: 42px; height: 42px; font-size: 1.1rem;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark fs-6">Pusat Pintasan Kasir</h5>
                        <span class="text-muted small">Menu navigasi praktis harian</span>
                    </div>
                </div>

                {{-- DAFTAR TOMBOL AKSES CEPAT --}}
                <div class="d-flex flex-column gap-2 my-2">
                    <a href="{{ route('penjualan.create') ?? route('penjualan.index') }}" class="action-tile">
                        <i class="bi bi-plus-circle-fill text-primary fs-5"></i>
                        <span>Buat Transaksi Baru</span>
                    </a>
                    
                    <a href="{{ route('produk.create') ?? route('produk.index') }}" class="action-tile">
                        <i class="bi bi-box-seam-fill text-warning fs-5"></i>
                        <span>Tambah Stok / Produk</span>
                    </a>
                </div>

                {{-- INFORMASI SHIFT KASIR AKTIF --}}
                <div class="pt-3 border-top mt-auto">
                    <div class="p-3 rounded-3 bg-light border mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small">Petugas Kasir:</span>
                            <span class="fw-bold small text-dark">{{ auth()->user()->name ?? 'Kasir' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Status Peran:</span>
                            <span class="badge bg-primary-subtle text-primary fw-semibold text-capitalize">
                                @if(is_object(auth()->user()->role))
                                    {{ optional(auth()->user()->role)->name ?? '' ?? 'Petugas' }}
                                @else
                                    {{ auth()->user()->role ?? 'Petugas' }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fw-semibold">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Sistem Siap
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-shield-check text-success me-1"></i> POS Online
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- STATS RINGKASAN PRODUK & INVENTARIS --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <div class="icon-box-modern bg-warning-subtle text-warning me-3 shadow-sm">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div>
                <h4 class="section-title mb-0 fs-5">Status Inventaris</h4>
                <span class="text-muted small">Pantau ketersediaan produk di gudang</span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        {{-- STOK MENIPIS --}}
        <div class="col-lg-6">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                    <span class="fw-bold text-warning d-flex align-items-center gap-2 fs-6">
                        <i class="bi bi-exclamation-triangle-fill"></i> Stok Menipis
                    </span>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                        {{ method_exists($produkStokRendah, 'count') ? $produkStokRendah->count() : count($produkStokRendah) }} Produk
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 60px;">#</th>
                                    <th>Produk</th>
                                    <th class="pe-4 text-end">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($produkStokRendah as $index => $produk)
                                <tr>
                                    <td class="ps-4 text-muted small fw-semibold">
                                        {{ method_exists($produkStokRendah, 'firstItem') ? $produkStokRendah->firstItem() + $index : $loop->iteration }}
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $produk->nama }}</td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-bold">
                                            {{ $produk->stok }} Unit
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                                        <span>Stok barang masih dalam batas aman</span>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- STOK HABIS --}}
        <div class="col-lg-6">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                    <span class="fw-bold text-danger d-flex align-items-center gap-2 fs-6">
                        <i class="bi bi-x-circle-fill"></i> Stok Habis
                    </span>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                        {{ method_exists($produkStokHabis, 'count') ? $produkStokHabis->count() : count($produkStokHabis) }} Produk
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 60px;">#</th>
                                    <th>Produk</th>
                                    <th class="pe-4 text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($produkStokHabis as $index => $produk)
                                <tr>
                                    <td class="ps-4 text-muted small fw-semibold">
                                        {{ method_exists($produkStokHabis, 'firstItem') ? $produkStokHabis->firstItem() + $index : $loop->iteration }}
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $produk->nama }}</td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-bold">
                                            Habis (0)
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-emoji-smile-fill text-primary fs-1 d-block mb-2"></i>
                                        <span>Tidak ada stok produk yang habis</span>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- SCRIPT JAM REALTIME --}}
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('liveClock').textContent = `${hours}:${minutes}:${seconds} WIB`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@endsection