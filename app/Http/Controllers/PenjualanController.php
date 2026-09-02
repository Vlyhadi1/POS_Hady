<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Menampilkan daftar transaksi penjualan.
     */
    public function index(SearchRequest $request)
    {
        $user = Auth::user();
        $keyword = $request->input('search');

        $sales = Penjualan::query()
            ->with(['user', 'itemPenjualan.produk']) // Eager loading
            // 🔒 Filter berdasarkan role: Kasir hanya melihat transaksinya sendiri
            ->when(strtolower(optional($user->role)->name ?? '') === 'kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            // 🔎 Search nama kasir/user
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', fn($u) => $u->where('name', 'like', '%' . $keyword . '%'))
                      ->orWhere('id', 'like', '%' . $keyword . '%');
                });
            })
            ->when($request->filled('metode'), function ($query) use ($request) {
                $method = strtoupper($request->metode);
                $query->where('metode_pembayaran', $method === 'TUNAI' ? 'CASH' : $method);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penjualan.index', compact('sales'));
    }

    /**
     * Menampilkan detail transaksi penjualan (Nota / Rincian).
     */
    public function show(Penjualan $penjualan)
    {
        // 🔒 Cek otorisasi kasir
        $user = Auth::user();
        if (strtolower(optional($user->role)->name ?? '') === 'kasir' && $penjualan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        // Load item dan produk terkait
        $penjualan->load(['itemPenjualan.produk', 'user']);

        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Menampilkan halaman kasir (POS) untuk transaksi baru / aktif.
     */
    public function create(SearchRequest $request)
    {
        // Cari atau buat transaksi baru berstatus OPEN untuk user yang sedang login
        $sale = Penjualan::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status'  => 'OPEN'
            ],
            [
                'total_pembayaran'  => 0,
                'metode_pembayaran' => 'CASH'
            ]
        );

        $keyword = $request->input('search');

        // Pencarian produk katalog
        $products = Produk::where('status', true)
            ->when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
        ->orderBy('nama')
        ->get();

        $mode = 'create';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
    }

    /**
     * Membuka halaman POS untuk mengedit transaksi OPEN yang ada.
     */
    public function edit(Penjualan $penjualan)
    {
        $this->authorize('update', $penjualan);
        $sale = $penjualan;

        // Transaksi yang sudah COMPLETED tidak boleh di-edit
        abort_if($sale->status === 'COMPLETED', 403, 'Transaksi yang sudah selesai tidak dapat diubah.');

        // Cek jika kasir lain mencoba mengedit transaksi milik kasir berbeda
        $user = Auth::user();
        if (strtolower(optional($user->role)->name ?? '') === 'kasir' && $sale->user_id !== $user->id) {
            abort(403, 'Anda tidak diizinkan mengedit transaksi pengguna lain.');
        }

        $sale->load('itemPenjualan.produk');
        $products = Produk::where('status', true)->orderBy('nama')->get();
        $mode = 'edit';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
    }

    /**
     * Menyelesaikan / Checkout transaksi.
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        $this->authorize('update', $penjualan);

        $request->validate([
            'payment_method' => ['required', 'in:CASH,QRIS,TRANSFER'],
            'amount_paid' => ['nullable', 'integer', 'min:0']
        ]);

        if ($penjualan->status !== 'OPEN') {
            return back()->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        if ($penjualan->itemPenjualan()->count() === 0) {
            return back()->with('error', 'Keranjang belanja masih kosong.');
        }

        try {
            DB::transaction(function () use ($penjualan, $request) {
                $penjualan = Penjualan::lockForUpdate()->findOrFail($penjualan->id);
                if ($penjualan->status !== 'OPEN') {
                    throw new \RuntimeException('Transaksi sudah diproses sebelumnya.');
                }
                $total = (int) $penjualan->itemPenjualan()->sum('subtotal');
            $method = $request->payment_method;
            $paid = $method === 'CASH' ? (int) ($request->amount_paid ?? 0) : $total;

            if ($method === 'CASH' && $paid < $total) {
                throw new \RuntimeException('Uang yang dibayar kurang dari total transaksi.');
            }

                $penjualan->update([
                    'metode_pembayaran' => $method,
                    'total_pembayaran' => $total,
                    'uang_dibayar' => $paid,
                    'kembalian' => max(0, $paid - $total),
                    'status' => 'COMPLETED',
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()
            ->route('penjualan.show', $penjualan)
            ->with('success', 'Transaksi berhasil diselesaikan.');
    }

    /**
     * Membatalkan transaksi OPEN dan mengembalikan stok barang.
     */
    public function destroy(Penjualan $penjualan)
    {
        $this->authorize('delete', $penjualan);

        if ($penjualan->status !== 'OPEN') {
            return back()->with('error', 'Hanya transaksi yang masih terbuka yang dapat dibatalkan.');
        }

        DB::transaction(function () use ($penjualan) {
            $items = $penjualan->itemPenjualan()->lockForUpdate()->get();

            foreach ($items as $item) {
                Produk::lockForUpdate()->find($item->produk_id)?->increment('stok', $item->kuantitas);
            }

            $penjualan->update([
                'status' => 'CANCELLED',
                'total_pembayaran' => $penjualan->itemPenjualan()->sum('subtotal'),
            ]);
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi dibatalkan dan stok telah dikembalikan.');
    }

}