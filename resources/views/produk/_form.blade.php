@csrf
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<style>
        :root {
    --green-main: #22c55e;
    --green-dark: #15803d;
    --green-soft: #dcfce7;
    --gray-border: #e2e8f0;

    }

    .form-card-section {
    background-color: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--gray-border);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    overflow:hidden;
}

    .form-label-custom {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.4rem;
        display: inline-block;
    }

    .input-group-custom .input-group-text {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #64748b;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        font-weight: 600;
    }

    .form-control-custom {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 0.65rem 0.9rem;
        font-size: 0.9rem;
        transition: all 0.2s ease-in-out;
    }

    .input-group-custom .form-control-custom {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-control-custom:focus {
    border-color: var(--green-main) !important;
    box-shadow: 0 0 0 3.5px rgba(34, 197, 94, 0.15) !important;
    }

    /* UPLOAD FOTO AREA */
    .file-upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 1.5rem;
        text-align: center;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .file-upload-box:hover {
    border-color: var(--green-main);
    background-color: var(--green-soft);
    }

    .file-upload-box input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* BUTTONS */
    button.btn-gradient-submit {
    background: linear-gradient(
        135deg,
        #15803d,
        #22c55e
    );
    border: none;
    color: white;
    padding: 0.7rem 1.8rem;
    border-radius: 50px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
    transition: all 0.2s ease;
    }

.btn-gradient-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(34, 197, 94, 0.35);
    color: white;
    }

    a.btn-soft-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 0.7rem 1.8rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-soft-secondary:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .category-card{
    display:block;
    cursor:pointer;
}

.category-card input{
    display:none;
}

.category-content{
    position:relative;
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px;
    border:2px solid #e5e7eb;
    border-radius:16px;
    background:#fff;
    transition:.3s;
}

.category-content:hover{
    border-color:#22c55e;
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.category-image{
    width:95px;
    height:95px;
    object-fit:cover;
    border-radius:12px;
}

.category-text h5{
    margin:0;
    font-size:18px;
    font-weight:700;
}

.category-text small{
    color:#64748b;
}

.check-circle{
    position:absolute;
    right:15px;
    top:15px;
    width:24px;
    height:24px;
    border-radius:50%;
    border:2px solid #d1d5db;
}

.category-card input:checked + .category-content{
    border-color:#22c55e;
    background:#f0fdf4;
}

.category-card input:checked + .category-content .check-circle{
    background:#22c55e;
    border-color:#22c55e;
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
}

</style>

<div class="row g-4">

    {{-- UPLOAD FOTO PRODUK --}}
    <div class="col-12">
        <div class="p-4 form-card-section">
            <label class="form-label-custom">Foto Produk</label>
            
            <div class="file-upload-box mb-2" id="dropArea">
                <input type="file" 
                       name="foto" 
                       id="fotoInput" 
                       onchange="previewImage(this)" 
                       accept="image/*">
                
                <div id="uploadPlaceholder">
                    <i class="bi bi-cloud-arrow-up fs-1" style="color: var(--green-main);"></i>
                    <p class="mb-1 mt-2 fw-semibold text-dark">Klik atau geser foto ke sini untuk mengunggah</p>
                    <span class="text-muted small">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                </div>

                {{-- PREVIEW FOTO --}}
                <div id="previewContainer" class="mt-2" style="{{ isset($produk) && $produk->foto ? '' : 'display:none;' }}">
                    <div class="position-relative d-inline-block">
                        <img id="preview" 
                             src="{{ isset($produk) && $produk->foto ? asset('storage/' . $produk->foto) : '#' }}" 
                             class="rounded-3 shadow-sm border" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <span class="badge position-absolute bottom-0 start-50 translate-middle-x mb-1 px-2 py-1"
                        style="
                            font-size: 0.7rem;
                            background: var(--green-soft);
                            color: var(--green-dark);">
                                Preview
                        </span>
                    </div>
                </div>
            </div>

            @error('foto')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>
    </div>


    {{-- NAMA PRODUK --}}
    <div class="col-12">
        <div class="p-4 form-card-section">
            <label class="form-label-custom">
                Nama Produk <span class="text-danger">*</span>
            </label>
            <div class="input-group input-group-custom">
                <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                <input type="text"
                       name="name"
                       class="form-control form-control-custom @error('name') is-invalid @enderror"
                       placeholder="Contoh: Kopi Susu Gula Aren"
                       value="{{ old('name', $produk->nama ?? '') }}"
                       required>
            </div>
            @error('name')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>
    </div>


   {{-- KATEGORI PRODUK --}}
<div class="col-12">
    <div class="p-4 form-card-section">

        <label class="form-label-custom">
            Jenis Produk <span class="text-danger">*</span>
        </label>

        <div class="row g-3">

            @foreach($categories as $category)

                @php
                    $foto = match(strtolower($category->nama)) {
                        'makanan' => 'makanan.jpg',
                        'minuman' => 'minuman.jpg',
                        'snack' => 'snack.jpg',
                        'elektronik' => 'elektronik.jpg',
                        default => 'default.jpg',
                    };
                @endphp

                <div class="col-md-6 col-lg-3">

                    <label class="category-card">

                        <input
                            type="radio"
                            name="category_id"
                            value="{{ $category->id }}"
                            {{ old('category_id', $produk->category_id ?? '') == $category->id ? 'checked' : '' }}
                        >

                        <div class="category-content">

                            <img
                                src="{{ asset('images/categories/'.$foto) }}"
                                class="category-image"
                            >

                            <div class="category-text">
                                <h5>{{ $category->nama }}</h5>
                                <small>{{ $category->deskripsi ?? 'Kategori Produk' }}</small>
                            </div>

                            <span class="check-circle">
                                <i class="bi bi-check"></i>
                            </span>

                        </div>

                    </label>

                </div>

            @endforeach

        </div>

        @error('category_id')
            <div class="text-danger mt-2">
                {{ $message }}
            </div>
        @enderror

    </div>
</div>

    {{-- HARGA BELI & HARGA JUAL --}}
    <div class="col-md-6">
        <div class="p-4 form-card-section h-100">
            <label class="form-label-custom">
                Harga Beli (Modal) <span class="text-danger">*</span>
            </label>
            <div class="input-group input-group-custom mb-2">
                <span class="input-group-text">Rp</span>
                <input type="number"
                       id="hargaBeli"
                       name="purchase_price"
                       class="form-control form-control-custom @error('purchase_price') is-invalid @enderror"
                       placeholder="0"
                       value="{{ old('purchase_price', $produk->harga_beli ?? '') }}"
                       oninput="hitungskalaku()"
                       required>
            </div>
            @error('purchase_price')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="p-4 form-card-section h-100">
            <label class="form-label-custom">
                Harga Jual <span class="text-danger">*</span>
            </label>
            <div class="input-group input-group-custom mb-2">
                <span class="input-group-text">Rp</span>
                <input type="number"
                       id="hargaJual"
                       name="selling_price"
                       class="form-control form-control-custom @error('selling_price') is-invalid @enderror"
                       placeholder="0"
                       value="{{ old('selling_price', $produk->harga_jual ?? '') }}"
                       oninput="hitungskalaku()"
                       required>
            </div>
            
            {{-- ESTIMASI MARGIN KEUNTUNGAN --}}
            <div id="marginInfo" class="small fw-semibold text-muted">
                Estimasi Profit: <span id="marginValue" class="text-success">Rp 0</span>
            </div>

            @error('selling_price')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>
    </div>


    {{-- STOK --}}
    <div class="col-12">
        <div class="p-4 form-card-section">
            <label class="form-label-custom">
                Stok Awal / Gudang <span class="text-danger">*</span>
            </label>

            <div class="input-group input-group-custom">
                <span class="input-group-text">
                    <i class="bi bi-stack"></i>
                </span>

                <input type="number"
                       name="stock"
                       class="form-control form-control-custom"
                       placeholder="0"
                       value="{{ old('stock', $produk->stok ?? '') }}"
                       required>

                <span class="input-group-text bg-light text-muted">
                    Unit
                </span>
            </div>
        </div>
    </div>


    {{-- SATUAN --}}
    <div class="col-md-6">
        <div class="p-4 form-card-section">

            <label class="form-label-custom">
                Satuan <span class="text-danger">*</span>
            </label>

            <select name="satuan"
        class="form-control form-control-custom"
        required>

<option value="">Pilih Satuan</option>

<option value="pcs"
{{ old('satuan',$produk->satuan ?? '')=='pcs'?'selected':'' }}>
PCS
</option>

<option value="kg"
{{ old('satuan',$produk->satuan ?? '')=='kg'?'selected':'' }}>
Kg
</option>

<option value="gram"
{{ old('satuan',$produk->satuan ?? '')=='gram'?'selected':'' }}>
Gram
</option>

<option value="liter"
{{ old('satuan',$produk->satuan ?? '')=='liter'?'selected':'' }}>
Liter
</option>

<option value="botol"
{{ old('satuan',$produk->satuan ?? '')=='botol'?'selected':'' }}>
Botol
</option>

<option value="pack"
{{ old('satuan',$produk->satuan ?? '')=='pack'?'selected':'' }}>
Pack
</option>

</select>

        </div>
    </div>


    {{-- MINIMUM STOK --}}
    <div class="col-md-6">
        <div class="p-4 form-card-section">

            <label class="form-label-custom">
                Minimum Stok
            </label>

            <input type="number"
                   name="minimum_stok"
                   class="form-control form-control-custom"
                   value="{{ old('minimum_stok', $produk->minimum_stok ?? 0) }}">

        </div>
    </div>


    {{-- DESKRIPSI --}}
    <div class="col-12">
        <div class="p-4 form-card-section">

            <label class="form-label-custom">
                Deskripsi
            </label>

            <textarea name="deskripsi"
                      rows="4"
                      class="form-control form-control-custom">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>

        </div>
    </div>


{{-- TOMBOL AKSES --}}
<div class="d-flex align-items-center gap-3 mt-4">
    <button type="submit" class="btn btn-gradient-submit d-inline-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        <span>Simpan Produk</span>
    </button>

    <a href="{{ route('produk.index') }}" class="btn btn-soft-secondary d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Batal</span>
    </a>
</div>


{{-- JAVASCRIPT PREVIEW & CALCULATOR MARGIN --}}
<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const container = document.getElementById('previewContainer');
        const placeholder = document.getElementById('uploadPlaceholder');

        const file = input.files[0];

        if(file && preview && container && placeholder){

             preview.src = URL.createObjectURL(file);
             container.style.display = 'block';
             placeholder.style.display = 'none';

}
    }

    function hitungskalaku() {
        const beli = parseFloat(document.getElementById('hargaBeli').value) || 0;
        const jual = parseFloat(document.getElementById('hargaJual').value) || 0;
        const profit = jual - beli;
        const marginElem = document.getElementById('marginValue');

        if (jual > 0) {
            if (profit >= 0) {
                marginElem.className = 'text-success fw-bold';
                marginElem.textContent = 'Rp ' + profit.toLocaleString('id-ID') + ' (Untung)';
            } else {
                marginElem.className = 'text-danger fw-bold';
                marginElem.textContent = 'Rp ' + profit.toLocaleString('id-ID') + ' (Rugi)';
            }
        } else {
            marginElem.className = 'text-muted';
            marginElem.textContent = 'Rp 0';
        }
    }

    // Jalankan kalkulasi profit awal jika sedang mode edit data
    document.addEventListener('DOMContentLoaded', hitungskalaku);
</script>