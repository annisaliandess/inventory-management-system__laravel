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

    // Resource CRUD Produk
    Route::resource('products', ProductController::class);

    // Transaksi Stok & Riwayat Mutasi (Masuk / Keluar)
    Route::get('transactions', [StockTransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [StockTransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [StockTransactionController::class, 'store'])->name('transactions.store');
});