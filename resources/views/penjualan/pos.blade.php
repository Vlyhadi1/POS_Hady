@extends('layouts.app')

@section('title', 'Sistem Kasir (POS)')

@section('content')

@include('layouts.navbar')

<style>
    /* Palette & Base Styling */
    :root {
    --green-main: #22c55e;
    --green-dark: #15803d;
    --green-light: #4ade80;
    --green-soft: #dcfce7;
    --bg-slate: #f8fafc;

    }

    body {
        background-color: var(--bg-slate) !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Banner Gradient POS Header */
    .banner-green-gradient {
    background: linear-gradient(
        135deg,
        var(--green-dark) 0%,
        var(--green-main) 50%,
        var(--green-light) 100%
    ) !important;
    color: #ffffff !important;
    }

    /* Card Styling */
    .custom-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
    }

    .bg-table-head {
        background-color: #f8fafc !important;
    }

    .table-head-text {
        color: #6b7280 !important;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Interactive Product Items */
    .product-select-btn {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .product-select-btn:hover {
    border-color: #22c55e;
    background-color: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.12);
    }

    /* Search Box Focus State */
    .bg-search {
        background-color: #f1f5f9 !important;
        border-color: #e2e8f0 !important;
        transition: all 0.2s ease;
    }

    .search-box:focus-within .bg-search {
        background-color: #ffffff !important;
        border-color: var(--green-main) !important;
    }

    /* Badges & Soft Color Elements */
    .badge-soft-green {
    background-color: #dcfce7 !important;
    color: #15803d !important;
    border: 1px solid #bbf7d0 !important;
    }

    /* Action Delete Soft */
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

    /* Checkout Banner / Receipt Box */
    .total-receipt-box {
    background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%);
    border: 1px dashed #86efac;
    border-radius: 12px;
    }

/* QRIS Custom Box */
.qris-card {
    background: #ffffff;
    border: 2px dashed #22c55e;
    border-radius: 16px;
    }
</style>

<main class="pos-page"><div class="pos-container">

    {{-- ALERTS --}}
    @if(session('error'))
        <div class="alert alert-danger rounded-3 shadow-sm mb-4 border-0 border-start border-4 border-danger">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm mb-4 border-0 border-start border-4 border-success">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    {{-- HEADER BANNER GRADIENT --}}
    <div class="banner-green-gradient p-4 p-md-5 rounded-4 mb-4 position-relative overflow-hidden shadow-sm">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative" style="z-index: 1;">
            <div>
                <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-calculator-fill fs-2"></i> Transaksi Kasir (POS)
                </h2>
                <p class="text-white opacity-75 small mb-0">Pilih item barang di sebelah kiri dan kelola keranjang belanja di sebelah kanan.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('penjualan.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold d-inline-flex align-items-center gap-2" style="color: #15803d !important;">
                    <i class="bi bi-arrow-left"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </div>
        </div>
        {{-- Decorative Icon Background --}}
        <div class="position-absolute end-0 bottom-0 opacity-25 pe-4 pb-2 d-none d-md-block">
            <i class="bi bi-cart-check-fill text-white" style="font-size: 5rem;"></i>
        </div>
    </div>

    <div class="row g-4">

        {{-- ==================== KATALOG PRODUK ==================== --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 custom-card h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-grid-fill" style="color: #15803d;"></i> Katalog Produk
                        </h5>
                        <span class="badge badge-soft-green px-2.5 py-1 rounded-pill small">
                            {{ count($products) }} Barang
                        </span>
                    </div>

                    {{-- Form Pencarian Produk Real-time --}}
                    <form method="GET" action="{{ route('penjualan.create') }}" id="searchForm">
                        <div class="input-group search-box">
                            <span class="input-group-text border-end-0 text-muted rounded-start-pill ps-3 bg-search">
                                <i class="bi bi-search" style="color: #15803d;"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                id="searchInput"
                                value="{{ request('search') }}"
                                class="form-control border-start-0 rounded-end-pill ps-0 bg-search shadow-none"
                                placeholder="Cari nama produk..."
                                onkeyup="filterProducts()"
                            >
                        </div>
                    </form>
                </div>

                <div class="card-body px-4 pb-4" style="max-height: 65vh; overflow-y: auto;">
                    <div class="d-flex flex-column gap-2" id="productList">
                        @forelse($products as $product)
                            <div class="product-item" data-name="{{ strtolower($product->nama) }}">
                                <form method="POST" action="{{ route('itempenjualan.store') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="p-2 rounded-3 product-select-btn">
                                        <div class="row align-items-center g-2">
                                            <div class="col-7 col-sm-7">
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($product->foto)
                                                        <img src="{{ asset('storage/'.$product->foto) }}"
                                                             alt="{{ $product->nama }}"
                                                             class="rounded-3 shadow-sm border"
                                                             style="width:48px; height:48px; object-fit:cover;">
                                                    @else
                                                        <div class="rounded-3 shadow-sm d-flex align-items-center justify-content-center bg-light fw-bold" style="width:48px; height:48px; color: #15803d;">
                                                            <i class="bi bi-box-seam fs-5"></i>
                                                        </div>
                                                    @endif
                                                    <div class="text-truncate">
                                                        <div class="fw-bold text-dark text-truncate">{{ $product->nama }}</div>
                                                        <div class="fw-semibold small" style="color: #15803d;">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-3 col-sm-3">
                                                <input type="number"
                                                       name="quantity"
                                                       value="1"
                                                       min="1"
                                                       class="form-control form-control-sm text-center border-1 shadow-none rounded-pill {{ $sale->status === 'COMPLETED' ? 'readonly' : '' }}"
                                                       style="background-color: #f8fafc;"
                                                       onclick="event.stopPropagation();">
                                            </div>

                                            <div class="col-2 col-sm-2 text-end">
                                               <button type="submit" 
                                                        class="btn btn-sm text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}" 
                                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #15803d 0%, #22c55e 100%);" 
                                                                title="Tambah ke Keranjang">
                                                    <i class="bi bi-plus-lg fw-bold"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam text-secondary fs-1 d-block mb-2"></i>
                                <span>Produk tidak ditemukan.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== KERANJANG BELANJA ==================== --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 custom-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-cart3" style="color: #15803d;"></i> Keranjang
                                <span class="badge rounded-pill bg-danger fs-6 fw-normal px-2" style="font-size: 0.75rem !important;">
                                    {{ $sale->itemPenjualan->sum('kuantitas') }} Item
                                </span>
                            </h5>
                            <span class="badge badge-soft-green px-3 py-1 rounded-pill fw-semibold">
                                Transaksi #{{ $sale->id }}
                            </span>
                        </div>
                    </div>

                    {{-- Tabel Item Keranjang --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-table">
                            <thead class="bg-table-head border-0">
                                <tr class="table-head-text small">
                                    <th class="ps-4">PRODUK</th>
                                    <th>HARGA</th>
                                    <th style="width: 18%;">QTY</th>
                                    <th>SUBTOTAL</th>
                                    <th class="pe-4 text-end" style="width: 10%;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->itemPenjualan as $item)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-semibold text-dark small d-block">{{ $item->produk->nama }}</span>
                                    </td>
                                    <td class="text-muted small">
                                        Rp {{ number_format($item->produk->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('itempenjualan.update', $item->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="number"
                                                   name="quantity"
                                                   value="{{ $item->kuantitas }}"
                                                   min="1"
                                                   onchange="this.form.submit()"
                                                   class="form-control form-control-sm text-center border-1 shadow-none rounded-2"
                                                   style="background-color: #f8fafc;"
                                                   {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}>
                                        </form>
                                    </td>
                                    <td class="fw-bold small" style="color: #15803d;">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="pe-4 text-end">
                                        @can('delete', $item)
                                        <form method="POST" action="{{ route('itempenjualan.destroy', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus Item" {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}>
                                                <i class="bi bi-trash-fill small"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x text-secondary fs-1 d-block mb-2"></i>
                                        <span>Keranjang belanja masih kosong.</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FOOTER / TOTAL & CHECKOUT --}}
                <div class="card-footer bg-white border-0 p-4 border-top">
                    <div class="total-receipt-box p-3 mb-3 text-center">
                        <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Total Pembayaran</span>
                        <h2 class="fw-bold mb-0" style="color: #15803d;">
                            Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}
                        </h2>
                    </div>

                    {{-- Form Checkout --}}
                    <form id="checkoutForm" method="POST" action="{{ route('penjualan.update', $sale->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <select id="paymentMethodSelect" name="payment_method" class="form-select border-1 rounded-pill px-3 shadow-none bg-light" required {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="CASH">Cash / Tunai</option>
                                <option value="QRIS">QRIS (Scan Barcode)</option>
                                <option value="TRANSFER">Transfer Bank</option>
                            </select>
                        </div>

                        <div id="cashPaymentBox" class="mb-3 d-none">
                            <label for="amountPaid" class="form-label fw-semibold">Uang Dibayar</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" step="1" id="amountPaid" name="amount_paid" class="form-control" placeholder="Masukkan uang pelanggan">
                            </div>
                            <div class="small text-muted mt-2">Kembalian: <strong id="changeAmount">Rp 0</strong></div>
                        </div>

                        <button type="button" onclick="handleCheckout()" class="btn btn-success w-100 rounded-pill py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Selesaikan & Checkout</span>
                        </button>
                    </form>

                    {{-- Tombol Cetak Struk --}}
                    @if($sale->status === 'COMPLETED')
                        <a href="javascript:window.print()" class="btn btn-dark w-100 rounded-pill py-2 mt-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-printer-fill"></i>
                            <span>Cetak Struk Pembayaran</span>
                        </a>
                    @endif

                    {{-- Form Batal Transaksi --}}
                    @can('delete', $sale)
                    <form action="{{ route('penjualan.destroy', $sale->id) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')" class="mt-2">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-outline-danger w-100 rounded-pill py-2 border-0 small fw-semibold d-flex align-items-center justify-content-center gap-1 {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">
                            <i class="bi bi-x-circle"></i>
                            <span>Batalkan Transaksi</span>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

    </div>

</div>

{{-- ==================== MODAL POPUP QRIS ==================== --}}
<div class="modal fade" id="qrisModal" tabindex="-1" aria-labelledby="qrisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #15803d 0%, #22c55e 100%);">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="qrisModalLabel">
                    <i class="bi bi-qr-code-scan"></i> Pembayaran QRIS
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted small mb-3">Scan QR Code menggunakan aplikasi E-Wallet / Mobile Banking Anda.</p>
                
                {{-- Box Barcode QRIS --}}
                <div class="qris-card p-4 d-inline-block shadow-sm mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=POS-TRX-{{ $sale->id }}-TOTAL-{{ $sale->total_pembayaran }}" 
                         alt="QRIS Barcode" 
                         class="img-fluid rounded-3 mb-2">
                    <div class="d-flex align-items-center justify-content-center gap-2 text-muted small fw-bold">
                        <i class="bi bi-shield-check text-success"></i> QRIS NATIONAL STANDARD
                    </div>
                </div>

                <div class="text-dark fw-bold fs-4 mb-1" style="color: #15803d !important;">
                    Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}
                </div>
                
                <div class="badge bg-warning text-dark px-3 py-2 rounded-pill small mb-3">
                    <i class="bi bi-clock"></i> Sisa Waktu: <span id="qrisTimer">05:00</span>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" onclick="submitFinalCheckout()" class="btn text-white fw-bold rounded-pill py-2.5 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                        <i class="bi bi-check2-circle"></i> Selesaikan Pembayaran
                    </button>
                    <button type="button" class="btn btn-light rounded-pill text-muted border py-2" data-bs-dismiss="modal">
                        Batal / Ganti Metode
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT INTERAKTIF --}}
<script>
    // Filtering Katalog Produk Real-time
    function filterProducts() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const items = document.querySelectorAll('.product-item');

        items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(input)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    }

    // Modal QRIS Handler
    let timerInterval;

    function handleCheckout() {
        const paymentMethod = document.getElementById('paymentMethodSelect').value;
        const totalAmount = {{ $sale->total_pembayaran }};
        const amountPaid = parseInt(document.getElementById('amountPaid')?.value || '0', 10);

        if (!paymentMethod) {
            alert('Silakan pilih metode pembayaran terlebih dahulu.');
            return;
        }

        if (totalAmount <= 0) {
            alert('Keranjang belanja masih kosong!');
            return;
        }

        if (paymentMethod === 'CASH' && amountPaid < totalAmount) {
            alert('Uang dibayar belum mencukupi total transaksi.');
            return;
        }

        if (paymentMethod === 'QRIS') {
            const qrisModal = new bootstrap.Modal(document.getElementById('qrisModal'));
            qrisModal.show();
            startQrisTimer(300); // Timer 5 Menit
        } else {
            if (confirm('Yakin ingin menyelesaikan transaksi ini?')) {
                document.getElementById('checkoutForm').submit();
            }
        }
    }

    function startQrisTimer(duration) {
        let timer = duration, minutes, seconds;
        clearInterval(timerInterval);
        
        timerInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            document.getElementById('qrisTimer').textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(timerInterval);
                alert('Waktu pembayaran QRIS telah habis!');
                const qrisModalEl = document.getElementById('qrisModal');
                const modal = bootstrap.Modal.getInstance(qrisModalEl);
                if (modal) modal.hide();
            }
        }, 1000);
    }

    document.getElementById('paymentMethodSelect')?.addEventListener('change', function () {
        const box = document.getElementById('cashPaymentBox');
        if (this.value === 'CASH') box.classList.remove('d-none'); else box.classList.add('d-none');
        updateChange();
    });

    document.getElementById('amountPaid')?.addEventListener('input', updateChange);

    function updateChange() {
        const total = {{ $sale->total_pembayaran }};
        const paid = parseInt(document.getElementById('amountPaid')?.value || '0', 10);
        const change = Math.max(0, paid - total);
        document.getElementById('changeAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(change);
    }

    function submitFinalCheckout() {
        document.getElementById('checkoutForm').submit();
    }
</script>

@endsection