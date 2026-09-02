@extends('layouts.app')

@section('title', 'Produk - POS HADI')

@section('content')
@include('layouts.navbar')

<style>
    .product-hero{background:linear-gradient(135deg,#0b1f36 0%,#064e3b 55%,#22c55e 100%);border-radius:22px;color:#fff;position:relative;overflow:hidden;box-shadow:0 18px 45px rgba(21,128,61,.16)}
    .product-hero:after{content:"";position:absolute;width:330px;height:330px;right:-110px;top:-180px;border-radius:50%;background:rgba(255,255,255,.08)}
    .product-stat{height:100%;background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 10px 28px rgba(15,23,42,.05)}
    .stat-icon{width:44px;height:44px;border-radius:13px;display:grid;place-items:center;flex:0 0 auto}
    .filter-card{background:#fff;border:1px solid #e5e7eb;border-radius:18px;box-shadow:0 10px 28px rgba(15,23,42,.05)}
    .product-table thead th{padding:13px 12px;background:#f8fafc;color:#64748b;font-size:.68rem;letter-spacing:.07em;text-transform:uppercase;white-space:nowrap}
    .product-table tbody td{padding:13px 12px;border-color:#eef2f7;vertical-align:middle}
    .product-table tbody tr:hover{background:#f8fffa}
    .product-thumb{width:46px;height:46px;object-fit:cover;border-radius:11px;border:1px solid #e5e7eb;background:#f8fafc}
    .product-placeholder{width:46px;height:46px;border-radius:11px;background:#dcfce7;color:#15803d;display:grid;place-items:center;font-size:19px}
    .stock-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;border-radius:999px;font-size:.72rem;font-weight:700}
    .stock-ready{background:#dcfce7;color:#15803d}.stock-low{background:#fef3c7;color:#b45309}.stock-empty{background:#fee2e2;color:#b91c1c}
    .status-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;border-radius:999px;font-size:.7rem;font-weight:700}
    .status-active{background:#dcfce7;color:#15803d}.status-off{background:#f1f5f9;color:#64748b}
    .action-btn{width:34px;height:34px;border:0;border-radius:10px;display:inline-grid;place-items:center;transition:.18s}.action-btn:hover{transform:translateY(-1px)}
    .action-view{background:#e0f2fe;color:#0369a1}.action-edit{background:#fef3c7;color:#b45309}.action-delete{background:#fee2e2;color:#b91c1c}
    .search-control{height:44px;border-radius:12px;border:1px solid #dbe2ea;padding-left:40px}
    .search-wrap{position:relative}.search-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#15803d;z-index:2}
    .filter-control{height:44px;border-radius:12px;border:1px solid #dbe2ea;font-size:.85rem}
    .empty-box{padding:60px 20px;text-align:center;color:#94a3b8}.empty-box i{font-size:42px;display:block;margin-bottom:10px}
    .pagination-wrap{padding:16px 20px;border-top:1px solid #eef2f7}
    @media(max-width:767px){.product-hero{border-radius:16px}.product-table{min-width:930px}.product-hero-actions{width:100%}.product-hero-actions>*{flex:1}.product-stat{padding:14px}}
    @media print{.no-print,.pos-sidebar,.pos-mobile-toggle,.pos-sidebar-backdrop{display:none!important}.pos-page{margin:0!important;padding:0!important}.product-hero{background:#fff!important;color:#000!important;box-shadow:none!important;border-radius:0;border-bottom:2px solid #111}.product-hero *{color:#000!important}.filter-card{box-shadow:none}.product-table{min-width:0!important}.product-table th:last-child,.product-table td:last-child{display:none}}
</style>

<main class="pos-page">
<div class="pos-container">
    <section class="product-hero p-4 p-md-5 mb-4">
        <div class="position-relative" style="z-index:1">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <div class="small opacity-75 mb-1">INVENTAR TOKO</div>
                    <h1 class="h3 fw-bold mb-1"><i class="bi bi-box-seam-fill me-2"></i>Kelola Produk</h1>
                    <p class="mb-0 opacity-75 small">Kelola barang, harga, stok, kategori, dan status produk dalam satu halaman.</p>
                </div>
                <div class="d-flex gap-2 product-hero-actions no-print">
                    <button type="button" onclick="window.print()" class="btn btn-outline-light rounded-pill px-3 fw-semibold">
                        <i class="bi bi-printer me-1"></i> Cetak
                    </button>
                    @can('create', App\Models\Produk::class)
                        <a href="{{ route('produk.create') }}" class="btn btn-light rounded-pill px-4 fw-bold" style="color:#15803d">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </section>

    <div class="row g-3 mb-4">
        @php
            $statItems = [
                ['label'=>'Total Produk','value'=>$stats['total'],'icon'=>'bi-boxes','class'=>'bg-success-subtle text-success'],
                ['label'=>'Total Stok','value'=>$stats['stok'],'icon'=>'bi-stack','class'=>'bg-info-subtle text-info'],
                ['label'=>'Stok Kritis','value'=>$stats['kritis'],'icon'=>'bi-exclamation-triangle-fill','class'=>'bg-warning-subtle text-warning'],
                ['label'=>'Stok Habis','value'=>$stats['habis'],'icon'=>'bi-x-circle-fill','class'=>'bg-danger-subtle text-danger'],
            ];
        @endphp
        @foreach($statItems as $stat)
            <div class="col-6 col-lg-3">
                <div class="product-stat d-flex align-items-center gap-3">
                    <div class="stat-icon {{ $stat['class'] }}"><i class="bi {{ $stat['icon'] }} fs-5"></i></div>
                    <div class="min-w-0"><div class="text-muted small">{{ $stat['label'] }}</div><div class="fs-5 fw-bold text-dark">{{ number_format($stat['value'], 0, ',', '.') }}</div></div>
                </div>
            </div>
        @endforeach
    </div>

    <section class="filter-card overflow-hidden">
        <div class="p-3 p-md-4 border-bottom">
            <form method="GET" action="{{ route('produk.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-lg-4">
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control search-control" placeholder="Cari nama atau kategori...">
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <select name="category_id" class="form-select filter-control">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string)request('category_id') === (string)$category->id)>
                                {{ $category->nama }}{{ !$category->status ? ' (Nonaktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <select name="stok_status" class="form-select filter-control">
                        <option value="">Semua Stok</option>
                        <option value="ready" @selected(request('stok_status') === 'ready')>Stok Aman</option>
                        <option value="kritis" @selected(request('stok_status') === 'kritis')>Stok Kritis</option>
                        <option value="habis" @selected(request('stok_status') === 'habis')>Stok Habis</option>
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <select name="status" class="form-select filter-control">
                        <option value="">Semua Status</option>
                        <option value="1" @selected(request('status') === '1')>Aktif</option>
                        <option value="0" @selected(request('status') === '0')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-6 col-lg-2 d-flex gap-2">
                    <button class="btn btn-success rounded-3 flex-grow-1 fw-semibold"><i class="bi bi-funnel me-1"></i>Filter</button>
                    @if(request()->hasAny(['search','category_id','stok_status','status']))
                        <a href="{{ route('produk.index') }}" class="btn btn-light border rounded-3" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table product-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:60px">No</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $products->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($product->foto)
                                    <img src="{{ asset('storage/'.$product->foto) }}" alt="{{ $product->nama }}" class="product-thumb">
                                @else
                                    <div class="product-placeholder"><i class="bi bi-box-seam"></i></div>
                                @endif
                                <div class="min-w-0">
                                    <a href="{{ route('produk.show', $product) }}" class="fw-bold text-dark d-block text-truncate" style="max-width:220px">{{ $product->nama }}</a>
                                    <small class="text-muted">{{ $product->satuan }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border fw-semibold">
                                <i class="{{ $product->category?->icon ?: 'bi bi-tags' }} me-1" style="color:{{ $product->category?->warna ?: '#16a34a' }}"></i>
                                {{ $product->category?->nama ?? 'Tanpa kategori' }}
                            </span>
                        </td>
                        <td class="text-muted small">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                        <td class="fw-bold text-dark small">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stok <= 0)
                                <span class="stock-pill stock-empty"><i class="bi bi-x-circle-fill"></i> Habis</span>
                            @elseif($product->stok <= $product->minimum_stok)
                                <span class="stock-pill stock-low"><i class="bi bi-exclamation-triangle-fill"></i> {{ $product->stok }} {{ $product->satuan }}</span>
                            @else
                                <span class="stock-pill stock-ready"><i class="bi bi-check-circle-fill"></i> {{ $product->stok }} {{ $product->satuan }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->status)
                                <span class="status-pill status-active"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                            @else
                                <span class="status-pill status-off"><i class="bi bi-pause-circle-fill"></i> Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $product->user?->name ?? '-' }}</td>
                        <td class="text-end pe-4 no-print">
                            <div class="d-inline-flex gap-1">
                                @can('view', $product)
                                    <a href="{{ route('produk.show', $product) }}" class="action-btn action-view" title="Detail"><i class="bi bi-eye"></i></a>
                                @endcan
                                @can('update', $product)
                                    <a href="{{ route('produk.edit', $product) }}" class="action-btn action-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                @endcan
                                @can('delete', $product)
                                    <form method="POST" action="{{ route('produk.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Hapus produk {{ addslashes($product->nama) }}? Jika produk sudah pernah dipakai dalam transaksi, produk tidak dapat dihapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn action-delete" title="Hapus"><i class="bi bi-trash3"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9"><div class="empty-box"><i class="bi bi-box-seam"></i><strong class="d-block text-dark mb-1">Belum ada produk</strong><span>Tambahkan produk baru atau ubah filter pencarian.</span></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="pagination-wrap d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <small class="text-muted">Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} produk</small>
                {{ $products->links() }}
            </div>
        @endif
    </section>
</div>
</main>
@endsection
