<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\User;

class TokoController extends Controller
{
    public function index()
    {
        $setting = Setting::current();
        $user = auth()->user();
        $isAdmin = strtolower(optional($user->role)->name ?? '') === 'admin';

        $stats = [
            'total_produk' => Produk::count(),
            'total_kategori' => Category::count(),
            'total_transaksi' => Penjualan::where('status', 'COMPLETED')->count(),
            'total_pendapatan' => Penjualan::where('status', 'COMPLETED')->sum('total_pembayaran'),
            'total_users' => User::count(),
        ];

        $sejakTanggal = Penjualan::oldest('created_at')->value('created_at') ?? $setting->created_at;

        return view('toko.index', [
            'setting' => $setting,
            'isAdmin' => $isAdmin,
            'stats' => $stats,
            'sejakTanggal' => $sejakTanggal,
        ]);
    }
}
