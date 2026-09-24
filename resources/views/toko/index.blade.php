@extends('layouts.app')
@section('title','Tentang Toko - '.$setting->store_name)
@section('content')
@include('layouts.navbar')

@php
    $customLogoUrl = $setting->store_logo ? asset('storage/'.$setting->store_logo) : null;
    $logoUrl = $customLogoUrl ?: asset('images/pos-ind-logistik.jpg');
@endphp

<main class="pos-page toko-page">
    <div class="pos-container toko-container">
        <section class="toko-hero mb-4">
            <div class="toko-hero-logo"><img src="{{ $logoUrl }}" alt="Logo {{ $setting->store_name }}"></div>
            <div class="toko-hero-content">
                <div class="toko-eyebrow"><i class="bi bi-shop-window"></i> Profil Toko</div>
                <h1>{{ $setting->store_name }}</h1>
                <p>
                    @if($setting->store_address) <i class="bi bi-geo-alt-fill me-1"></i>{{ $setting->store_address }} @else Alamat Toko Kota Tasikmalaya Kec.Purbaratu Jl.Cikareo RT02 RW03 @endif
                </p>
                <div class="toko-about-box">
                    <i class="bi bi-card-text me-2"></i>
                    {{ $setting->store_description ?: 'Toko kami hadir untuk memenuhi kebutuhan harian Anda dengan produk yang berkualitas dan pelayanan yang ramah.' }}
                </div>
                <div class="toko-hero-meta">
                    @if($setting->store_phone)
                        <span><i class="bi bi-telephone-fill me-1"></i>{{ $setting->store_phone }}</span>
                    @endif
                    @if($setting->store_email)
                        <span><i class="bi bi-envelope-fill me-1"></i>{{ $setting->store_email }}</span>
                    @endif
                    @if($setting->store_hours)
                        <span><i class="bi bi-clock-fill me-1"></i>{{ $setting->store_hours }}</span>
                    @endif
                    @if($setting->store_social)
                        <a href="{{ $setting->store_social }}" target="_blank" rel="noopener" class="text-white text-decoration-none"><i class="bi bi-globe2 me-1"></i>Kunjungi tautan</a>
                    @endif
                    <span><i class="bi bi-calendar3 me-1"></i>Beroperasi sejak {{ \Carbon\Carbon::parse($sejakTanggal)->translatedFormat('d F Y') }}</span>
                </div>
            </div>
            @if($isAdmin)
                <a href="{{ route('admin.setting') }}" class="btn btn-light rounded-pill fw-semibold toko-edit-btn">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profil Toko
                </a>
            @endif
        </section>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="toko-stat">
                    <div class="toko-stat-icon bg-primary-subtle text-primary"><i class="bi bi-box-seam-fill"></i></div>
                    <div>
                        <div class="toko-stat-value">{{ number_format($stats['total_produk']) }}</div>
                        <div class="toko-stat-label">Produk</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="toko-stat">
                    <div class="toko-stat-icon bg-warning-subtle text-warning"><i class="bi bi-tags-fill"></i></div>
                    <div>
                        <div class="toko-stat-value">{{ number_format($stats['total_kategori']) }}</div>
                        <div class="toko-stat-label">Kategori</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="toko-stat">
                    <div class="toko-stat-icon bg-success-subtle text-success"><i class="bi bi-receipt-cutoff"></i></div>
                    <div>
                        <div class="toko-stat-value">{{ number_format($stats['total_transaksi']) }}</div>
                        <div class="toko-stat-label">Transaksi Selesai</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="toko-stat">
                    <div class="toko-stat-icon bg-info-subtle text-info"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <div class="toko-stat-value">{{ number_format($stats['total_users']) }}</div>
                        <div class="toko-stat-label">Pengguna</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <section class="toko-card">
                    <div class="toko-card-header">
                        <div class="toko-title-icon"><i class="bi bi-info-circle-fill"></i></div>
                        <div>
                            <h2>Tentang Toko Ini</h2>
                            <p>Ringkasan identitas dan performa toko sejauh ini.</p>
                        </div>
                    </div>
                    <div class="toko-card-body">
                        <div class="toko-info-row">
                            <span>Nama Toko</span>
                            <strong>{{ $setting->store_name }}</strong>
                        </div>
                        <div class="toko-info-row">
                            <span>Alamat</span>
                            <strong>{{ $setting->store_address ?: '-' }}</strong>
                        </div>
                        <div class="toko-info-row">
                            <span>Telepon</span>
                            <strong>{{ $setting->store_phone ?: '-' }}</strong>
                        </div>
                        <div class="toko-info-row">
                            <span>Email</span>
                            <strong>{{ $setting->store_email ?: '-' }}</strong>
                        </div>
                        <div class="toko-info-row">
                            <span>Jam Operasional</span>
                            <strong>{{ $setting->store_hours ?: '-' }}</strong>
                        </div>
                        <div class="toko-info-row">
                            <span>Media Sosial / Website</span>
                            <strong>
                                @if($setting->store_social)
                                    <a href="{{ $setting->store_social }}" target="_blank" rel="noopener">Buka tautan</a>
                                @else
                                    -
                                @endif
                            </strong>
                        </div>
                        <div class="toko-info-row">
                            <span>Tentang Toko</span>
                            <strong>{{ $setting->store_description ?: '-' }}</strong>
                        </div>
                        <div class="toko-info-row">
                            <span>Mata Uang</span>
                            <strong>{{ $setting->currency }}</strong>
                        </div>
                        <div class="toko-info-row">
                            <span>Total Pendapatan</span>
                            <strong>Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="toko-info-row border-0">
                            <span>Catatan Struk</span>
                            <strong>{{ $setting->receipt_footer ?: '-' }}</strong>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                <section class="toko-card h-100">
                    <div class="toko-card-header">
                        <div class="toko-title-icon"><i class="bi bi-person-badge-fill"></i></div>
                        <div>
                            <h2>Anda Login Sebagai</h2>
                            <p>Informasi akun yang sedang aktif.</p>
                        </div>
                    </div>
                    <div class="toko-card-body">
                        @php $me = auth()->user(); @endphp
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $me->photo ? asset('storage/'.$me->photo) : 'https://ui-avatars.com/api/?name='.urlencode($me->name).'&background=16a34a&color=ffffff&bold=true' }}" class="toko-avatar" alt="{{ $me->name }}">
                            <div>
                                <div class="fw-bold">{{ $me->name }}</div>
                                <div class="text-muted small">{{ $me->email }}</div>
                            </div>
                        </div>
                        <span class="status-badge"><span></span> {{ ucfirst(optional($me->role)->name ?? 'Staff') }}</span>
                        @if($isAdmin)
                            <p class="text-muted small mt-3 mb-0">Sebagai admin, kamu dapat mengubah nama, alamat, telepon, dan logo toko melalui halaman Pengaturan.</p>
                        @else
                            <p class="text-muted small mt-3 mb-0">Hubungi admin untuk mengubah informasi toko ini.</p>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<style>
.toko-page{padding-top:24px;padding-bottom:40px}
.toko-container{max-width:1420px}
.toko-hero{position:relative;overflow:hidden;display:flex;align-items:center;gap:22px;padding:28px 32px;border-radius:22px;background:linear-gradient(135deg,#0f172a 0%,#14532d 58%,#22c55e 100%);box-shadow:0 18px 45px rgba(21,128,61,.18);color:#fff}
.toko-hero:before,.toko-hero:after{content:"";position:absolute;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none}.toko-hero:before{width:280px;height:280px;right:80px;top:-190px}.toko-hero:after{width:190px;height:190px;right:-60px;bottom:-110px}
.toko-hero-logo{position:relative;z-index:1;width:84px;height:84px;flex:0 0 auto;border-radius:20px;overflow:hidden;background:#fff;display:grid;place-items:center;box-shadow:0 10px 24px rgba(0,0,0,.2)}.toko-hero-logo img{width:100%;height:100%;object-fit:cover}
.toko-hero-content{position:relative;z-index:1;min-width:0;flex:1 1 auto}
.toko-eyebrow{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.1);border-radius:999px;font-size:.78rem;font-weight:700;margin-bottom:11px}
.toko-hero h1{font-size:clamp(1.5rem,3vw,2.1rem);font-weight:800;letter-spacing:-.03em;margin:0 0 8px}
.toko-hero p{margin:0 0 10px;color:rgba(255,255,255,.8)}
.toko-hero-meta{display:flex;flex-wrap:wrap;gap:16px;font-size:.85rem;color:rgba(255,255,255,.85)}
.toko-edit-btn{position:relative;z-index:1;white-space:nowrap;color:#14532d!important}
.toko-stat{display:flex;align-items:center;gap:13px;background:#fff;border:1px solid #e7ebf0;border-radius:16px;padding:16px;box-shadow:0 8px 20px rgba(15,23,42,.05);height:100%}
.toko-stat-icon{width:44px;height:44px;flex:0 0 auto;display:grid;place-items:center;border-radius:12px;font-size:19px}
.toko-stat-value{font-size:1.25rem;font-weight:800;color:#0f172a;line-height:1.1}
.toko-stat-label{font-size:.75rem;color:#64748b;margin-top:2px}
.toko-card{background:#fff;border:1px solid #e7ebf0;border-radius:18px;box-shadow:0 8px 25px rgba(15,23,42,.055);overflow:hidden}
.toko-card-header{display:flex;gap:13px;align-items:flex-start;padding:22px 24px 17px}
.toko-title-icon{width:40px;height:40px;flex:0 0 auto;display:grid;place-items:center;border-radius:12px;background:#f0fdf4;color:#15803d;font-size:18px}
.toko-card-header h2{font-size:1rem;font-weight:800;margin:2px 0 4px;color:#0f172a}
.toko-card-header p{margin:0;color:#64748b;font-size:.82rem;line-height:1.5}
.toko-card-body{padding:0 24px 24px}
.toko-info-row{display:flex;justify-content:space-between;gap:12px;padding:11px 0;border-bottom:1px solid #eef2f7;font-size:.85rem}
.toko-info-row span{color:#64748b}
.toko-info-row strong{color:#0f172a;text-align:right}
.toko-avatar{width:52px;height:52px;border-radius:50%;object-fit:cover}
.status-badge{display:inline-flex!important;align-items:center;gap:6px;color:#15803d!important;font-weight:700;font-size:.8rem}
.status-badge span{width:7px;height:7px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 3px #dcfce7}
@media(max-width:767.98px){.toko-hero{flex-wrap:wrap;padding:22px}.toko-edit-btn{width:100%;text-align:center}}
</style>
@endsection
