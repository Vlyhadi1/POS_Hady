<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $user = auth()->user();
        $isAdmin = strtolower(optional($user->role)->name ?? '') === 'admin';

        $todaySales = Penjualan::query()
            ->whereDate('created_at', $today)
            ->where('status', 'COMPLETED')
            ->when(!$isAdmin, fn ($q) => $q->where('user_id', $user->id));

        $ringkasan = [
            'total_transaksi' => (clone $todaySales)->count(),
            'total_penjualan' => (clone $todaySales)->sum('total_pembayaran'),
            'total_cash' => (clone $todaySales)->where('metode_pembayaran', 'CASH')->sum('total_pembayaran'),
            'total_non_tunai' => (clone $todaySales)->whereIn('metode_pembayaran', ['QRIS', 'TRANSFER'])->sum('total_pembayaran'),
        ];

        $totalProfit = DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->where('penjualan.status', 'COMPLETED')
            ->whereDate('penjualan.created_at', $today)
            ->when(!$isAdmin, fn ($q) => $q->where('penjualan.user_id', $user->id))
            ->selectRaw('COALESCE(SUM(item_penjualan.kuantitas * (item_penjualan.harga_satuan - produk.harga_beli)), 0) AS profit')
            ->value('profit');

        $produkTerlaris = DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->where('penjualan.status', 'COMPLETED')
            ->whereDate('penjualan.created_at', $today)
            ->when(!$isAdmin, fn ($q) => $q->where('penjualan.user_id', $user->id))
            ->groupBy('produk.id', 'produk.nama', 'produk.stok')
            ->select('produk.nama', 'produk.stok', DB::raw('SUM(item_penjualan.kuantitas) AS total_terjual'))
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $lowStockLimit = Setting::current()->low_stock_limit;

        $produkStokRendahCount = Produk::where('stok', '>', 0)
            ->where(function ($q) use ($lowStockLimit) {
                $q->whereColumn('stok', '<=', 'minimum_stok')
                  ->orWhere('stok', '<=', $lowStockLimit);
            })
            ->orderBy('stok')
            ->limit(5)
            ->get();

        $produkStokRendah = $produkStokRendahCount;
        $produkStokRendahTotal = Produk::where('stok', '>', 0)
            ->where(function ($q) use ($lowStockLimit) {
                $q->whereColumn('stok', '<=', 'minimum_stok')->orWhere('stok', '<=', $lowStockLimit);
            })->count();
        $produkStokHabisTotal = Produk::where('stok', 0)->count();

        $produkStokHabis = Produk::where('stok', 0)
            ->orderBy('nama')
            ->limit(5)
            ->get();

        $totalProduk = Produk::count();
        $totalUsers = User::count();
        $setting = Setting::current();

        $recentSales = Penjualan::with('user')
            ->where('status', 'COMPLETED')
            ->when(!$isAdmin, fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->limit(5)
            ->get();

        $year = $today->year;
        $monthly = Penjualan::query()
            ->where('status', 'COMPLETED')
            ->whereYear('created_at', $year)
            ->when(!$isAdmin, fn ($q) => $q->where('user_id', $user->id))
            ->selectRaw('MONTH(created_at) AS bulan, SUM(total_pembayaran) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        $chartLabels = [];
        $chartValues = [];
        for ($month = 1; $month <= 12; $month++) {
            $chartLabels[] = Carbon::create($year, $month, 1)->translatedFormat('M');
            $chartValues[] = (int) ($monthly[$month] ?? 0);
        }

        $categoryRows = DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->leftJoin('categories', 'categories.id', '=', 'produk.category_id')
            ->where('penjualan.status', 'COMPLETED')
            ->whereYear('penjualan.created_at', $year)
            ->when(!$isAdmin, fn ($q) => $q->where('penjualan.user_id', $user->id))
            ->groupBy('categories.id', 'categories.nama')
            ->selectRaw("COALESCE(categories.nama, 'Tanpa Kategori') AS nama, SUM(item_penjualan.subtotal) AS total")
            ->orderByDesc('total')
            ->get();

        return view('dashboard', compact(
            'ringkasan',
            'totalProfit',
            'isAdmin',
            'produkTerlaris',
            'produkStokRendah',
            'produkStokHabis',
            'produkStokRendahTotal',
            'produkStokHabisTotal',
            'totalProduk',
            'totalUsers',
            'recentSales',
            'chartLabels',
            'chartValues',
            'categoryRows',
            'setting'
        ));
    }
}
