@extends('layouts.app')

@section('title', 'Profil Saya - POS HADI')

@section('content')
@include('layouts.navbar')

<main class="profile-page">
    <div class="profile-container">
        <div class="profile-hero">
            <div>
                <span class="profile-eyebrow"><i class="bi bi-person-circle"></i> AKUN SAYA</span>
                <h1>Profil Saya</h1>
                <p>Kelola informasi akun, foto profil, dan keamanan password kamu.</p>
            </div>
            <div class="profile-role-top">
                <i class="bi bi-shield-check"></i>
                {{ ucfirst(optional($user->role)->name ?? 'Staff') }}
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="profile-card profile-photo-card h-100">
                        <div class="card-title-row">
                            <div>
                                <h5>Foto Profil</h5>
                                <p>Gunakan foto yang jelas agar akun mudah dikenali.</p>
                            </div>
                            <i class="bi bi-camera"></i>
                        </div>

                        <div class="avatar-wrap">
                            @if($user->photo)
                                <img id="profilePreview" src="{{ asset('storage/' . $user->photo) }}" alt="Foto profil {{ $user->name }}">
                            @else
                                <div id="profileInitials" class="profile-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <img id="profilePreview" src="" alt="Preview foto profil" class="d-none">
                            @endif
                        </div>

                        <label for="photo" class="btn btn-outline-success w-100 mt-3">
                            <i class="bi bi-upload me-1"></i>Pilih Foto
                        </label>
                        <input id="photo" type="file" name="photo" class="d-none" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" onchange="previewProfile(event)">

                        @if($user->photo)
                            <label class="remove-photo-option mt-3">
                                <input type="checkbox" name="remove_photo" value="1" {{ old('remove_photo') ? 'checked' : '' }}>
                                <span><i class="bi bi-trash3"></i> Hapus foto profil</span>
                            </label>
                        @endif

                        <div class="photo-note mt-3">
                            <i class="bi bi-info-circle"></i>
                            <span>JPG, JPEG, PNG, atau WEBP. Maksimal 2MB.</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="profile-card">
                        <div class="card-title-row">
                            <div>
                                <h5>Informasi Akun</h5>
                                <p>Data dasar yang digunakan pada akun POS HADI.</p>
                            </div>
                            <i class="bi bi-person-vcard"></i>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <div class="input-group-modern">
                                    <i class="bi bi-person"></i>
                                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="100" autocomplete="name">
                                </div>
                                @error('name') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group-modern">
                                    <i class="bi bi-envelope"></i>
                                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email">
                                </div>
                                @error('email') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Role Akun</label>
                                <div class="role-display">
                                    <span class="role-icon"><i class="bi bi-shield-lock"></i></span>
                                    <div>
                                        <strong>{{ ucfirst(optional($user->role)->name ?? 'Staff') }}</strong>
                                        <small>Role dikelola oleh administrator.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card mt-4">
                        <div class="card-title-row">
                            <div>
                                <h5>Keamanan Akun</h5>
                                <p>Ubah password jika kamu ingin meningkatkan keamanan akun.</p>
                            </div>
                            <i class="bi bi-key"></i>
                        </div>

                        <div class="security-tip">
                            <i class="bi bi-shield-check"></i>
                            <span>Jika tidak ingin mengubah password, biarkan bagian ini kosong.</span>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <div class="password-input">
                                    <input id="current_password" type="password" name="current_password" autocomplete="current-password" placeholder="Password saat ini">
                                    <button type="button" onclick="togglePassword('current_password', this)" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                                </div>
                                @error('current_password') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="password-input">
                                    <input id="password" type="password" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter">
                                    <button type="button" onclick="togglePassword('password', this)" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                                </div>
                                @error('password') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <div class="password-input">
                                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password baru">
                                    <button type="button" onclick="togglePassword('password_confirmation', this)" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card account-info mt-4">
                        <div><span>Status Akun</span><strong><i class="bi bi-check-circle-fill"></i> Aktif</strong></div>
                        <div><span>ID User</span><strong>#{{ $user->id }}</strong></div>
                        <div><span>Email Terverifikasi</span><strong><i class="bi bi-envelope-check"></i> Terdaftar</strong></div>
                    </div>

                    <div class="profile-actions">
                        <a href="{{ route('dashboard') }}" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check2-circle me-1"></i>Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<style>
    .profile-page { padding: 28px 20px 50px; background: #f5f7fb; min-height: calc(100vh - 70px); }
    .profile-container { max-width: 1180px; margin: 0 auto; }
    .profile-hero { background: linear-gradient(135deg, #071a2f, #0d6b50); border-radius: 22px; padding: 28px 30px; color: #fff; display:flex; justify-content:space-between; align-items:center; gap:20px; margin-bottom:22px; box-shadow: 0 12px 28px rgba(7,26,47,.12); }
    .profile-eyebrow { font-size: 11px; font-weight: 800; letter-spacing: .12em; opacity:.8; }
    .profile-hero h1 { margin: 7px 0 4px; font-size: 30px; font-weight: 800; }
    .profile-hero p { margin:0; opacity:.78; }
    .profile-role-top { background: rgba(255,255,255,.13); border:1px solid rgba(255,255,255,.2); border-radius:999px; padding:9px 15px; font-weight:700; white-space:nowrap; }
    .profile-card { background:#fff; border:1px solid #e8edf3; border-radius:18px; padding:22px; box-shadow:0 6px 18px rgba(25,45,70,.05); }
    .card-title-row { display:flex; justify-content:space-between; gap:15px; margin-bottom:20px; }
    .card-title-row h5 { margin:0; font-weight:800; color:#162235; }
    .card-title-row p { margin:4px 0 0; color:#7b8794; font-size:13px; }
    .card-title-row > i { font-size:22px; color:#198754; }
    .avatar-wrap { display:flex; justify-content:center; }
    .avatar-wrap img, .profile-initials { width:170px; height:170px; border-radius:50%; object-fit:cover; border:6px solid #eef7f2; box-shadow:0 8px 24px rgba(0,0,0,.09); }
    .profile-initials { display:flex; align-items:center; justify-content:center; background:#e7f5ee; color:#157347; font-size:55px; font-weight:800; }
    .photo-note { display:flex; gap:8px; font-size:12px; color:#7b8794; line-height:1.5; }
    .remove-photo-option { display:flex; align-items:center; gap:8px; font-size:13px; color:#b02a37; cursor:pointer; }
    .remove-photo-option input { accent-color:#dc3545; }
    .form-label { font-size:13px; font-weight:700; color:#344054; margin-bottom:7px; }
    .input-group-modern, .password-input { display:flex; align-items:center; border:1px solid #dfe5ec; border-radius:11px; overflow:hidden; background:#fff; transition:.2s; }
    .input-group-modern:focus-within, .password-input:focus-within { border-color:#198754; box-shadow:0 0 0 3px rgba(25,135,84,.1); }
    .input-group-modern > i { padding-left:13px; color:#8a96a3; }
    .input-group-modern input, .password-input input { border:0; outline:0; width:100%; padding:11px 12px; background:transparent; font-size:14px; }
    .password-input button { border:0; background:transparent; padding:10px 12px; color:#7b8794; cursor:pointer; }
    .field-error { color:#dc3545; font-size:12px; margin-top:5px; }
    .role-display { display:flex; align-items:center; gap:12px; background:#f7faf8; border:1px solid #e1eee7; padding:12px 14px; border-radius:12px; }
    .role-icon { width:38px; height:38px; display:grid; place-items:center; border-radius:10px; background:#dff3e8; color:#157347; }
    .role-display strong { display:block; font-size:14px; }
    .role-display small { color:#8a96a3; }
    .security-tip { display:flex; gap:9px; align-items:center; padding:10px 12px; border-radius:10px; background:#f0f8f4; color:#416b55; font-size:12px; }
    .account-info { display:grid; grid-template-columns:repeat(3,1fr); gap:15px; }
    .account-info span { display:block; color:#8a96a3; font-size:11px; text-transform:uppercase; letter-spacing:.05em; font-weight:700; margin-bottom:5px; }
    .account-info strong { color:#253448; font-size:14px; }
    .account-info strong i { color:#198754; }
    .profile-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:18px; }
    @media(max-width:767px){ .profile-page{padding:18px 12px 35px}.profile-hero{padding:22px;display:block}.profile-role-top{display:inline-block;margin-top:16px}.profile-hero h1{font-size:26px}.account-info{grid-template-columns:1fr}.profile-actions{justify-content:stretch}.profile-actions .btn{flex:1}.profile-card{padding:18px} }
</style>

<script>
function previewProfile(event) {
    const file = event.target.files?.[0];
    if (!file) return;

    const preview = document.getElementById('profilePreview');
    const initials = document.getElementById('profileInitials');
    const reader = new FileReader();

    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        if (initials) initials.classList.add('d-none');
    };

    reader.readAsDataURL(file);
}

function togglePassword(id, button) {
    const input = document.getElementById(id);
    const icon = button.querySelector('i');
    const visible = input.type === 'text';
    input.type = visible ? 'password' : 'text';
    icon.className = visible ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endsection
