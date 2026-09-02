@csrf

<style>
    .user-form-label{font-size:12px;font-weight:700;color:#334155;margin-bottom:7px}
    .user-input,.user-select{height:44px;border:1px solid #dbe3e8;border-radius:11px;font-size:13px}
    .user-input:focus,.user-select:focus{border-color:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.1)}
    .upload-panel{border:1px dashed #cbd5e1;border-radius:15px;background:#f8fafc;padding:16px}
    .avatar-form{width:86px;height:86px;border-radius:50%;object-fit:cover;border:3px solid #fff;box-shadow:0 5px 16px rgba(15,23,42,.12);background:#dcfce7}
    .section-title{font-size:13px;font-weight:800;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px}
    .section-title i{color:#16a34a}
    .password-note{font-size:11px;color:#64748b;margin-top:5px}
    .form-error{font-size:11px;color:#dc2626;margin-top:5px}
</style>

<div class="mb-4">
    <div class="section-title"><i class="bi bi-person-badge-fill"></i>Informasi Akun</div>
    <div class="row g-3">
        <div class="col-12">
            <div class="upload-panel">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                    <img id="imagePreview" class="avatar-form" src="{{ isset($user) && $user->photo ? asset('storage/'.$user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'User').'&background=dcfce7&color=15803d&bold=true' }}" alt="Preview foto">
                    <div class="flex-grow-1">
                        <label class="user-form-label d-block">Foto Profil <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="file" name="photo" id="photoInput" class="form-control user-input @error('photo') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp" onchange="previewPhoto(event)">
                        <div class="password-note">JPG, JPEG, PNG, WEBP • maksimal 2MB.</div>
                        @error('photo')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label class="user-form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name',$user->name ?? '') }}" class="form-control user-input @error('name') is-invalid @enderror" placeholder="Contoh: Hadi Santoso" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="user-form-label">Alamat Email <span class="text-danger">*</span></label>
            <input type="email" name="email" value="{{ old('email',$user->email ?? '') }}" class="form-control user-input @error('email') is-invalid @enderror" placeholder="nama@email.com" required>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="section-title"><i class="bi bi-shield-lock-fill"></i>Hak Akses</div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="user-form-label">Role <span class="text-danger">*</span></label>
            <select name="role_id" class="form-select user-select @error('role_id') is-invalid @enderror" required>
                <option value="">Pilih Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id',$user->role_id ?? '') == $role->id)>{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
            @error('role_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="user-form-label">Password {{ isset($user) ? '(opsional)' : '*' }}</label>
            <div class="input-group">
                <input type="password" name="password" id="userPassword" class="form-control user-input" placeholder="{{ isset($user) ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}" {{ isset($user) ? '' : 'required' }}>
                <button type="button" class="btn btn-light border" style="border-radius:0 11px 11px 0" onclick="toggleUserPassword()" title="Tampilkan password"><i class="bi bi-eye" id="passwordEye"></i></button>
            </div>
            <div class="password-note">Minimal 8 karakter. {{ isset($user) ? 'Kosongkan jika password tidak ingin diubah.' : '' }}</div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top">
    <a href="{{ route('admin.users') }}" class="btn btn-light border rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i>Batal</a>
    <button type="submit" class="btn btn-success rounded-pill px-4"><i class="bi bi-check-circle-fill me-1"></i>{{ isset($user) ? 'Simpan Perubahan' : 'Tambah User' }}</button>
</div>

<script>
function previewPhoto(event){const file=event.target.files?.[0],preview=document.getElementById('imagePreview');if(!file)return;if(file.size>2*1024*1024){alert('Ukuran foto maksimal 2MB.');event.target.value='';return;}const reader=new FileReader();reader.onload=e=>preview.src=e.target.result;reader.readAsDataURL(file);}
function toggleUserPassword(){const input=document.getElementById('userPassword'),icon=document.getElementById('passwordEye');const show=input.type==='password';input.type=show?'text':'password';icon.className=show?'bi bi-eye-slash':'bi bi-eye';}
</script>
