<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use App\Livewire\PosTerminal;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// --- Auth (guest only) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Kasir (POS): owner & staff sama-sama boleh transaksi ---
    Route::middleware('role:owner,staff')->group(function () {
        Route::get('/pos', PosTerminal::class)->name('sales.create');
        Route::get('/penjualan', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/penjualan/{sale}', [SaleController::class, 'show'])->name('sales.show');

        Route::get('/stok', [StockController::class, 'index'])->name('stock.index');
        Route::post('/stok/penyesuaian', [StockController::class, 'adjust'])->name('stock.adjust');
    });

    // --- Master data & pengaturan: khusus owner ---
    Route::middleware('role:owner')->group(function () {
        Route::resource('produk', ProductController::class)->parameters(['produk' => 'product'])->names('products');
        Route::resource('kategori', CategoryController::class)->parameters(['kategori' => 'category'])->names('categories');
    });
});
