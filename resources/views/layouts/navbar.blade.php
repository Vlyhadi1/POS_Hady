@php
    $user = auth()->user();
    $role = strtolower(optional($user?->role)->name ?? 'staff');
    $isAdmin = $role === 'admin';
    $photo = $user?->photo;
    $avatar = $photo
        ? asset('storage/'.$photo)
        : 'https://ui-avatars.com/api/?name='.urlencode($user?->name ?? 'User').'&background=16a34a&color=ffffff&bold=true';
@endphp

<button class="pos-mobile-toggle" id="posMobileToggle" type="button" aria-label="Buka menu">
    <i class="bi bi-list"></i>
</button>
<div class="pos-sidebar-backdrop" id="posSidebarBackdrop"></div>

<aside class="pos-sidebar" id="posSidebar">
    <div class="pos-brand">
        <div class="pos-brand-icon"><img src="{{ asset('images/pos-ind-logistik.jpg') }}" alt="POS IND Logistik Indonesia" class="pos-brand-logo"></div>
        <div>
            <div class="pos-brand-title">POS IND</div>
            <div class="pos-brand-subtitle">Logistik Indonesia</div>
        </div>
        <button class="pos-sidebar-close" id="posSidebarClose" type="button"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="pos-user-card">
        <img src="{{ $avatar }}" alt="Foto profil {{ $user?->name }}" class="pos-avatar">
        <div class="min-w-0">
            <div class="pos-user-name text-truncate">{{ $user?->name ?? 'User' }}</div>
            <div class="pos-user-role"><i class="bi bi-shield-check me-1"></i>{{ ucfirst($role) }}</div>
        </div>
    </div>

    <div class="pos-nav-label">Menu Utama</div>
    <nav class="pos-nav">
        {{-- 1. Ringkasan --}}
        <a class="pos-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
        </a>

        {{-- 2. Transaksi --}}
        <a class="pos-nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}" href="{{ route('penjualan.index') }}">
            <i class="bi bi-receipt-cutoff"></i><span>Penjualan</span>
        </a>

        {{-- 3. Produk --}}
        <a class="pos-nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">
            <i class="bi bi-box-seam-fill"></i><span>Produk</span>
        </a>

        {{-- 4. Kategori --}}
        @if($isAdmin)
            <a class="pos-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="bi bi-tags-fill"></i><span>Kategori</span>
            </a>
        @endif

        {{-- 5. Laporan --}}
        <a class="pos-nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
            <i class="bi bi-bar-chart-fill"></i><span>Laporan</span>
        </a>

        {{-- 6. Manajemen User --}}
        @if($isAdmin)
            <a class="pos-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                <i class="bi bi-people-fill"></i><span>Users</span>
            </a>
        @endif
    </nav>

    <div class="pos-nav-label">Akun & Sistem</div>
    <nav class="pos-nav">
        <a class="pos-nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
            <i class="bi bi-person-circle"></i><span>Profil Saya</span>
        </a>
        @if($isAdmin)
            <a class="pos-nav-link {{ request()->routeIs('admin.setting*') ? 'active' : '' }}" href="{{ route('admin.setting') }}">
                <i class="bi bi-gear-fill"></i><span>Pengaturan</span>
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="pos-nav-link pos-logout" type="submit">
                <i class="bi bi-box-arrow-right"></i><span>Keluar</span>
            </button>
        </form>
    </nav>

    <div class="pos-sidebar-footer">
        <i class="bi bi-lightning-charge-fill"></i>
        <span>POS HADI • {{ date('Y') }}</span>
    </div>
</aside>

<style>
.pos-sidebar{position:fixed;inset:0 auto 0 0;width:250px;background:linear-gradient(180deg,#0b1f36,#0a1a2c);color:#dbe7f5;z-index:1100;display:flex;flex-direction:column;box-shadow:10px 0 30px rgba(2,6,23,.12)}
.pos-brand{height:78px;padding:0 18px;display:flex;align-items:center;gap:11px;border-bottom:1px solid rgba(255,255,255,.08)}
.pos-brand-icon{width:48px;height:48px;border-radius:12px;overflow:hidden;background:#fff;display:grid;place-items:center;box-shadow:0 8px 20px rgba(0,0,0,.18);flex:0 0 auto}.pos-brand-logo{width:100%;height:100%;object-fit:cover;display:block}
.pos-brand-title{font-weight:800;font-size:17px;color:#fff;line-height:1.1}
.pos-brand-subtitle{font-size:10px;color:#8da2ba;margin-top:3px}
.pos-sidebar-close{display:none;margin-left:auto;background:none;border:0;color:#94a3b8}
.pos-user-card{margin:16px 14px;padding:12px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.045);border-radius:14px;display:flex;align-items:center;gap:10px}
.pos-avatar{width:42px;height:42px;object-fit:cover;border-radius:50%;border:2px solid rgba(255,255,255,.8);flex:0 0 auto}
.pos-user-name{font-size:12px;font-weight:700;color:#fff}
.pos-user-role{font-size:9px;color:#91a5bd;text-transform:uppercase;letter-spacing:.06em;margin-top:3px}
.pos-nav-label{font-size:9px;font-weight:700;color:#617891;text-transform:uppercase;letter-spacing:.12em;padding:12px 18px 7px}
.pos-nav{padding:0 10px}
.pos-nav-link{width:100%;display:flex;align-items:center;gap:11px;padding:11px 12px;margin:2px 0;border:0;border-radius:10px;background:transparent;color:#b9c8d8;font-size:12px;font-weight:600;text-align:left;transition:.18s}
.pos-nav-link i{width:18px;text-align:center;font-size:15px}
.pos-nav-link:hover{background:rgba(34,197,94,.10);color:#fff}
.pos-nav-link.active{background:linear-gradient(90deg,#16a34a,#15803d);color:#fff;box-shadow:0 8px 18px rgba(22,163,74,.2)}
.pos-nav form{margin:0}
.pos-logout{color:#fda4af}
.pos-logout:hover{background:rgba(239,68,68,.1);color:#fecaca}
.pos-sidebar-footer{margin-top:auto;padding:14px 18px;border-top:1px solid rgba(255,255,255,.07);font-size:9px;color:#627992;display:flex;align-items:center;gap:7px}
.pos-sidebar-footer i{color:#22c55e}
.pos-mobile-toggle{display:none;position:fixed;top:14px;left:14px;z-index:1050;width:42px;height:42px;border:0;border-radius:12px;background:#0b1f36;color:#fff;box-shadow:0 8px 20px rgba(15,23,42,.2)}
.pos-sidebar-backdrop{display:none}
@media(max-width:900px){
 .pos-mobile-toggle{display:grid;place-items:center}
 .pos-sidebar{transform:translateX(-100%);transition:transform .25s ease}
 .pos-sidebar.show{transform:translateX(0)}
 .pos-sidebar-close{display:block}
 .pos-sidebar-backdrop.show{display:block;position:fixed;inset:0;background:rgba(2,6,23,.45);z-index:1090}
}
</style>

<script>
document.addEventListener('DOMContentLoaded',()=>{
 const sidebar=document.getElementById('posSidebar');
 const toggle=document.getElementById('posMobileToggle');
 const close=document.getElementById('posSidebarClose');
 const backdrop=document.getElementById('posSidebarBackdrop');
 const hide=()=>{sidebar?.classList.remove('show');backdrop?.classList.remove('show')};
 toggle?.addEventListener('click',()=>{sidebar.classList.add('show');backdrop.classList.add('show')});
 close?.addEventListener('click',hide);
 backdrop?.addEventListener('click',hide);
});
</script>
