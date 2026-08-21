@csrf

<style>
    :root {
        --green-main:#22c55e;
        --green-dark:#15803d;
    }

    .form-label-custom {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.35rem;
    }

    .form-control-custom, .form-select-custom {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.6rem 0.85rem;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        border-color:#22c55e !important;
        box-shadow:0 0 0 3px rgba(34,197,94,.15) !important;
    }

    button.btn-gradient-submit {
        background:linear-gradient(135deg,#15803d,#22c55e) !important;
        border: none !important;
        color: white !important;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-gradient-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
}

    a.btn-soft-secondary {
        background-color: #f1f5f9 !important;
        color: #64748b !important;
        border: none !important;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-soft-secondary:hover {
        background-color: #e2e8f0 !important;
        color: #334155 !important;
    }

    /* Style Tambahan untuk Upload Foto */
    .avatar-upload-box {
        position: relative;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #f1f5f9;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        background-color: #f8fafc;
    }

    .avatar-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="row g-3">
    {{-- UPLOAD FOTO PROFIL --}}
    <div class="col-12 mb-2">
        <label class="form-label-custom d-block">Foto Profil</label>
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-upload-box flex-shrink-0">
                <img id="imagePreview" 
                     src="{{ isset($user) && $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=dcfce7&color=15803d' }}" 
                     alt="Preview Foto" 
                     class="avatar-preview-img">
            </div>
            <div>
                <input type="file" 
                       name="photo" 
                       id="photoInput" 
                       class="form-control form-control-custom @error('photo') is-invalid @enderror" 
                       accept="image/*"
                       onchange="previewPhoto(event)">
                <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</small>
                @error('photo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- NAMA USER --}}
    <div class="col-12">
        <label class="form-label-custom">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 8px 0 0 8px;">
                <i class="bi bi-person-fill" style="color: #15803d;"></i>
            </span>
            <input type="text" 
                   name="name"
                   class="form-control form-control-custom border-start-0 @error('name') is-invalid @enderror"
                   placeholder="Masukkan nama pengguna..."
                   value="{{ old('name', $user->name ?? '') }}">
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- EMAIL --}}
    <div class="col-md-6">
        <label class="form-label-custom">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 8px 0 0 8px;">
                <i class="bi bi-envelope-fill" style="color: #15803d;"></i>
            </span>
            <input type="email" 
                   name="email"
                   class="form-control form-control-custom border-start-0 @error('email') is-invalid @enderror"
                   placeholder="contoh@domain.com"
                   value="{{ old('email', $user->email ?? '') }}">
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- PASSWORD --}}
    <div class="col-md-6">
        <label class="form-label-custom">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 8px 0 0 8px;">
                <i class="bi bi-key-fill" style="color: #15803d;"></i>
            </span>
            <input type="password" 
                   name="password"
                   class="form-control form-control-custom border-start-0 @error('password') is-invalid @enderror"
                   placeholder="{{ isset($user) ? 'Kosongkan jika tidak diubah' : 'Masukkan password...' }}">
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        @if(isset($user))
            <small class="text-muted d-block mt-1">Isi kolom password hanya jika ingin mengganti password lama.</small>
        @endif
    </div>

    {{-- ROLE --}}
    <div class="col-12">
        <label class="form-label-custom">Role Access</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 8px 0 0 8px;">
                <i class="bi bi-shield-lock-fill" style="color: #15803d;"></i>
            </span>
            <select name="role_id"
                    class="form-select form-select-custom border-start-0 @error('role_id') is-invalid @enderror">
                <option value="">-- Pilih Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        @selected(old('role_id', $user->role_id ?? '') == $role->id)>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

{{-- TOMBOL AKSI --}}
<div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
    <button class="btn btn-gradient-submit d-inline-flex align-items-center gap-2" type="submit">
        <i class="bi bi-check-circle-fill"></i>
        <span>Simpan User</span>
    </button>
    <a href="{{ route('admin.users') }}" class="btn btn-soft-secondary d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali</span>
    </a>
</div>

{{-- SCRIPT JAVASCRIPT UNTUK PREVIEW GAMBAR --}}
<script>
    function previewPhoto(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>