@extends('layouts.app')

@section('title', 'Laporan Penjualan - POS HADI')

@section('content')
@include('layouts.navbar')

<main class="pos-page">
    <div class="pos-container">

        <div class="report-hero mb-4">
            <div>
                <div class="report-kicker">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    PUSAT LAPORAN
                </div>
                <h1>Laporan Penjualan</h1>
                <p>Analisis transaksi, pendapatan, metode pembayaran, dan produk terlaris berdasarkan periode.</p>
            </div>
            <div class="report-hero-icon">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
            </div>
        </div>

        <div class="card report-filter-card mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Filter Laporan</h5>
                        <p class="text-muted small mb-0">Pilih periode dan data yang ingin ditampilkan.</p>
                    </div>
                    <a href="{{ route('laporan.index') }}" class="btn btn-light border btn-sm">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </a>
                </div>

                <form method="GET" action="{{ route('laporan.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Dari tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-calendar3"></i></span>
                                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label">Sampai tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-12 col-md-2">
                            <label class="form-label">Pembayaran</label>
                            <select name="metode" class="form-select">
                                <option value="">Semua metode</option>
                                @foreach(['CASH' => 'Cash', 'QRIS' => 'QRIS', 'TRANSFER' => 'Transfer'] as $value => $label)
                                    <option value="{{ $value }}" @selected(strtoupper((string) request('metode')) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if($cashiers->isNotEmpty())
                            <div class="col-12 col-md-2">
                                <label class="form-label">Kasir</label>
                                <select name="kasir" class="form-select">
                                    <option value="">Semua kasir</option>
                                    @foreach($cashiers as $cashier)
                                        <option value="{{ $cashier->id }}" @selected((string) request('kasir') === (string) $cashier->id)>
                                            {{ $cashier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-funnel-fill me-1"></i>Tampilkan
                            </button>
                        </div>
                    </div>
                </form>

                <div class="report-period mt-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Periode:
                    <strong>{{ $from->translatedFormat('d F Y') }}</strong>
                    s/d
                    <strong>{{ $to->translatedFormat('d F Y') }}</strong>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="report-stat">
                    <div class="report-stat-icon green"><i class="bi bi-receipt-cutoff"></i></div>
                    <div>
                        <div class="report-stat-label">Total Transaksi</div>
                        <div class="report-stat-value">{{ number_format($ringkasan['transaksi'], 0, ',', '.') }}</div>
                        <div class="report-stat-note">Transaksi selesai</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="report-stat">
                    <div class="report-stat-icon blue"><i class="bi bi-cash-stack"></i></div>
                    <div class="min-w-0">
                        <div class="report-stat-label">Total Penjualan</div>
                        <div class="report-stat-value currency">Rp {{ number_format($ringkasan['penjualan'], 0, ',', '.') }}</div>
                        <div class="report-stat-note">Pendapatan periode</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="report-stat">
                    <div class="report-stat-icon orange"><i class="bi bi-wallet2"></i></div>
                    <div class="min-w-0">
                        <div class="report-stat-label">Pembayaran Cash</div>
                        <div class="report-stat-value currency">Rp {{ number_format($ringkasan['cash'], 0, ',', '.') }}</div>
                        <div class="report-stat-note">Transaksi tunai</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="report-stat">
                    <div class="report-stat-icon purple"><i class="bi bi-credit-card-2-front-fill"></i></div>
                    <div class="min-w-0">
                        <div class="report-stat-label">Pembayaran Non-Tunai</div>
                        <div class="report-stat-value currency">Rp {{ number_format($ringkasan['non_tunai'], 0, ',', '.') }}</div>
                        <div class="report-stat-note">QRIS + Transfer</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card report-card h-100">
                    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 p-md-4">
                        <div>
                            <h5 class="fw-bold mb-1">Daftar Transaksi</h5>
                            <p class="text-muted small mb-0">Transaksi berstatus selesai pada periode terpilih.</p>
                        </div>
                        <a href="{{ route('laporan.export', request()->query()) }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export CSV
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table report-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Kasir</th>
                                    <th>Metode</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $sale)
                                    <tr>
                                        <td><span class="transaction-id">#{{ $sale->id }}</span></td>
                                        <td>
                                            <div class="fw-semibold">{{ $sale->created_at->format('d/m/Y') }}</div>
                                            <div class="small text-muted">{{ $sale->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="cashier-avatar">
                                                    {{ strtoupper(substr($sale->user?->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <span>{{ $sale->user?->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $method = strtoupper($sale->metode_pembayaran ?? '');
                                                $methodClass = match($method) {
                                                    'CASH' => 'cash',
                                                    'QRIS' => 'qris',
                                                    'TRANSFER' => 'transfer',
                                                    default => 'other',
                                                };
                                                $methodLabel = match($method) {
                                                    'CASH' => 'Cash',
                                                    'QRIS' => 'QRIS',
                                                    'TRANSFER' => 'Transfer',
                                                    default => $sale->metode_pembayaran ?? '-',
                                                };
                                            @endphp
                                            <span class="payment-badge {{ $methodClass }}">{{ $methodLabel }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-report">
                                                <i class="bi bi-receipt"></i>
                                                <strong>Belum ada transaksi</strong>
                                                <span>Tidak ada transaksi selesai pada periode/filter yang dipilih.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($transactions->hasPages())
                        <div class="card-footer bg-white border-0 p-3">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card report-card h-100">
                    <div class="card-header bg-white p-3 p-md-4">
                        <h5 class="fw-bold mb-1">Produk Terlaris</h5>
                        <p class="text-muted small mb-0">Berdasarkan jumlah unit terjual.</p>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse($topProducts as $i => $product)
                            <div class="top-product">
                                <div class="rank {{ $i < 3 ? 'top' : '' }}">{{ $i + 1 }}</div>
                                <div class="top-product-info">
                                    <div class="fw-semibold text-truncate">{{ $product->nama }}</div>
                                    <div class="small text-muted">{{ number_format($product->qty, 0, ',', '.') }} unit terjual</div>
                                </div>
                                <div class="top-product-total">
                                    Rp {{ number_format($product->total, 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div class="empty-report py-5">
                                <i class="bi bi-box-seam"></i>
                                <strong>Belum ada data produk</strong>
                                <span>Data produk terlaris akan muncul setelah ada transaksi.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<style>
.report-hero{
    min-height:170px;
    border-radius:22px;
    padding:30px 34px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:25px;
    color:#fff;
    background:linear-gradient(135deg,#0f172a 0%,#14532d 55%,#22c55e 100%);
    box-shadow:0 18px 45px rgba(21,128,61,.18);
    position:relative;
    overflow:hidden;
}
.report-hero:before{
    content:"";
    position:absolute;
    width:320px;height:320px;
    right:-110px;top:-190px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
}
.report-hero h1{font-size:clamp(25px,3vw,34px);font-weight:800;margin:0 0 7px}
.report-hero p{margin:0;color:rgba(255,255,255,.72);font-size:13px}
.report-kicker{font-size:10px;font-weight:800;letter-spacing:.12em;color:#bbf7d0;margin-bottom:9px}
.report-hero-icon{
    width:82px;height:82px;border-radius:24px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.15);
    display:grid;place-items:center;
    font-size:35px;flex:0 0 auto;
}
.report-filter-card,.report-card{border:1px solid #e5e7eb;border-radius:18px;box-shadow:0 10px 30px rgba(15,23,42,.06);overflow:hidden}
.report-filter-card .form-label{font-size:12px;font-weight:700;color:#334155;margin-bottom:7px}
.report-filter-card .input-group-text{border-color:#dbe2ea;color:#64748b}
.report-filter-card .form-control,.report-filter-card .form-select{min-height:44px}
.report-period{
    display:inline-flex;align-items:center;gap:4px;
    padding:8px 11px;border-radius:9px;
    background:#f0fdf4;color:#166534;font-size:11px;
}
.report-stat{
    height:100%;display:flex;align-items:center;gap:13px;
    padding:18px;background:#fff;border:1px solid #e5e7eb;
    border-radius:16px;box-shadow:0 7px 22px rgba(15,23,42,.05);
    transition:.2s ease;
}
.report-stat:hover{transform:translateY(-2px);box-shadow:0 13px 28px rgba(15,23,42,.09)}
.report-stat-icon{
    width:47px;height:47px;border-radius:14px;display:grid;place-items:center;
    font-size:19px;flex:0 0 auto;
}
.report-stat-icon.green{background:#dcfce7;color:#15803d}
.report-stat-icon.blue{background:#dbeafe;color:#2563eb}
.report-stat-icon.orange{background:#ffedd5;color:#c2410c}
.report-stat-icon.purple{background:#f3e8ff;color:#7e22ce}
.report-stat-label{font-size:10px;text-transform:uppercase;letter-spacing:.04em;color:#64748b;font-weight:700}
.report-stat-value{font-size:20px;font-weight:800;color:#0f172a;margin-top:2px;white-space:nowrap}
.report-stat-value.currency{font-size:17px}
.report-stat-note{font-size:10px;color:#94a3b8;margin-top:2px}
.report-table{font-size:12px}
.report-table th{font-size:9px!important;padding:12px 16px!important}
.report-table td{padding:13px 16px!important}
.transaction-id{font-weight:800;color:#15803d}
.cashier-avatar{
    width:30px;height:30px;border-radius:50%;
    display:grid;place-items:center;
    background:#dcfce7;color:#15803d;font-size:10px;font-weight:800;flex:0 0 auto;
}
.payment-badge{display:inline-flex;padding:5px 9px;border-radius:8px;font-size:9px;font-weight:800}
.payment-badge.cash{background:#dcfce7;color:#166534}
.payment-badge.qris{background:#dbeafe;color:#1d4ed8}
.payment-badge.transfer{background:#f3e8ff;color:#7e22ce}
.payment-badge.other{background:#f1f5f9;color:#475569}
.top-product{display:flex;align-items:center;gap:11px;padding:13px 16px;border-bottom:1px solid #eef2f7}
.top-product:last-child{border-bottom:0}
.rank{
    width:28px;height:28px;border-radius:9px;background:#f1f5f9;color:#64748b;
    display:grid;place-items:center;font-size:10px;font-weight:800;flex:0 0 auto;
}
.rank.top{background:#dcfce7;color:#15803d}
.top-product-info{min-width:0;flex:1}
.top-product-total{font-size:10px;font-weight:800;color:#334155;white-space:nowrap}
.empty-report{min-height:190px;padding:30px 20px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;color:#94a3b8;gap:4px}
.empty-report i{font-size:35px;margin-bottom:5px}
.empty-report strong{font-size:13px;color:#64748b}
.empty-report span{font-size:11px;max-width:280px}
@media(max-width:768px){
    .report-hero{padding:23px;border-radius:17px;min-height:145px}
    .report-hero-icon{width:58px;height:58px;border-radius:17px;font-size:25px}
    .report-stat{padding:15px}
    .report-stat-value{font-size:18px}
    .report-stat-value.currency{font-size:15px}
    .report-table{min-width:720px}
}
</style>
@endsection
