@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $penjualan->id)

@section('content')

@include('layouts.navbar')

<style>
   :root {
    --green-main: #22c55e;
    --green-dark: #15803d;
    --green-light: #4ade80;
    --green-soft: #dcfce7;
}

    .banner-green-gradient {
       background: linear-gradient(
        135deg,
        #064e3b 0%,
        #15803d 50%,
        #22c55e 100%
        ) !important;
        color: #ffffff !important;
    }
    
   .text-green {
    color: var(--green-dark) !important;
    }

    .bg-green-subtle {
    background-color: var(--green-soft) !important;
    color: var(--green-dark) !important;
    }

    .card-detail {
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .qr-container {
        background: #ffffff;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        display: inline-block;
    }

    /* CSS Khusus Mode Cetak / Print Struk */
    @media print {
        .no-print, nav, .navbar {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .card-detail {
            border: none !important;
            box-shadow: none !important;
        }
        .banner-green-gradient {
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

<main class="pos-page"><div class="pos-container">

    {{-- HEADER BANNER --}}
    <div class="banner-green-gradient p-4 rounded-4 mb-4 shadow-sm">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-receipt fs-2"></i> Detail Transaksi #{{ $penjualan->id }}
                </h2>
                <p class="text-white opacity-75 small mb-0">Informasi rincian transaksi dan daftar barang yang dibeli.</p>
            </div>
            
            {{-- ACTION BUTTONS --}}
            <div class="d-flex align-items-center gap-2 no-print">
                <button onclick="window.print()" class="btn btn-light rounded-pill px-3 fw-semibold text-green shadow-sm d-flex align-items-center gap-1">
                    <i class="bi bi-printer-fill"></i> Cetak Struk
                </button>
                <a href="{{ route('penjualan.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        {{-- CARD INFORMASI TRANSAKSI + QR CODE --}}
        <div class="col-md-4">
            <div class="card border-0 card-detail h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-4 text-green d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill"></i> Ringkasan Transaksi
                        </h5>
                        
                        <div class="mb-3">
                            <label class="text-muted small d-block">ID Transaksi</label>
                            <span class="fw-bold text-dark fs-6">#{{ $penjualan->id }}</span>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small d-block">Tanggal Transaksi</label>
                            <span class="fw-semibold text-dark">{{ $penjualan->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small d-block">Kasir / Petugas</label>
                            <span class="fw-semibold text-dark">{{ $penjualan->user->name ?? 'User Tidak Ditemukan' }}</span>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small d-block">Metode Pembayaran</label>
                            <span class="badge bg-green-subtle px-3 py-2 rounded-pill fw-semibold">
                                {{ strtoupper($penjualan->metode_pembayaran) }}
                            </span>
                        </div>

                        @if($penjualan->status === 'COMPLETED' && $penjualan->metode_pembayaran === 'CASH')
                            <div class="mb-3">
                                <label class="text-muted small d-block">Uang Dibayar</label>
                                <span class="fw-semibold text-dark">Rp {{ number_format($penjualan->uang_dibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="mb-4">
                                <label class="text-muted small d-block">Kembalian</label>
                                <span class="fw-bold text-success">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="text-muted small d-block mb-1">Status Transaksi</label>
                            @if(strtoupper($penjualan->status) === 'COMPLETED')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span>
                            @elseif(strtoupper($penjualan->status) === 'CANCELLED')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-semibold"><i class="bi bi-x-circle-fill me-1"></i>Dibatalkan</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-semibold"><i class="bi bi-clock-history me-1"></i>Terbuka</span>
                            @endif
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-3">

                    {{-- QR CODE SECTION --}}
                    <div class="text-center pt-2">
                        <div class="qr-container shadow-sm mb-2">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data={{ urlencode(route('penjualan.show', $penjualan->id)) }}" 
                                 alt="QR Code Transaksi #{{ $penjualan->id }}" 
                                 class="img-fluid"
                                 width="130" height="130">
                        </div>
                        <span class="d-block text-muted small fw-medium">Scan untuk verifikasi resi</span>
                    </div>

                </div>
            </div>
        </div>

        {{-- TABEL ITEM PENJUALAN --}}
        <div class="col-md-8">
            <div class="card border-0 card-detail h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-green d-flex align-items-center gap-2">
                        <i class="bi bi-basket-fill"></i> Rincian Barang
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light small">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualan->itemPenjualan as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $item->produk->nama ?? 'Produk Terhapus' }}</div>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($item->harga_satuan ?? ($item->subtotal / max($item->kuantitas, 1)), 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border px-2 py-1">{{ $item->kuantitas }}</span>
                                        </td>
                                        <td class="text-end fw-semibold text-dark">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Tidak ada rincian barang untuk transaksi ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5 pt-3">Total Pembayaran:</td>
                                    <td class="text-end fw-bold fs-5 text-green pt-3">
                                        Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div></main>@endsection