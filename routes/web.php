<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;

// Rute Autentikasi (Tamu / Guest)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Logout (Hanya User yang Login)
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute yang Memerlukan Autentikasi (Auth Protected)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('products.index');
    });

    // Manajemen Produk (Hanya Admin: Tambah, Ubah, Hapus)
    Route::middleware('role:admin')->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Akses Produk: Staff & Admin bisa melihat katalog produk (index & show)
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Transaksi Stok & Riwayat Mutasi (Masuk / Keluar)
    Route::get('transactions', [StockTransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [StockTransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [StockTransactionController::class, 'store'])->name('transactions.store');
});