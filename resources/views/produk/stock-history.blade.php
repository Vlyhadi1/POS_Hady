@extends('layouts.app')

@section('title', 'Riwayat Stok - POS HADI')

@section('content')
@include('layouts.navbar')

<main class="pos-page">
    <div class="pos-container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Riwayat Perubahan Stok</h1>
                <p class="text-muted mb-0">Catatan penjualan, penyesuaian, dan pengembalian stok.</p>
            </div>
            <a href="{{ route('produk.index') }}" class="btn btn-outline-success rounded-pill">
                <i class="bi bi-box-seam me-1"></i>Daftar Produk
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th><th>Produk</th><th>Jenis</th><th>Catatan</th>
                            <th class="text-end">Sebelum</th><th class="text-end">Perubahan</th><th class="text-end">Sesudah</th><th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td class="small">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                <td class="fw-semibold">{{ $movement->produk->nama ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark">{{ $movement->jenis }}</span></td>
                                <td class="small text-muted">{{ $movement->catatan ?? '-' }}</td>
                                <td class="text-end">{{ $movement->stok_sebelum }}</td>
                                <td class="text-end {{ $movement->perubahan < 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                    {{ $movement->perubahan > 0 ? '+' : '' }}{{ $movement->perubahan }}
                                </td>
                                <td class="text-end fw-semibold">{{ $movement->stok_sesudah }}</td>
                                <td>{{ $movement->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-5">Belum ada riwayat perubahan stok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($movements->hasPages())
                <div class="card-footer bg-white border-0">{{ $movements->links() }}</div>
            @endif
        </div>
    </div>
</main>
@endsection
