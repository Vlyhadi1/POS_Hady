@extends('layouts.app')

@section('title', 'Kelola Users - POS HADI')

@section('content')
@include('layouts.navbar')

<style>
    .users-page { padding-bottom: 30px; }
    .users-hero {
        background: linear-gradient(135deg,#071a2e,#064e3b 58%,#16a34a);
        border-radius: 22px; color:#fff; box-shadow:0 18px 45px rgba(6,78,59,.18);
    }
    .stat-card { border:1px solid #e8eef2; border-radius:17px; transition:.2s; }
    .stat-card:hover { transform:translateY(-2px); box-shadow:0 10px 25px rgba(15,23,42,.07)!important; }
    .stat-icon { width:46px;height:46px;border-radius:13px;display:grid;place-items:center;font-size:20px;flex:0 0 auto; }
    .users-card { border:1px solid #e8eef2!important; border-radius:18px!important; }
    .filter-control { height:42px;border:1px solid #e1e8ed;border-radius:11px;background:#f8fafc; }
    .filter-control:focus { border-color:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.1);background:#fff; }
    .table thead th { background:#f8fafc;color:#64748b;font-size:11px;letter-spacing:.04em;font-weight:700;white-space:nowrap;border-bottom:1px solid #e9eef2; }
    .table tbody td { border-color:#eef2f5;padding-top:13px;padding-bottom:13px; }
    .table tbody tr:hover { background:#f8fffb; }
    .avatar-user { width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid #fff;box-shadow:0 2px 8px rgba(15,23,42,.12); }
    .avatar-fallback { width:42px;height:42px;border-radius:50%;display:grid;place-items:center;background:#dcfce7;color:#15803d;font-weight:800; }
    .role-badge { display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:6px 10px;font-size:11px;font-weight:700; }
    .role-admin { background:#dbeafe;color:#1d4ed8; }
    .role-kasir { background:#dcfce7;color:#15803d; }
    .role-other { background:#f1f5f9;color:#475569; }
    .btn-action { width:34px;height:34px;border:0;border-radius:10px;display:inline-grid;place-items:center;transition:.18s; }
    .btn-edit { background:#ecfccb;color:#65a30d; }
    .btn-edit:hover { background:#65a30d;color:#fff;transform:translateY(-1px); }
    .btn-delete { background:#fee2e2;color:#dc2626; }
    .btn-delete:hover { background:#dc2626;color:#fff;transform:translateY(-1px); }
    .empty-state { padding:55px 20px; }
    .pagination-wrap nav svg { width:16px; }
    @media(max-width:768px){ .users-hero{border-radius:17px}.users-hero .hero-actions{width:100%}.users-hero .hero-actions a,.users-hero .hero-actions button{flex:1}.filter-control{width:100%!important}.table{min-width:760px} }
    @media print { .pos-sidebar,.pos-mobile-toggle,.pos-sidebar-backdrop,.no-print,.users-hero .hero-actions{display:none!important}.pos-page{margin:0!important;padding:0!important}.users-hero{box-shadow:none}.users-card{box-shadow:none!important} }
</style>

<main class="pos-page users-page">
<div class="pos-container">
    <div class="users-hero p-4 p-md-5 mb-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <div class="small text-white-50 mb-2">MANAJEMEN SISTEM</div>
                <h2 class="fw-bold mb-1"><i class="bi bi-people-fill me-2"></i>Kelola Users</h2>
                <p class="mb-0 text-white-50 small">Kelola akun, role, dan akses pengguna POS HADI.</p>
            </div>
            <div class="d-flex gap-2 hero-actions no-print">
                <button onclick="window.print()" class="btn btn-outline-light rounded-pill px-3 fw-semibold"><i class="bi bi-printer me-1"></i>Cetak</button>
                <a href="{{ route('admin.users.create') }}" class="btn btn-light rounded-pill px-4 fw-bold text-success"><i class="bi bi-person-plus-fill me-1"></i>Tambah User</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-4"><div class="card stat-card bg-white shadow-sm h-100"><div class="card-body p-3 d-flex align-items-center gap-3"><div class="stat-icon" style="background:#dcfce7;color:#15803d"><i class="bi bi-people-fill"></i></div><div><div class="small text-muted">Total User</div><div class="fs-5 fw-bold">{{ $totalUsers }}</div></div></div></div></div>
        <div class="col-6 col-lg-4"><div class="card stat-card bg-white shadow-sm h-100"><div class="card-body p-3 d-flex align-items-center gap-3"><div class="stat-icon" style="background:#dbeafe;color:#1d4ed8"><i class="bi bi-shield-lock-fill"></i></div><div><div class="small text-muted">Administrator</div><div class="fs-5 fw-bold">{{ $totalAdmins }}</div></div></div></div></div>
        <div class="col-6 col-lg-4"><div class="card stat-card bg-white shadow-sm h-100"><div class="card-body p-3 d-flex align-items-center gap-3"><div class="stat-icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-person-badge-fill"></i></div><div><div class="small text-muted">Kasir</div><div class="fs-5 fw-bold">{{ $totalKasir }}</div></div></div></div></div>
    </div>

    <div class="card users-card shadow-sm overflow-hidden">
        <div class="card-header bg-white border-0 p-3 p-md-4 no-print">
            <form action="{{ route('admin.users') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-6 col-lg-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-color:#e1e8ed"><i class="bi bi-search text-success"></i></span>
                            <input class="form-control filter-control border-start-0" style="border-radius:0 11px 11px 0" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email...">
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <select name="role" class="form-select filter-control" onchange="this.form.submit()">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ strtolower($role->name) }}" @selected(request('role') === strtolower($role->name))>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3 col-lg-2 d-flex gap-2">
                        <button class="btn btn-success flex-fill" type="submit"><i class="bi bi-search me-1"></i>Cari</button>
                        @if(request('search') || request('role'))
                            <a href="{{ route('admin.users') }}" class="btn btn-light border" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th class="ps-4">NO</th><th>PENGGUNA</th><th>EMAIL</th><th>ROLE</th><th>TERDAFTAR</th><th class="text-end pe-4 no-print">AKSI</th></tr></thead>
                <tbody>
                @forelse($users as $user)
                    @php $roleName = strtolower(optional($user->role)->name ?? 'user'); @endphp
                    <tr>
                        <td class="ps-4 text-muted small">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($user->photo)
                                    <img src="{{ asset('storage/'.$user->photo) }}" alt="{{ $user->name }}" class="avatar-user">
                                @else
                                    <div class="avatar-fallback">{{ strtoupper(substr($user->name,0,1)) }}</div>
                                @endif
                                <div class="min-w-0"><div class="fw-bold text-dark text-truncate" style="max-width:190px">{{ $user->name }}</div><small class="text-muted">ID #{{ $user->id }}</small></div>
                            </div>
                        </td>
                        <td><span class="small text-secondary">{{ $user->email }}</span></td>
                        <td>
                            <span class="role-badge {{ $roleName === 'admin' ? 'role-admin' : ($roleName === 'kasir' ? 'role-kasir' : 'role-other') }}">
                                <i class="bi {{ $roleName === 'admin' ? 'bi-shield-lock-fill' : 'bi-person-badge-fill' }}"></i>{{ ucfirst($roleName) }}
                            </span>
                        </td>
                        <td><span class="small text-muted">{{ optional($user->created_at)->format('d M Y') ?? '-' }}</span></td>
                        <td class="text-end pe-4 no-print">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn-action btn-delete" title="Hapus" onclick="confirmDelete({{ $user->id }}, @js($user->name))"><i class="bi bi-trash-fill"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state text-center"><i class="bi bi-person-x fs-1 text-secondary d-block mb-3"></i><h6 class="fw-bold mb-1">User tidak ditemukan</h6><p class="text-muted small mb-0">Coba ubah kata pencarian atau filter role.</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="card-footer bg-white border-0 p-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                <small class="text-muted">Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user</small>
                <div>{{ $users->onEachSide(1)->links() }}</div>
            </div>
        @endif
    </div>
</div>
</main>

<form id="deleteUserForm" method="POST" class="d-none">
    @csrf @method('DELETE')
</form>
<script>
function confirmDelete(id, name) {
    if (!confirm('Hapus user "' + name + '"? Data akun akan dihapus dari sistem.')) return;
    const form = document.getElementById('deleteUserForm');
    form.action = '{{ url('/admin/users/destroy') }}/' + id;
    form.submit();
}
</script>
@endsection
