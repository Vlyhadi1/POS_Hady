<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemPenjualanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/auth', [AuthController::class, 'auth'])->name('auth');
    Route::get('/lupa-kata-sandi', [AuthController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('/lupa-kata-sandi', [AuthController::class, 'forgotPasswordSubmit'])->name('password.forgot.submit');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPasswordSubmit'])->name('password.reset.submit');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil semua user yang sudah login
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/destroy/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting');
        Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    // Admin & Kasir
    Route::middleware('role:admin,kasir')->group(function () {
        Route::resource('produk', ProdukController::class);
        Route::resource('penjualan', PenjualanController::class);
        Route::post('/itempenjualan', [ItemPenjualanController::class, 'store'])->name('itempenjualan.store');
        Route::put('/itempenjualan/{itempenjualan}', [ItemPenjualanController::class, 'update'])->name('itempenjualan.update');
        Route::delete('/itempenjualan/{itempenjualan}', [ItemPenjualanController::class, 'destroy'])->name('itempenjualan.destroy');
    });
});
