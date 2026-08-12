@extends('layouts.app')

@section('title', 'Kelola Produk - Vlyhadi')

@section('content')

@include('layouts.navbar')

<style>
    /* Color Palette & Variables */
    :root {
  --green-main:#22c55e;
    --green-dark:#15803d;
    --bg-slate:#f8fafc;
    }

    body {
        background-color: var(--bg-slate) !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Gradient Header Banner */
    .banner-green-gradient {
    background: linear-gradient(
        135deg,
        #020617 0%,
        #064e3b 55%,
        #22c55e 100%
    ) !important;
    color: #ffffff !important;
}

    /* Custom Card & Table Styling */
    .custom-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
    }

    .stat-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
    }

    .bg-table-head {
        background-color: #f8fafc !important;
    }

    .table-head-text {
        color: #6b7280 !important;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .product-name-text {
        color: #1e1b4b !important;
        font-weight: 600;
        transition: color 0.15s ease;
    }

    /* Table Hover Interactions */
    .custom-table tbody tr {
        transition: background-color 0.15s ease;
    }

        .custom-table tbody tr:hover {
        background-color:#f0fdf4 !important;
    }

        .custom-table tbody tr:hover .product-name-text {
        color:#15803d !important;
    }

    /* Search Box & Inputs */
    .bg-search {
        background-color: #f1f5f9 !important;
        border-color: #e2e8f0 !important;
        transition: all 0.2s ease;
    }

    .search-box:focus-within .bg-search {
    background-color:#ffffff !important;
    border-color:#22c55e !important;
    }

    .search-box input:focus {
    box-shadow:none !important;
    border-color:#22c55e !important;
    }

    /* Badges & Soft Color Elements */
    .badge-soft-green {
    background-color:#dcfce7 !important;
    color:#15803d !important;
    border:1px solid #a7f3d0 !important;
    }

    .badge-soft-amber {
        background-color: #fef3c7 !important;
        color: #b45309 !important;
        border: 1px solid #fde68a !important;
    }

    .badge-soft-danger {
        background-color: #fee2e2 !important;
        color: #b91c1c !important;
        border: 1px solid #fca5a5 !important;
    }

    /* Action Buttons */
    .btn-action-info {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: none !important;
        transition: all 0.2s ease;
    }
    .btn-action-info:hover {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        transform: scale(1.08);
    }

    .btn-action-edit {
        background-color: #fef3c7 !important;
        color: #d97706 !important;
        border: none !important;
        transition: all 0.2s ease;
    }
    .btn-action-edit:hover {
        background-color: #d97706 !important;
        color: #ffffff !important;
        transform: scale(1.08);
    }

    .btn-action-delete {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
        border: none !important;
        transition: all 0.2s ease;
    }
    .btn-action-delete:hover {
        background-color: #dc2626 !important;
        color: #ffffff !important;
        transform: scale(1.08);
    }

    /* Thumbnail styling */
    .product-thumb {
        width: 44px;
        height: 44px;
        object-fit: cover;
        border-radius: 10px;
    }

   .product-thumb-placeholder {
    width:44px;
    height:44px;
    border-radius:10px;
    background-color:#dcfce7;
    color:#15803d;
}

/* PRODUCT CARD GRID */
.product-card{
    background:white;
    border-radius:20px;
    border:1px solid #e2e8f0;
    overflow:hidden;
    transition:.3s;
}

.product-card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(0,0,0,.08);
}

.product-image-box{
    height:220px;
    background:#f8fafc;
    position:relative;
}

.product-image{
    width:100%;
    height:100%;
    object-fit:cover;
}

.no-image{
    height:220px;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:50px;
    color:#94a3b8;
}

.stock-badge{
    position:absolute;
    top:15px;
    right:15px;
    padding:6px 12px;
    border-radius:50px;
    font-size:12px;
    font-weight:700;
}

.success{
    background:#dcfce7;
    color:#15803d;
}

.warning{
    background:#fef3c7;
    color:#b45309;
}

.danger{
    background:#fee2e2;
    color:#dc2626;
}

.product-title{
    font-size:18px;
    font-weight:700;
}

.category-badge{
    background:#dcfce7;
    color:#15803d;
    padding:5px 12px;
    border-radius:50px;
    font-size:12px;
}

.price{
    font-size:20px;
    font-weight:800;
    color:#15803d;
}

.btn-card{
    width:38px;
    height:38px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    border:none;
}

.info{
    background:#e0f2fe;
    color:#0369a1;
}

.edit{
    background:#fef3c7;
    color:#d97706;
}

.delete{
    background:#fee2e2;
    color:#dc2626;
}

</style>

<div class="container py-4">

    {{-- HEADER BANNER GRADIENT --}}
    <div class="banner-green-gradient p-4 p-md-5 rounded-4 mb-4 position-relative overflow-hidden shadow-sm">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative" style="z-index: 1;">
            <div>
                <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill fs-2"></i> Kelola Produk
                </h2>
                <p class="text-white opacity-75 small mb-0">Kelola inventaris, harga jual, dan stok barang toko Anda secara terpusat.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button onclick="window.print()" class="btn btn-outline-light rounded-pill px-3 shadow-sm fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-printer-fill"></i>
                    <span>Cetak Data</span>
                </button>

                @can('create', App\Models\Produk::class)
                    <a href="{{ route('produk.create') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold d-inline-flex align-items-center gap-2" style="color:#15803d !important;">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Tambah Produk</span>
                    </a>
                @endcan
            </div>
        </div>
        <div class="position-absolute end-0 bottom-0 opacity-25 pe-4 pb-2 d-none d-md-block">
            <i class="bi bi-bag-check-fill text-white" style="font-size: 5rem;"></i>
        </div>
    </div>

    {{-- STATISTIK RINGKASAN PRODUK --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center"
                    style="background:#dcfce7; color:#15803d; width: 48px; height: 48px;">
                        <i class="bi bi-boxes fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Produk</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ method_exists($products, 'total') ? $products->total() : count($products) }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: #dcfce7; color: #16a34a; width: 48px; height: 48px;">
                        <i class="bi bi-stack fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Item Stok</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ $products->sum('stok') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: #fef3c7; color: #d97706; width: 48px; height: 48px;">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Stok Kritis</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ $products->where('stok', '<=', 10)->where('stok', '>', 0)->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: #fee2e2; color: #dc2626; width: 48px; height: 48px;">
                        <i class="bi bi-x-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Stok Habis</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ $products->where('stok', 0)->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 custom-card">
        
        {{-- CARD HEADER / FILTER & SEARCH --}}
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <form action="{{ route('produk.index') }}" method="GET">
                <div class="row g-2 justify-content-between align-items-center">
                    
                    {{-- SEARCH BAR --}}
                    <div class="col-md-5 col-lg-4">
                        <div class="input-group search-box">
                            <span class="input-group-text border-end-0 text-muted rounded-start-pill ps-3 bg-search">
                                <i class="bi bi-search" style="color:#15803d;"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control border-start-0 rounded-end-pill ps-0 bg-search shadow-none"
                                placeholder="Cari nama produk..."
                                id="fastSearchInput"
                            >
                        </div>
                    </div>

                    {{-- FILTER STATUS STOK --}}
                    <div class="col-md-7 col-lg-6 d-flex align-items-center justify-content-md-end gap-2">
                        <select name="stok_status" class="form-select bg-search rounded-pill shadow-none border-0 w-auto" onchange="this.form.submit()">
                            <option value="">Semua Status Stok</option>
                            <option value="ready" {{ request('stok_status') == 'ready' ? 'selected' : '' }}>Stok Tersedia (>10)</option>
                            <option value="kritis" {{ request('stok_status') == 'kritis' ? 'selected' : '' }}>Stok Kritis (1-10)</option>
                            <option value="habis" {{ request('stok_status') == 'habis' ? 'selected' : '' }}>Stok Habis (0)</option>
                        </select>

                        @if(request('search') || request('stok_status'))
                            <a href="{{ route('produk.index') }}" class="btn btn-sm btn-light rounded-pill px-3 text-muted border-0">
                                <i class="bi bi-x-circle me-1"></i>Reset
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>

        {{-- TABLE SECTION --}}
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-table-head border-0">
                        <tr class="table-head-text small">
                            <th class="ps-4" style="width: 5%;">NO</th>
                            <th style="width: 8%;">FOTO</th>
                            <th>NAMA PRODUK</th>
                            <th>DITAMBAHKAN</th>
                            <th>HARGA BELI</th>
                            <th>HARGA JUAL</th>
                            <th>STOK</th>
                            <th class="pe-4 text-end" style="width: 15%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($products as $product)
                        <tr class="product-row">
                            <td class="ps-4 text-muted small fw-medium">
                                {{ method_exists($products, 'firstItem') ? $products->firstItem() + $loop->index : $loop->iteration }}
                            </td>
                            <td>
                                @if($product->foto)
                                    <img src="{{ asset('storage/'.$product->foto) }}" alt="{{ $product->nama }}" class="product-thumb shadow-sm border">
                                @else
                                    <div class="product-thumb-placeholder d-flex align-items-center justify-content-center fw-bold shadow-sm">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="product-name-text d-block">{{ $product->nama }}</span>
                            </td>
                            <td class="text-muted small">
                                <span class="badge badge-soft-green px-2 py-1 rounded-pill fw-normal">
                                    <i class="bi bi-person me-1"></i>{{ $product->user->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-secondary small fw-medium">
                                Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="fw-bold text-dark small">
                                Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($product->stok > 10)
                                    <span class="badge badge-soft-emerald px-3 py-1 rounded-pill fw-semibold">
                                        {{ $product->stok }} Pcs
                                    </span>
                                @elseif($product->stok > 0)
                                    <span class="badge badge-soft-amber px-3 py-1 rounded-pill fw-semibold">
                                        {{ $product->stok }} Pcs (Kritis)
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger px-3 py-1 rounded-pill fw-semibold">
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- DETAIL --}}
                                    @can('view', $product)
                                        <a href="{{ route('produk.show', $product) }}" class="btn btn-action-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" title="Lihat Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    @endcan

                                    {{-- EDIT --}}
                                    @can('update', $product)
                                        <a href="{{ route('produk.edit', $product) }}" class="btn btn-action-edit rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" title="Edit Produk">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    @endcan

                                    {{-- DELETE (Panggil Modal Global) --}}
                                    @can('delete', $product)
                                        <button type="button" 
                                                class="btn btn-action-delete rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                style="width: 34px; height: 34px;" 
                                                title="Hapus Produk" 
                                                onclick="triggerDeleteModal(
                                                    {{ Js::from(route('produk.destroy', $product)) }},
                                                    {{ Js::from($product->nama) }}
                                                )">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam text-secondary fs-1 d-block mb-2"></i>
                                <span>Tidak ada data produk yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION FOOTER --}}
        @if(method_exists($products, 'hasPages') && $products->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-3 border-top">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <span class="small text-muted">
                        Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                    </span>
                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>

{{-- MODAL GLOBAL KONFIRMASI HAPUS PRODUK (DITARUH DI OUTSIDE TABLE) --}}
<div class="modal fade" id="globalDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-center p-3">
            <div class="modal-body p-3">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-exclamation-triangle-fill fs-2"></i>
                </div>
                <h5 class="fw-bold mb-1 text-dark">Hapus Produk?</h5>
                <p class="text-muted small mb-0">Apakah Anda yakin ingin menghapus produk <strong id="deleteProductNameText" class="text-dark"></strong>? Data ini tidak bisa dikembalikan.</p>
            </div>
            <div class="d-flex gap-2 justify-content-center px-3 pb-2">
                <button type="button" class="btn btn-light rounded-pill px-3 fw-semibold w-50" data-bs-dismiss="modal">Batal</button>
                <form id="globalDeleteForm" action="" method="POST" class="w-50">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-3 fw-semibold w-100">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Trigger Modal Hapus Produk Presisi
    function triggerDeleteModal(deleteUrl, productName) {
        document.getElementById('deleteProductNameText').innerText = `"${productName}"`;
        document.getElementById('globalDeleteForm').action = deleteUrl;
        
        var myModal = new bootstrap.Modal(document.getElementById('globalDeleteModal'));
        myModal.show();
    }

    // Instant Fast Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('fastSearchInput');
        const tableRows = document.querySelectorAll('.custom-table tbody tr.product-row');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const keyword = this.value.toLowerCase();

                tableRows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    if (text.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>

@endsection