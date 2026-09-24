<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    public function pdf(Penjualan $penjualan)
    {
        $this->authorize('view', $penjualan);
        $penjualan->load(['itemPenjualan.produk', 'user']);
        $setting = \App\Models\Setting::current();
        $logoPath = $setting->store_logo ? storage_path('app/public/' . $setting->store_logo) : null;
        $logoDataUri = is_file($logoPath ?? '')
            ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        return Pdf::loadView('penjualan.pdf', compact('penjualan', 'setting', 'logoDataUri'))
            ->setPaper('a5', 'portrait')
            ->download('nota-transaksi-' . $penjualan->id . '.pdf');
    }
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
     * Membuat transaksi baru secara eksplisit (kompatibilitas resource route).
     */
    public function store(Request $request)
    {
        $sale = Penjualan::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status' => 'OPEN',
            ],
            [
                'total_pembayaran' => 0,
                'metode_pembayaran' => 'CASH',
            ]
        );

        return redirect()
            ->route('penjualan.edit', $sale)
            ->with('success', 'Transaksi baru siap digunakan.');
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
            'amount_paid' => ['nullable', 'integer', 'min:0'],
            'diskon' => ['nullable', 'integer', 'min:0'],
            'diskon_value' => ['nullable', 'integer', 'min:0'],
            'diskon_type' => ['nullable', 'in:nominal,persen'],
        ]);

        $isAdmin = strtolower(optional(Auth::user()->role)->name ?? '') === 'admin';
        $discountValue = $request->has('diskon_value') ? $request->integer('diskon_value') : $request->integer('diskon');
        if (!$isAdmin && $discountValue > 0) {
            return back()->with('error', 'Diskon hanya dapat digunakan oleh Admin.')->withInput();
        }

        if ($penjualan->status !== 'OPEN') {
            return back()->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        if ($penjualan->itemPenjualan()->count() === 0) {
            return back()->with('error', 'Keranjang belanja masih kosong.');
        }

        try {
            DB::transaction(function () use ($penjualan, $request, $isAdmin) {
                $penjualan = Penjualan::lockForUpdate()->findOrFail($penjualan->id);
                if ($penjualan->status !== 'OPEN') {
                    throw new \RuntimeException('Transaksi sudah diproses sebelumnya.');
                }
                $subtotal = (int) $penjualan->itemPenjualan()->sum('subtotal');
                $discountType = $request->input('diskon_type', 'nominal');
                $discountValue = $isAdmin ? ($request->has('diskon_value') ? (int) $request->diskon_value : (int) $request->diskon) : 0;
                $diskonPersen = $discountType === 'persen' ? $discountValue : 0;
                if ($diskonPersen > 100) {
                    throw new \RuntimeException('Diskon persentase maksimal 100%.');
                }
                $diskon = $discountType === 'persen' ? (int) floor($subtotal * $diskonPersen / 100) : $discountValue;
                if ($diskon > $subtotal) {
                    throw new \RuntimeException('Diskon tidak boleh lebih besar dari subtotal transaksi.');
                }
                $total = $subtotal - $diskon;
                $method = $request->payment_method;
                $paid = $method === 'CASH' ? (int) ($request->amount_paid ?? 0) : $total;

                if ($method === 'CASH' && $paid < $total) {
                    throw new \RuntimeException('Uang yang dibayar kurang dari total transaksi.');
                }

                $penjualan->update([
                    'diskon' => $diskon,
                    'diskon_persen' => $diskonPersen,
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

        $wasCancelled = DB::transaction(function () use ($penjualan) {
            $penjualan = Penjualan::lockForUpdate()->findOrFail($penjualan->id);

            if ($penjualan->status !== 'OPEN') {
                return false;
            }

            $items = $penjualan->itemPenjualan()->lockForUpdate()->get();

            foreach ($items as $item) {
                $product = Produk::lockForUpdate()->find($item->produk_id);
                if ($product) {
                    $stokSebelum = $product->stok;
                    $product->increment('stok', $item->kuantitas);
                    StockMovement::create([
                        'produk_id' => $product->id, 'user_id' => Auth::id(),
                        'stok_sebelum' => $stokSebelum, 'perubahan' => $item->kuantitas,
                        'stok_sesudah' => $stokSebelum + $item->kuantitas, 'jenis' => 'PEMBATALAN',
                        'catatan' => 'Transaksi dibatalkan',
                    ]);
                }
            }

            $penjualan->update([
                'status' => 'CANCELLED',
                'total_pembayaran' => $penjualan->itemPenjualan()->sum('subtotal'),
            ]);

            return true;
        });

        if (!$wasCancelled) {
            return back()->with('error', 'Hanya transaksi yang masih terbuka yang dapat dibatalkan.');
        }

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi dibatalkan dan stok telah dikembalikan.');
    }

}