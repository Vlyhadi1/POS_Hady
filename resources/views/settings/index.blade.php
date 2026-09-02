@extends('layouts.app')
@section('title','Pengaturan - POS HADI')
@section('content')
@include('layouts.navbar')

@php
    $customLogoUrl = $settings->store_logo ? asset('storage/'.$settings->store_logo) : null;
    $logoUrl = $customLogoUrl ?: asset('images/pos-ind-logistik.jpg');
@endphp

<main class="pos-page settings-page">
    <div class="pos-container settings-container">
        <section class="settings-hero mb-4">
            <div class="settings-hero-content">
                <div class="settings-eyebrow"><i class="bi bi-shield-check"></i> Administrator</div>
                <h1>Pengaturan POS</h1>
                <p>Kelola identitas toko, stok, logo, dan informasi yang tampil pada struk.</p>
            </div>
            <div class="settings-hero-icon" aria-hidden="true"><i class="bi bi-gear-wide-connected"></i></div>
        </section>

        <form action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="remove_logo" value="0" id="removeLogoInput">

            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    <section class="settings-card mb-4">
                        <div class="settings-card-header">
                            <div class="settings-title-icon"><i class="bi bi-shop"></i></div>
                            <div>
                                <h2>Identitas Toko</h2>
                                <p>Informasi utama yang digunakan sebagai identitas aplikasi POS.</p>
                            </div>
                        </div>
                        <div class="settings-card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="storeName" class="form-label">Nama Toko / Sistem <span class="text-danger">*</span></label>
                                    <input id="storeName" name="store_name" value="{{ old('store_name',$settings->store_name) }}" class="form-control" maxlength="100" required autocomplete="organization">
                                </div>
                                <div class="col-md-7">
                                    <label for="storeAddress" class="form-label">Alamat Toko</label>
                                    <textarea id="storeAddress" name="store_address" class="form-control" rows="3" maxlength="255" placeholder="Contoh: Jl. Contoh No. 10, Tasikmalaya">{{ old('store_address',$settings->store_address) }}</textarea>
                                </div>
                                <div class="col-md-5">
                                    <label for="storePhone" class="form-label">Nomor Telepon</label>
                                    <input id="storePhone" name="store_phone" value="{{ old('store_phone',$settings->store_phone) }}" class="form-control" maxlength="30" placeholder="08xxxxxxxxxx" inputmode="tel" autocomplete="tel">
                                    <div class="form-text">Opsional, dapat ditampilkan pada struk.</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="settings-card mb-4">
                        <div class="settings-card-header">
                            <div class="settings-title-icon"><i class="bi bi-box-seam"></i></div>
                            <div>
                                <h2>Stok & Transaksi</h2>
                                <p>Atur nilai yang digunakan sistem untuk peringatan stok.</p>
                            </div>
                        </div>
                        <div class="settings-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="currency" class="form-label">Mata Uang <span class="text-danger">*</span></label>
                                    <select id="currency" name="currency" class="form-select" required>
                                        <option value="IDR" @selected(old('currency',$settings->currency) === 'IDR')>IDR — Rupiah Indonesia</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="lowStock" class="form-label">Batas Stok Menipis <span class="text-danger">*</span></label>
                                    <div class="input-group settings-input-group">
                                        <input id="lowStock" type="number" name="low_stock_limit" value="{{ old('low_stock_limit',$settings->low_stock_limit) }}" class="form-control" min="0" max="1000" required inputmode="numeric">
                                        <span class="input-group-text">unit</span>
                                    </div>
                                    <div class="form-text">Peringatan muncul saat stok mencapai batas ini.</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="settings-card">
                        <div class="settings-card-header">
                            <div class="settings-title-icon"><i class="bi bi-receipt"></i></div>
                            <div>
                                <h2>Struk</h2>
                                <p>Tentukan teks penutup yang digunakan pada struk transaksi.</p>
                            </div>
                        </div>
                        <div class="settings-card-body">
                            <label for="receiptFooter" class="form-label">Catatan / Footer Struk</label>
                            <input id="receiptFooter" name="receipt_footer" value="{{ old('receipt_footer',$settings->receipt_footer) }}" class="form-control" maxlength="255" placeholder="Terima kasih telah berbelanja.">
                            <div class="form-text">Opsional. Data tersimpan di database untuk digunakan saat pencetakan struk.</div>
                        </div>
                    </section>
                </div>

                <div class="col-lg-4">
                    <aside class="settings-card settings-logo-card mb-4">
                        <div class="settings-card-header">
                            <div class="settings-title-icon"><i class="bi bi-image"></i></div>
                            <div>
                                <h2>Logo Toko</h2>
                                <p>Gunakan logo sebagai identitas visual toko.</p>
                            </div>
                        </div>
                        <div class="settings-card-body">
                            <div class="logo-preview-wrap" id="logoPreviewWrap">
                                <img id="settingLogoPreview" src="{{ $logoUrl }}" alt="Logo toko">
                                <div id="settingLogoFallback" class="logo-fallback d-none"><i class="bi bi-image"></i></div>
                            </div>
                            <div class="logo-status" id="logoStatus">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ $customLogoUrl ? 'Logo kustom aktif' : 'Logo bawaan aktif' }}</span>
                            </div>

                            <label for="storeLogoInput" class="form-label mt-3">Pilih logo baru</label>
                            <input id="storeLogoInput" type="file" name="store_logo" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">JPG, PNG, atau WEBP • maksimal 2 MB.</div>

                            @if($customLogoUrl)
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-3" id="removeLogoBtn">
                                    <i class="bi bi-trash3 me-1"></i> Hapus Logo Kustom
                                </button>
                            @endif
                        </div>
                    </aside>

                    <aside class="settings-card">
                        <div class="settings-card-body">
                            <div class="access-box">
                                <div class="access-icon"><i class="bi bi-shield-check"></i></div>
                                <div>
                                    <h3>Akses Admin</h3>
                                    <p>Halaman ini hanya dapat diakses oleh pengguna dengan role <strong>Admin</strong>.</p>
                                </div>
                            </div>
                            <div class="settings-summary">
                                <div><span>Mata uang</span><strong>{{ $settings->currency }}</strong></div>
                                <div><span>Batas stok</span><strong>{{ $settings->low_stock_limit }} unit</strong></div>
                                <div><span>Status sistem</span><span class="status-badge"><span></span> Aktif</span></div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>

            <div class="settings-savebar mt-4">
                <div class="savebar-info">
                    <div class="savebar-icon"><i class="bi bi-cloud-check"></i></div>
                    <div>
                        <strong>Siap menyimpan perubahan?</strong>
                        <span>Pengaturan akan langsung digunakan oleh sistem POS.</span>
                    </div>
                </div>
                <button class="btn btn-success rounded-pill px-4 fw-semibold settings-save-btn" type="submit" id="saveSettingsBtn">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</main>

<style>
.settings-page{padding-top:24px;padding-bottom:40px}
.settings-container{max-width:1420px}
.settings-hero{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:24px;padding:30px 34px;border-radius:22px;background:linear-gradient(135deg,#0f172a 0%,#14532d 58%,#22c55e 100%);box-shadow:0 18px 45px rgba(21,128,61,.18);color:#fff}
.settings-hero:before,.settings-hero:after{content:"";position:absolute;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none}.settings-hero:before{width:280px;height:280px;right:80px;top:-190px}.settings-hero:after{width:190px;height:190px;right:-60px;bottom:-110px}
.settings-hero-content{position:relative;z-index:1}.settings-eyebrow{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.1);border-radius:999px;font-size:.78rem;font-weight:700;margin-bottom:13px}.settings-hero h1{font-size:clamp(1.7rem,3vw,2.25rem);font-weight:800;letter-spacing:-.03em;margin:0 0 7px}.settings-hero p{margin:0;color:rgba(255,255,255,.76);max-width:720px}.settings-hero-icon{position:relative;z-index:1;display:grid;place-items:center;width:76px;height:76px;flex:0 0 auto;border:1px solid rgba(255,255,255,.16);border-radius:22px;background:rgba(255,255,255,.1);font-size:34px}
.settings-card{background:#fff;border:1px solid #e7ebf0;border-radius:18px;box-shadow:0 8px 25px rgba(15,23,42,.055);overflow:hidden}.settings-card-header{display:flex;gap:13px;align-items:flex-start;padding:22px 24px 17px}.settings-title-icon{width:40px;height:40px;flex:0 0 auto;display:grid;place-items:center;border-radius:12px;background:#f0fdf4;color:#15803d;font-size:18px}.settings-card-header h2{font-size:1rem;font-weight:800;margin:2px 0 4px;color:#0f172a}.settings-card-header p{margin:0;color:#64748b;font-size:.82rem;line-height:1.5}.settings-card-body{padding:0 24px 24px}.settings-card .form-label{font-size:.83rem;font-weight:700;color:#334155;margin-bottom:7px}.settings-card .form-control,.settings-card .form-select{background:#fff;min-height:45px}.settings-card textarea.form-control{min-height:96px;resize:vertical}.settings-card .form-text{font-size:.75rem;color:#94a3b8;margin-top:7px}.settings-input-group .input-group-text{background:#f8fafc;border-color:#dbe2ea;color:#64748b;font-size:.82rem;font-weight:600}.settings-input-group .form-control{border-right:0}.settings-input-group .input-group-text{border-radius:0 12px 12px 0}.settings-input-group .form-control{border-radius:12px 0 0 12px}
.logo-preview-wrap{height:190px;border:1px dashed #cfd8e3;border-radius:16px;background:linear-gradient(180deg,#f8fafc,#f1f5f9);display:grid;place-items:center;overflow:hidden}.logo-preview-wrap img{max-width:150px;max-height:150px;width:auto;height:auto;object-fit:contain;border-radius:10px}.logo-fallback{width:100px;height:100px;border-radius:24px;background:linear-gradient(135deg,#22c55e,#15803d);display:grid;place-items:center;color:#fff;font-size:40px;box-shadow:0 12px 25px rgba(22,163,74,.2)}.logo-status{display:flex;align-items:center;gap:7px;margin-top:10px;color:#15803d;font-size:.76rem;font-weight:700}.logo-status i{font-size:.8rem}
.access-box{display:flex;gap:12px;padding:14px;border:1px solid #dcfce7;background:#f7fff9;border-radius:14px}.access-icon{width:40px;height:40px;flex:0 0 auto;display:grid;place-items:center;border-radius:11px;background:#dcfce7;color:#15803d;font-size:18px}.access-box h3{font-size:.88rem;font-weight:800;margin:2px 0 4px}.access-box p{font-size:.75rem;line-height:1.55;color:#64748b;margin:0}.settings-summary{margin-top:17px;padding-top:15px;border-top:1px solid #eef2f7}.settings-summary>div{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:7px 0;font-size:.78rem}.settings-summary span:first-child{color:#64748b}.settings-summary strong{color:#334155}.status-badge{display:inline-flex!important;align-items:center;gap:6px;color:#15803d!important;font-weight:700}.status-badge span{width:7px;height:7px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 3px #dcfce7}
.settings-savebar{position:sticky;bottom:12px;z-index:20;display:flex;align-items:center;justify-content:space-between;gap:20px;padding:12px 14px 12px 17px;border:1px solid #e2e8f0;border-radius:17px;background:rgba(255,255,255,.95);backdrop-filter:blur(14px);box-shadow:0 12px 35px rgba(15,23,42,.11)}.savebar-info{display:flex;align-items:center;gap:10px;min-width:0}.savebar-icon{width:38px;height:38px;flex:0 0 auto;display:grid;place-items:center;border-radius:11px;background:#f0fdf4;color:#15803d}.savebar-info strong{display:block;font-size:.82rem;color:#334155}.savebar-info span{display:block;font-size:.73rem;color:#94a3b8;margin-top:2px}.settings-save-btn{min-height:43px;white-space:nowrap;box-shadow:0 7px 16px rgba(22,163,74,.18)}
@media(max-width:991.98px){.settings-page{padding-top:18px}.settings-hero{padding:26px}.settings-hero-icon{width:64px;height:64px;font-size:28px}.settings-savebar{bottom:8px}}
@media(max-width:575.98px){.settings-page{padding:12px 10px 28px}.settings-hero{padding:22px 20px;border-radius:17px}.settings-hero-icon{display:none}.settings-hero h1{font-size:1.55rem}.settings-hero p{font-size:.82rem}.settings-card{border-radius:16px}.settings-card-header{padding:18px 17px 14px}.settings-card-body{padding:0 17px 18px}.settings-title-icon{width:37px;height:37px}.logo-preview-wrap{height:165px}.settings-savebar{align-items:stretch;flex-direction:column;padding:12px}.savebar-info{align-items:flex-start}.settings-save-btn{width:100%}}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('storeLogoInput');
    const preview = document.getElementById('settingLogoPreview');
    const fallback = document.getElementById('settingLogoFallback');
    const removeBtn = document.getElementById('removeLogoBtn');
    const removeInput = document.getElementById('removeLogoInput');
    const status = document.getElementById('logoStatus');
    const form = document.getElementById('settingsForm');
    const saveBtn = document.getElementById('saveSettingsBtn');

    input?.addEventListener('change', function () {
        const file = this.files?.[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            this.value = '';
            alert('Ukuran logo maksimal 2 MB.');
            return;
        }
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            this.value = '';
            alert('Format logo harus JPG, PNG, atau WEBP.');
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            if (preview) { preview.src = e.target.result; preview.classList.remove('d-none'); }
            fallback?.classList.add('d-none');
            if (removeInput) removeInput.value = '0';
            if (status) status.innerHTML = '<i class="bi bi-eye-fill"></i><span>Preview logo baru</span>';
            if (removeBtn) { removeBtn.disabled = false; removeBtn.innerHTML = '<i class="bi bi-trash3 me-1"></i> Hapus Logo Kustom'; }
        };
        reader.readAsDataURL(file);
    });

    removeBtn?.addEventListener('click', function () {
        if (removeInput) removeInput.value = '1';
        if (input) input.value = '';
        if (preview) { preview.src = ''; preview.classList.add('d-none'); }
        fallback?.classList.remove('d-none');
        if (status) status.innerHTML = '<i class="bi bi-trash3-fill"></i><span>Logo akan dihapus saat disimpan</span>';
        this.disabled = true;
        this.innerHTML = '<i class="bi bi-check2 me-1"></i> Logo ditandai untuk dihapus';
    });

    form?.addEventListener('submit', function () {
        if (!saveBtn) return;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
    });
});
</script>
@endsection
