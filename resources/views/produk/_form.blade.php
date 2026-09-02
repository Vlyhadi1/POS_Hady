@csrf

<style>
    .product-form-section{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:20px;height:100%;box-shadow:0 8px 24px rgba(15,23,42,.035)}
    .product-form-label{display:block;font-size:.78rem;font-weight:700;color:#334155;margin-bottom:8px}
    .product-form-label .required{color:#dc2626}
    .product-input,.product-select,.product-textarea{width:100%;border:1px solid #dbe2ea;border-radius:11px;background:#fff;color:#1e293b;font-size:.88rem;outline:0;transition:.2s}
    .product-input,.product-select{height:45px;padding:0 13px}.product-textarea{padding:12px 13px;resize:vertical;min-height:105px}
    .product-input:focus,.product-select:focus,.product-textarea:focus{border-color:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.12)}
    .input-prefix{display:flex}.input-prefix span{height:45px;display:grid;place-items:center;padding:0 12px;background:#f8fafc;border:1px solid #dbe2ea;border-right:0;border-radius:11px 0 0 11px;color:#64748b;font-size:.82rem;font-weight:700}.input-prefix .product-input{border-radius:0 11px 11px 0}
    .upload-box{border:2px dashed #cbd5e1;border-radius:14px;background:#f8fafc;padding:22px;text-align:center;position:relative;transition:.2s;cursor:pointer}.upload-box:hover,.upload-box.dragover{border-color:#22c55e;background:#f0fdf4}
    .upload-box input{position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer}.upload-icon{width:50px;height:50px;border-radius:14px;background:#dcfce7;color:#15803d;display:grid;place-items:center;font-size:22px;margin:0 auto 10px}
    .preview-wrap{display:flex;align-items:center;gap:15px;text-align:left}.preview-img{width:86px;height:86px;object-fit:cover;border-radius:12px;border:1px solid #dbe2ea}.preview-name{font-size:.8rem;font-weight:700;color:#334155;word-break:break-word}
    .category-select-wrap{position:relative}.category-select-wrap i{position:absolute;pointer-events:none;top:50%;transform:translateY(-50%);color:#64748b}.category-left-icon{left:14px}.category-arrow{right:14px}.category-select{appearance:none;padding-left:42px;padding-right:40px}
    .help-text{font-size:.72rem;color:#94a3b8;margin-top:6px}.invalid-feedback-custom{color:#dc2626;font-size:.75rem;margin-top:6px}
    .status-box{display:flex;align-items:center;justify-content:space-between;gap:15px;padding:12px 14px;border:1px solid #dbe2ea;border-radius:11px;background:#f8fafc}.status-box small{color:#64748b}.switch{position:relative;width:42px;height:24px;display:inline-block;flex:0 0 auto}.switch input{opacity:0;width:0;height:0}.slider{position:absolute;inset:0;background:#cbd5e1;border-radius:999px;cursor:pointer;transition:.2s}.slider:before{content:"";position:absolute;width:18px;height:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.18)}.switch input:checked+.slider{background:#16a34a}.switch input:checked+.slider:before{transform:translateX(18px)}
    .form-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:4px}.btn-save{border:0;background:linear-gradient(135deg,#22c55e,#15803d);color:#fff;border-radius:11px;padding:11px 19px;font-weight:700;box-shadow:0 8px 18px rgba(22,163,74,.18)}.btn-save:hover{color:#fff;filter:brightness(.96);transform:translateY(-1px)}
    @media(max-width:575px){.product-form-section{padding:16px}.form-actions>*{width:100%;text-align:center}.preview-wrap{align-items:flex-start}}
</style>

<div class="row g-3">
    <div class="col-12">
        <div class="product-form-section">
            <label class="product-form-label">Foto Produk <span class="text-muted fw-normal">(opsional)</span></label>
            <div class="upload-box" id="uploadBox">
                <input type="file" name="foto" id="fotoInput" accept="image/jpeg,image/png,image/webp">
                <div id="uploadEmpty" style="{{ isset($produk) && $produk->foto ? 'display:none' : '' }}">
                    <div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                    <div class="fw-bold text-dark small">Klik untuk memilih foto</div>
                    <div class="help-text">JPG, JPEG, PNG, WEBP • maksimal 2 MB</div>
                </div>
                <div id="uploadPreview" class="preview-wrap" style="{{ isset($produk) && $produk->foto ? '' : 'display:none' }}">
                    <img id="previewImage" class="preview-img" src="{{ isset($produk) && $produk->foto ? asset('storage/'.$produk->foto) : '#' }}" alt="Preview produk">
                    <div>
                        <div class="preview-name" id="previewName">{{ isset($produk) && $produk->foto ? basename($produk->foto) : '' }}</div>
                        <div class="help-text">Foto akan digunakan sebagai gambar produk.</div>
                    </div>
                </div>
            </div>
            @error('foto')<div class="invalid-feedback-custom"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="product-form-section">
            <label class="product-form-label">Nama Produk <span class="required">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-box-seam"></i></span>
                <input type="text" name="name" class="product-input border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $produk->nama ?? '') }}" placeholder="Contoh: Kopi Susu Gula Aren" required maxlength="255">
            </div>
            @error('name')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="product-form-section">
            <label for="category_id" class="product-form-label">Jenis Produk <span class="required">*</span></label>
            <div class="category-select-wrap">
                <i class="bi bi-tags category-left-icon"></i>
                <select name="category_id" id="category_id" class="product-select category-select @error('category_id') is-invalid @enderror" required>
                    <option value="">Pilih Jenis Produk</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string)old('category_id', $produk->category_id ?? '') === (string)$category->id)>
                            {{ $category->nama }}{{ !$category->status ? ' (Nonaktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down category-arrow"></i>
            </div>
            @error('category_id')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="product-form-section">
            <label class="product-form-label">Harga Beli / Modal <span class="required">*</span></label>
            <div class="input-prefix"><span>Rp</span><input type="number" min="0" step="1" inputmode="numeric" id="hargaBeli" name="purchase_price" class="product-input @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price', $produk->harga_beli ?? '') }}" placeholder="0" required></div>
            @error('purchase_price')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="product-form-section">
            <label class="product-form-label">Harga Jual <span class="required">*</span></label>
            <div class="input-prefix"><span>Rp</span><input type="number" min="0" step="1" inputmode="numeric" id="hargaJual" name="selling_price" class="product-input @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', $produk->harga_jual ?? '') }}" placeholder="0" required></div>
            <div id="profitInfo" class="help-text">Estimasi keuntungan: <strong id="profitValue" class="text-success">Rp 0</strong></div>
            @error('selling_price')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="product-form-section">
            <label class="product-form-label">Stok Awal / Gudang <span class="required">*</span></label>
            <div class="input-prefix"><span><i class="bi bi-stack"></i></span><input type="number" min="0" step="1" inputmode="numeric" name="stock" class="product-input @error('stock') is-invalid @enderror" value="{{ old('stock', $produk->stok ?? 0) }}" required></div>
            @error('stock')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="product-form-section">
            <label class="product-form-label">Satuan <span class="required">*</span></label>
            <select name="satuan" class="product-select @error('satuan') is-invalid @enderror" required>
                <option value="">Pilih Satuan</option>
                @foreach(['Pcs'=>'Pcs','Box'=>'Box','Pack'=>'Pack','Botol'=>'Botol','Liter'=>'Liter','Kg'=>'Kg','Gram'=>'Gram','Lusin'=>'Lusin','Unit'=>'Unit'] as $value=>$label)
                    <option value="{{ $value }}" @selected(strtolower((string)old('satuan', $produk->satuan ?? '')) === strtolower($value))>{{ $label }}</option>
                @endforeach
            </select>
            @error('satuan')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="product-form-section">
            <label class="product-form-label">Minimum Stok</label>
            <input type="number" name="minimum_stok" min="0" step="1" inputmode="numeric" class="product-input" value="{{ old('minimum_stok', $produk->minimum_stok ?? 0) }}" placeholder="0">
            <div class="help-text">Produk dianggap kritis jika stok ≤ nilai ini.</div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="product-form-section">
            <label class="product-form-label">Status Produk</label>
            <div class="status-box">
                <div><strong class="d-block small">Produk Aktif</strong><small>Bisa muncul dan dijual di halaman kasir.</small></div>
                <label class="switch" aria-label="Status produk">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" @checked((bool) old('status', isset($produk) ? $produk->status : true))>
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="product-form-section">
            <label class="product-form-label">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
            <textarea name="deskripsi" rows="4" maxlength="2000" class="product-textarea" placeholder="Tambahkan keterangan produk jika diperlukan...">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
            <div class="help-text">Maksimal 2.000 karakter.</div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-actions">
            <button type="submit" class="btn-save"><i class="bi bi-check-circle-fill me-1"></i>{{ isset($produk) ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
            <a href="{{ route('produk.index') }}" class="btn btn-light border rounded-3 px-4 py-2 fw-semibold"><i class="bi bi-arrow-left me-1"></i>Batal</a>
        </div>
    </div>
</div>

<script>
(function(){
    const input = document.getElementById('fotoInput');
    const box = document.getElementById('uploadBox');
    const empty = document.getElementById('uploadEmpty');
    const preview = document.getElementById('uploadPreview');
    const image = document.getElementById('previewImage');
    const name = document.getElementById('previewName');
    const beli = document.getElementById('hargaBeli');
    const jual = document.getElementById('hargaJual');
    const profit = document.getElementById('profitValue');

    input?.addEventListener('change', function(){
        const file = this.files?.[0];
        if(!file) return;
        if(file.size > 2 * 1024 * 1024){ alert('Ukuran foto maksimal 2 MB.'); this.value=''; return; }
        image.src = URL.createObjectURL(file);
        name.textContent = file.name;
        empty.style.display='none'; preview.style.display='flex';
    });
    ['dragenter','dragover'].forEach(evt=>box?.addEventListener(evt,e=>{e.preventDefault();box.classList.add('dragover')}));
    ['dragleave','drop'].forEach(evt=>box?.addEventListener(evt,e=>{e.preventDefault();box.classList.remove('dragover')}));

    function calculate(){
        const b = Number(beli?.value || 0), j = Number(jual?.value || 0), p = j-b;
        if(!profit) return;
        profit.textContent = 'Rp ' + Math.abs(p).toLocaleString('id-ID') + (p < 0 ? ' (Rugi)' : ' (Untung)');
        profit.className = p < 0 ? 'text-danger fw-bold' : 'text-success fw-bold';
    }
    beli?.addEventListener('input',calculate); jual?.addEventListener('input',calculate); calculate();
})();
</script>
