<?php

namespace App\Http\Controllers;

use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use App\Models\Category;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Produk::class);

        $keyword = trim((string) $request->input('search', ''));
        $categoryId = $request->input('category_id');
        $stokStatus = $request->input('stok_status');
        $status = $request->input('status');

        $query = Produk::with(['category', 'user'])
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('satuan', 'like', "%{$keyword}%")
                        ->orWhereHas('category', fn ($category) => $category->where('nama', 'like', "%{$keyword}%"));
                });
            })
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($status !== null && $status !== '', fn ($query) => $query->where('status', (bool) $status))
            ->when($stokStatus === 'habis', fn ($query) => $query->where('stok', 0))
            ->when($stokStatus === 'kritis', fn ($query) => $query->whereColumn('stok', '<=', 'minimum_stok')->where('stok', '>', 0))
            ->when($stokStatus === 'ready', fn ($query) => $query->where(function ($q) {
                $q->where('stok', '>', 0)->whereColumn('stok', '>', 'minimum_stok');
            }))
            ->orderBy('nama');

        $products = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => Produk::count(),
            'stok' => (int) Produk::sum('stok'),
            'kritis' => Produk::where('stok', '>', 0)->whereColumn('stok', '<=', 'minimum_stok')->count(),
            'habis' => Produk::where('stok', 0)->count(),
            'aktif' => Produk::where('status', true)->count(),
        ];

        $categories = Category::orderBy('nama')->get(['id', 'nama', 'status']);

        return view('produk.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Produk::class);

        $categories = Category::where('status', true)->orderBy('nama')->get();

        return view('produk.create', compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('create', Produk::class);

        $data = $this->productData($request);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        $this->authorize('view', $produk);
        $produk->load(['category', 'user']);

        return view('produk.detail', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        $this->authorize('update', $produk);

        $categories = Category::where('status', true)
            ->orWhere('id', $produk->category_id)
            ->orderBy('nama')
            ->get();

        return view('produk.edit', compact('produk', 'categories'));
    }

    public function update(UpdateRequest $request, Produk $produk)
    {
        $this->authorize('update', $produk);

        $data = $this->productData($request);

        // Jangan mengubah user_id saat admin mengedit produk.
        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $this->authorize('delete', $produk);

        // Produk yang sudah pernah masuk transaksi tidak dihapus agar histori penjualan tetap aman.
        if ($produk->itemPenjualan()->exists()) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi. Nonaktifkan produk jika sudah tidak dijual.');
        }

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function productData(Request $request): array
    {
        return [
            'category_id' => $request->integer('category_id'),
            'nama' => trim((string) $request->input('name')),
            'harga_beli' => $request->integer('purchase_price'),
            'harga_jual' => $request->integer('selling_price'),
            'stok' => $request->integer('stock'),
            'satuan' => $request->input('satuan'),
            'minimum_stok' => $request->integer('minimum_stok', 0),
            'deskripsi' => $request->input('deskripsi'),
            'status' => $request->boolean('status'),
        ];
    }
}
