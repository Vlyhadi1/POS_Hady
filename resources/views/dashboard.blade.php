@extends('layouts.app')

@section('title', 'Dashboard - POS HADI')

@section('content')
@include('layouts.navbar')

<style>
    .dashboard-wrap{padding:18px 22px 24px;min-height:100vh}
    .hero{background:linear-gradient(135deg,#0f172a,#064e3b 55%,#22c55e);color:#fff;border-radius:16px;padding:24px;position:relative;overflow:hidden;box-shadow:0 14px 35px rgba(15,23,42,.12)}
    .hero:after{content:'';position:absolute;width:260px;height:260px;border-radius:50%;right:-70px;top:-120px;background:rgba(255,255,255,.1)}
    .hero h1{font-size:24px;font-weight:800;margin:0 0 5px}
    .hero p{margin:0;color:rgba(255,255,255,.72);font-size:12px}
    .stat{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:18px;box-shadow:0 5px 18px rgba(15,23,42,.06);height:100%}
    .stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px}
    .stat h6{font-size:10px;text-transform:uppercase;color:#64748b;margin:0 0 6px}
    .stat .number{font-size:20px;font-weight:800;color:#0f172a}
    .small-muted{font-size:10px;color:#94a3b8}
    .panel{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 5px 18px rgba(15,23,42,.05);overflow:hidden}
    .panel-head{padding:16px 18px;border-bottom:1px solid #eef2f7;display:flex;justify-content:space-between;align-items:center}
    .panel-title{font-size:14px;font-weight:800;margin:0}
    .table{font-size:11px}
    .table th{font-size:9px;text-transform:uppercase;color:#64748b;background:#f8fafc;white-space:nowrap}
    .table td,.table th{padding:11px 12px;vertical-align:middle}
    .badge-soft{padding:5px 9px;border-radius:20px;font-size:9px}
    .quick{display:flex;align-items:center;gap:10px;border:1px solid #e2e8f0;border-radius:9px;padding:11px;text-decoration:none;color:#0f172a;font-size:11px;font-weight:600}
    .quick:hover{background:#f0fdf4;border-color:#86efac;color:#15803d}
    .profile-mini{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px}
    @media(max-width:768px){.dashboard-wrap{padding:12px}.hero{padding:18px}.hero h1{font-size:19px}}
</style>

<div class="dashboard-wrap">

    <div class="hero mb-3">
        <div class="position-relative" style="z-index:1">
            <div class="small mb-2 opacity-75">
                <i class="bi bi-calendar3 me-1"></i>
                {{ now()->translatedFormat('l, d F Y') }}
                <span class="ms-2"><i class="bi bi-clock me-1"></i><span id="liveClock"></span> WIB</span>
            </div>
            <h1>Selamat Datang di {{ $setting->store_name }}</h1>
            <p>Ringkasan aktivitas transaksi, inventaris, dan performa POS kamu.</p>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="stat">
                <div class="d-flex justify-content-between">
                    <div><h6>Total Penjualan Hari Ini</h6><div class="number">Rp {{ number_format($ringkasan['total_penjualan'],0,',','.') }}</div></div>
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-cash-stack"></i></div>
                </div>
                <div class="small-muted mt-2">Penjualan selesai hari ini</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat">
                <div class="d-flex justify-content-between">
                    <div><h6>Jumlah Transaksi</h6><div class="number">{{ number_format($ringkasan['total_transaksi'],0,',','.') }}</div></div>
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-receipt"></i></div>
                </div>
                <div class="small-muted mt-2">Transaksi completed</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat">
                <div class="d-flex justify-content-between">
                    <div><h6>Profit Hari Ini</h6><div class="number">Rp {{ number_format($totalProfit,0,',','.') }}</div></div>
                    <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-graph-up-arrow"></i></div>
                </div>
                <div class="small-muted mt-2">Estimasi laba dari produk terjual</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat">
                <div class="d-flex justify-content-between">
                    <div><h6>Total Produk</h6><div class="number">{{ number_format($totalProduk,0,',','.') }}</div></div>
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-box-seam"></i></div>
                </div>
                <div class="small-muted mt-2">{{ $totalUsers }} user terdaftar · {{ $isAdmin ? 'Semua kasir' : 'Transaksi saya' }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat">
                <div class="d-flex justify-content-between">
                    <div><h6>Stok Menipis / Habis</h6><div class="number">{{ $produkStokRendahTotal }} / {{ $produkStokHabisTotal }}</div></div>
                    <div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-exclamation-triangle"></i></div>
                </div>
                <div class="small-muted mt-2">Perlu diperhatikan</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-head"><h5 class="panel-title">Grafik Penjualan {{ now()->year }}</h5></div>
                <div class="p-3" style="height:300px"><canvas id="salesChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-head"><h5 class="panel-title">Penjualan per Kategori</h5></div>
                <div class="p-3" style="height:300px"><canvas id="categoryChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-head">
                    <h5 class="panel-title">Produk Terlaris Hari Ini</h5>
                    <a href="{{ route('produk.index') }}" class="small text-success text-decoration-none">Lihat Produk</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Produk</th><th>Stok Saat Ini</th><th class="text-end">Terjual</th></tr></thead>
                        <tbody>
                        @forelse($produkTerlaris as $p)
                            <tr><td><i class="bi bi-box-seam me-2 text-muted"></i>{{ $p->nama }}</td><td>{{ $p->stok }}</td><td class="text-end"><span class="badge-soft bg-success-subtle text-success">{{ $p->total_terjual }} terjual</span></td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada penjualan hari ini.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel h-100">
                <div class="panel-head"><h5 class="panel-title">Pusat Pintasan</h5></div>
                <div class="p-3 d-flex flex-column gap-2">
                    <a class="quick" href="{{ route('penjualan.create') }}"><i class="bi bi-plus-circle-fill text-primary"></i> Buat Transaksi Baru</a>
                    <a class="quick" href="{{ route('produk.create') }}"><i class="bi bi-box-seam-fill text-warning"></i> Tambah Produk / Stok</a>
                    @if(auth()->user()->role && strtolower(auth()->user()->role->name) === 'admin')
                        <a class="quick" href="{{ route('admin.categories.create') }}"><i class="bi bi-tags-fill text-success"></i> Tambah Kategori</a>
                    @endif
                    <div class="profile-mini mt-2">
                        <div class="small-muted">Petugas Aktif</div>
                        <div class="fw-bold small">{{ auth()->user()->name }}</div>
                        <span class="badge-soft bg-primary-subtle text-primary text-capitalize">{{ optional(auth()->user()->role)->name ?? 'Staff' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-head"><h5 class="panel-title text-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i>Stok Menipis</h5></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Produk</th><th>Stok</th><th>Minimum</th></tr></thead>
                        <tbody>
                        @forelse($produkStokRendah as $p)
                            <tr><td>{{ $p->nama }}</td><td><span class="badge-soft bg-warning-subtle text-warning">{{ $p->stok }}</span></td><td>{{ $p->minimum_stok }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-success py-4">Semua stok aman.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-head"><h5 class="panel-title text-danger"><i class="bi bi-x-circle-fill me-2"></i>Stok Habis</h5></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Produk</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($produkStokHabis as $p)
                            <tr><td>{{ $p->nama }}</td><td><span class="badge-soft bg-danger-subtle text-danger">Habis (0)</span></td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-success py-4">Tidak ada produk habis.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-head"><h5 class="panel-title">Transaksi Terakhir</h5><a href="{{ route('penjualan.index') }}" class="small text-success text-decoration-none">Semua Transaksi</a></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>ID</th><th>Tanggal</th><th>Kasir</th><th>Metode</th><th class="text-end">Total</th></tr></thead>
                        <tbody>
                        @forelse($recentSales as $sale)
                            <tr><td>#{{ $sale->id }}</td><td>{{ $sale->created_at->format('d/m/Y H:i') }}</td><td>{{ $sale->user->name ?? '-' }}</td><td>{{ $sale->metode_pembayaran }}</td><td class="text-end fw-semibold">Rp {{ number_format($sale->total_pembayaran,0,',','.') }}</td></tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel h-100">
                <div class="panel-head"><h5 class="panel-title">Ringkasan Pembayaran Hari Ini</h5></div>
                <div class="p-3">
                    <div class="d-flex justify-content-between border-bottom py-2"><span class="small-muted">Cash</span><strong>Rp {{ number_format($ringkasan['total_cash'],0,',','.') }}</strong></div>
                    <div class="d-flex justify-content-between border-bottom py-2"><span class="small-muted">Non Tunai</span><strong>Rp {{ number_format($ringkasan['total_non_tunai'],0,',','.') }}</strong></div>
                    <div class="d-flex justify-content-between py-3"><span class="fw-bold">Total</span><strong class="text-success">Rp {{ number_format($ringkasan['total_penjualan'],0,',','.') }}</strong></div>
                    <a href="{{ route('profile') }}" class="quick"><i class="bi bi-person-circle text-success"></i> Kelola Profil</a>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateClock(){
        const n=new Date();
        document.getElementById('liveClock').textContent =
            [n.getHours(),n.getMinutes(),n.getSeconds()].map(v=>String(v).padStart(2,'0')).join(':');
    }
    updateClock(); setInterval(updateClock,1000);

    new Chart(document.getElementById('salesChart'),{
        type:'line',
        data:{
            labels:@json($chartLabels),
            datasets:[{
                label:'Penjualan',
                data:@json($chartValues),
                tension:.35,
                borderWidth:2,
                fill:false
            }]
        },
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'Rp '+Number(v).toLocaleString('id-ID')}}}}
    });

    new Chart(document.getElementById('categoryChart'),{
        type:'doughnut',
        data:{
            labels:@json($categoryRows->pluck('nama')->values()),
            datasets:[{data:@json($categoryRows->pluck('total')->map(fn($v)=>(int)$v)->values()),borderWidth:2}]
        },
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{size:10}}}}}
    });
</script>
@endsection
