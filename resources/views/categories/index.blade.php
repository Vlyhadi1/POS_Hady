@extends('layouts.app')
@section('title', $title ?? 'Kategori - POS HADI')
@section('content')
@include('layouts.navbar')

<main class="pos-page category-page">
    <div class="pos-container">
        <div class="category-head">
            <div>
                <div class="category-kicker"><i class="bi bi-tags-fill"></i> Manajemen Produk</div>
                <h3 class="fw-bold mb-1">Kategori Produk</h3>
                <div class="text-muted small">Kelola kategori dan pengelompokan produk POS HADI.</div>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success category-add-btn">
                <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
            </a>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6 col-xl-3">
                <div class="category-stat">
                    <div class="stat-icon stat-green"><i class="bi bi-tags-fill"></i></div>
                    <div><div class="stat-label">Total Kategori</div><div class="stat-value">{{ $categories->total() }}</div><div class="stat-note">Semua kategori terdaftar</div></div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="category-stat">
                    <div class="stat-icon stat-blue"><i class="bi bi-check-circle-fill"></i></div>
                    <div><div class="stat-label">Kategori Aktif</div><div class="stat-value">{{ \App\Models\Category::where('status', true)->count() }}</div><div class="stat-note">Siap digunakan produk</div></div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="category-stat">
                    <div class="stat-icon stat-purple"><i class="bi bi-box-seam-fill"></i></div>
                    <div><div class="stat-label">Total Produk</div><div class="stat-value">{{ \App\Models\Produk::count() }}</div><div class="stat-note">Produk dalam semua kategori</div></div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="category-stat">
                    <div class="stat-icon stat-orange"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div><div class="stat-label">Kategori Nonaktif</div><div class="stat-value">{{ \App\Models\Category::where('status', false)->count() }}</div><div class="stat-note">Tidak tersedia untuk produk</div></div>
                </div>
            </div>
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="card category-card border-0">
            <div class="category-toolbar">
                <div class="category-search"><i class="bi bi-search"></i><input type="text" id="categorySearch" placeholder="Cari kategori..."></div>
                <select class="form-select category-filter" id="categoryStatus">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table category-table align-middle mb-0" id="categoryTable">
                    <thead>
                        <tr>
                            <th class="ps-4">NO</th>
                            <th>KATEGORI</th>
                            <th>WARNA</th>
                            <th>JUMLAH PRODUK</th>
                            <th>STATUS</th>
                            <th>DIBUAT OLEH</th>
                            <th>DIBUAT PADA</th>
                            <th class="text-end pe-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr data-status="{{ $category->status ? 'active' : 'inactive' }}">
                            <td class="ps-4 text-muted fw-semibold">{{ $categories->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="category-name-wrap">
                                    <div class="category-icon" style="--category-color: {{ $category->warna ?: '#22c55e' }}">
                                        <i class="bi {{ $category->icon ?: 'bi-tag-fill' }}"></i>
                                    </div>
                                    <div>
                                        <div class="category-name">{{ $category->nama }}</div>
                                        <div class="category-desc">{{ $category->deskripsi ?: 'Kategori produk POS HADI' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="color-pill" style="--category-color: {{ $category->warna ?: '#22c55e' }}"><span></span>{{ $category->warna ?: '#22c55e' }}</span></td>
                            <td><span class="product-pill"><i class="bi bi-box-seam"></i>{{ $category->produk_count }} Produk</span></td>
                            <td>{!! $category->status ? '<span class="status-pill active"><span></span>Aktif</span>' : '<span class="status-pill inactive"><span></span>Nonaktif</span>' !!}</td>
                            <td>
                                <div class="creator"><div class="creator-avatar">{{ strtoupper(substr($category->user?->name ?? 'Admin', 0, 1)) }}</div><div><div class="creator-name">{{ $category->user?->name ?? 'Admin POS HADI' }}</div><div class="creator-role">Admin</div></div></div>
                            </td>
                            <td><div class="date-cell"><i class="bi bi-calendar3"></i><div>{{ $category->created_at?->format('d M Y') }}<small>{{ $category->created_at?->format('H:i') }}</small></div></div></td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.categories.edit',$category) }}" class="action-btn edit" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('admin.categories.destroy',$category) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori {{ $category->nama }}?')">@csrf @method('DELETE')<button class="action-btn delete" title="Hapus"><i class="bi bi-trash-fill"></i></button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-5 text-muted"><i class="bi bi-tags fs-2 d-block mb-2"></i>Belum ada kategori.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="category-footer">
                <span>Menampilkan {{ $categories->firstItem() ?? 0 }} sampai {{ $categories->lastItem() ?? 0 }} dari {{ $categories->total() }} data</span>
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</main>

@push('styles')
<style>
.category-page{background:#f5f8fc}.category-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}.category-kicker{font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#16a34a;margin-bottom:6px}.category-head h3{font-size:25px;color:#0f2742}.category-add-btn{border-radius:10px;font-weight:700;padding:10px 16px;box-shadow:0 7px 18px rgba(22,163,74,.18)}
.category-stat{background:#fff;border:1px solid #e5ebf2;border-radius:15px;padding:18px;display:flex;gap:13px;align-items:center;min-height:104px;box-shadow:0 5px 16px rgba(15,23,42,.045)}.stat-icon{width:46px;height:46px;border-radius:14px;display:grid;place-items:center;font-size:19px;flex:0 0 auto}.stat-green{background:#dcfce7;color:#16a34a}.stat-blue{background:#dbeafe;color:#2563eb}.stat-purple{background:#ede9fe;color:#7c3aed}.stat-orange{background:#fef3c7;color:#d97706}.stat-label{font-size:11px;color:#64748b;font-weight:600}.stat-value{font-size:23px;font-weight:800;color:#12243a;line-height:1.15}.stat-note{font-size:9px;color:#94a3b8;margin-top:3px}
.category-card{border-radius:16px;overflow:hidden;box-shadow:0 8px 25px rgba(15,23,42,.06);border:1px solid #e5ebf2!important}.category-toolbar{display:flex;justify-content:space-between;gap:14px;padding:18px;border-bottom:1px solid #edf1f5;background:#fff}.category-search{width:370px;max-width:100%;height:42px;border:1px solid #dce5ef;background:#f8fafc;border-radius:22px;display:flex;align-items:center;padding:0 15px;gap:9px}.category-search i{color:#64748b}.category-search input{border:0;outline:0;background:transparent;width:100%;font-size:13px;color:#334155}.category-filter{width:180px;border-radius:11px;background:#f8fafc;border-color:#dce5ef;font-size:13px}
.category-table{font-size:12px}.category-table thead th{background:#f8fafc;color:#64748b;font-size:9px;font-weight:800;letter-spacing:.07em;padding:13px 10px;border-bottom:1px solid #e5ebf2;white-space:nowrap}.category-table tbody td{padding:13px 10px;border-color:#edf1f5}.category-table tbody tr{transition:.15s}.category-table tbody tr:hover{background:#fbfefc}.category-name-wrap{display:flex;align-items:center;gap:11px;min-width:190px}.category-icon{width:40px;height:40px;border-radius:11px;background:color-mix(in srgb,var(--category-color) 10%,white);color:var(--category-color);display:grid;place-items:center;font-size:16px;border:1px solid color-mix(in srgb,var(--category-color) 18%,white)}.category-name{font-weight:800;color:#132944;font-size:12px}.category-desc{font-size:9px;color:#94a3b8;margin-top:2px;max-width:170px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.color-pill,.product-pill,.status-pill{display:inline-flex;align-items:center;gap:6px;border-radius:8px;padding:5px 8px;font-size:10px;font-weight:700;white-space:nowrap}.color-pill{color:var(--category-color);background:color-mix(in srgb,var(--category-color) 8%,white);border:1px solid color-mix(in srgb,var(--category-color) 18%,white)}.color-pill span{width:8px;height:8px;border-radius:50%;background:var(--category-color)}.product-pill{color:#2563eb;background:#eff6ff;border:1px solid #dbeafe}.status-pill{border:1px solid}.status-pill span{width:7px;height:7px;border-radius:50%}.status-pill.active{color:#15803d;background:#ecfdf3;border-color:#bbf7d0}.status-pill.active span{background:#16a34a}.status-pill.inactive{color:#64748b;background:#f1f5f9;border-color:#e2e8f0}.status-pill.inactive span{background:#94a3b8}.creator{display:flex;align-items:center;gap:8px;min-width:145px}.creator-avatar{width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#15803d;display:grid;place-items:center;font-size:10px;font-weight:800}.creator-name{font-size:10px;font-weight:700;color:#334155;white-space:nowrap}.creator-role{font-size:8px;color:#94a3b8;margin-top:1px;text-transform:uppercase}.date-cell{display:flex;gap:7px;align-items:center;color:#475569;white-space:nowrap}.date-cell i{color:#64748b}.date-cell small{display:block;font-size:8px;color:#94a3b8;margin-top:2px}.action-btn{width:36px;height:36px;border-radius:8px;display:inline-grid;place-items:center;background:#fff;margin-left:4px;font-size:12px}.action-btn.edit{border:1px solid #f59e0b;color:#f59e0b}.action-btn.delete{border:1px solid #ef4444;color:#ef4444}.action-btn:hover{background:#f8fafc}.category-footer{padding:13px 18px;display:flex;justify-content:space-between;align-items:center;font-size:10px;color:#64748b;background:#fff}.category-footer .pagination{margin:0}.category-footer .page-link{font-size:11px;border-radius:7px;margin-left:3px}
@media(max-width:1100px){.category-table{min-width:1050px}.category-card{overflow:auto}.category-toolbar{position:sticky;left:0}.category-footer{min-width:1050px}}@media(max-width:700px){.category-head{align-items:flex-start;gap:12px;flex-direction:column}.category-add-btn{width:100%}.category-toolbar{flex-direction:column}.category-search,.category-filter{width:100%}}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const search=document.getElementById('categorySearch'), status=document.getElementById('categoryStatus');
    const rows=[...document.querySelectorAll('#categoryTable tbody tr[data-status]')];
    function filter(){const q=(search.value||'').toLowerCase().trim(), s=status.value; rows.forEach(r=>{const text=r.innerText.toLowerCase();r.style.display=(!q||text.includes(q))&&(s==='all'||r.dataset.status===s)?'':'none';});}
    search?.addEventListener('input',filter); status?.addEventListener('change',filter);
});
</script>
@endpush
@endsection
