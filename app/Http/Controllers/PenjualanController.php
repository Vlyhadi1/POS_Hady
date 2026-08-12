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
            ->when($user->role && $user->role->name === 'kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            // 🔎 Search nama kasir/user
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
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
        if ($user->role && $user->role->name === 'kasir' && $penjualan->user_id !== $user->id) {
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
        $products = Produk::when($keyword, function ($query) use ($keyword) {
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
        $sale = $penjualan;

        // Transaksi yang sudah COMPLETED tidak boleh di-edit
        abort_if($sale->status === 'COMPLETED', 403, 'Transaksi yang sudah selesai tidak dapat diubah.');

        // Cek jika kasir lain mencoba mengedit transaksi milik kasir berbeda
        $user = Auth::user();
        if ($user->role && $user->role->name === 'kasir' && $sale->user_id !== $user->id) {
            abort(403, 'Anda tidak diizinkan mengedit transaksi pengguna lain.');
        }

        $sale->load('itemPenjualan.produk');
        $products = Produk::orderBy('nama')->get();
        $mode = 'edit';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
    }

    /**
     * Menyelesaikan / Checkout transaksi.
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS,TRANSFER'
        ]);

        if ($penjualan->status !== 'OPEN') {
            return back()->with('errors', 'Transaksi sudah diproses sebelumnya.');
        }

        if ($penjualan->itemPenjualan()->count() === 0) {
            return back()->with('errors', 'Keranjang belanja masih kosong.');
        }

        DB::transaction(function () use ($penjualan, $request) {
            // 🔄 Hitung ulang total pembayaran di backend demi keamanan
            $total = $penjualan->itemPenjualan()->sum('subtotal');

            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran'  => $total,
                'status'            => 'COMPLETED',
            ]);
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil diselesaikan.');
    }

    /**
     * Membatalkan transaksi OPEN dan mengembalikan stok barang.
     */
    public function destroy(Penjualan $penjualan)
    {
        $this->authorize('delete', $penjualan);

        // ❗ Pastikan hanya transaksi berstatus OPEN
        if ($penjualan->status !== 'OPEN') {
            return redirect()->route('penjualan.create')
                ->with('errors', 'Transaksi yang sudah selesai tidak dapat dibatalkan.');
        }

        // ❗ Pastikan milik kasir yang sedang login (kecuali Admin)
        $user = Auth::user();
        if ($user->role && $user->role->name === 'kasir' && $penjualan->user_id != $user->id) {
            return redirect()->route('penjualan.create');
        }

        DB::transaction(function () use ($penjualan) {
            // 🔼 Kembalikan kuantitas stok ke produk
            foreach ($penjualan->itemPenjualan as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->kuantitas);
                }
            }

            // ❌ Hapus rincian item & data transaksi
            $penjualan->itemPenjualan()->delete();
            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dibatalkan.');
    }
}