<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Models\Kategori;

Route::get('/', [\App\Http\Controllers\PublicController::class, 'index'])->name('home');
Route::get('/track', [\App\Http\Controllers\PublicController::class, 'track'])->name('public.track');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // Rate limit: 5 attempts per minute
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Redirection
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    // Tambahkan halaman admin lainnya di sini
    Route::get('/kategori', [\App\Http\Controllers\kategoriController::class, 'index'])->name('admin.kategori');
    Route::get('/kategori/create', [\App\Http\Controllers\kategoriController::class, 'create'])->name('admin.kategori.create');
    Route::post('/kategori', [\App\Http\Controllers\kategoriController::class, 'store'])->name('admin.kategori.store');
    Route::get('/kategori/{id}/edit', [\App\Http\Controllers\kategoriController::class, 'edit'])->name('admin.kategori.edit');
    Route::put('/kategori/{id}', [\App\Http\Controllers\kategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/kategori/{id}', [\App\Http\Controllers\kategoriController::class, 'destroy'])->name('admin.kategori.destroy');

    // Layanan (Laundry Items)
    Route::get('/layanan', [\App\Http\Controllers\LayananController::class, 'index'])->name('admin.layanan');
    Route::get('/layanan/create', [\App\Http\Controllers\LayananController::class, 'create'])->name('admin.layanan.create');
    Route::post('/layanan', [\App\Http\Controllers\LayananController::class, 'store'])->name('admin.layanan.store');
    Route::get('/layanan/{id}/edit', [\App\Http\Controllers\LayananController::class, 'edit'])->name('admin.layanan.edit');
    Route::put('/layanan/{id}', [\App\Http\Controllers\LayananController::class, 'update'])->name('admin.layanan.update');
    Route::delete('/layanan/{id}', [\App\Http\Controllers\LayananController::class, 'destroy'])->name('admin.layanan.destroy');

    // Pelanggan (Customers)
    Route::get('/pelanggan', [\App\Http\Controllers\PelangganController::class, 'index'])->name('admin.pelanggan');
    Route::get('/pelanggan/create', [\App\Http\Controllers\PelangganController::class, 'create'])->name('admin.pelanggan.create');
    Route::post('/pelanggan', [\App\Http\Controllers\PelangganController::class, 'store'])->name('admin.pelanggan.store');
    Route::get('/pelanggan/{id}/edit', [\App\Http\Controllers\PelangganController::class, 'edit'])->name('admin.pelanggan.edit');
    Route::put('/pelanggan/{id}', [\App\Http\Controllers\PelangganController::class, 'update'])->name('admin.pelanggan.update');
    Route::delete('/pelanggan/{id}', [\App\Http\Controllers\PelangganController::class, 'destroy'])->name('admin.pelanggan.destroy');

    // User Management
    Route::get('/user', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.user');
    Route::get('/user/create', [\App\Http\Controllers\UserController::class, 'create'])->name('admin.user.create');
    Route::post('/user', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.user.store');
    Route::get('/user/{id}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/user/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/user/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.user.destroy');

    // General Settings
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');

    // Profile Settings
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('admin.profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('admin.profile.update');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/export-excel', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');
    Route::get('/reports/print', [\App\Http\Controllers\ReportController::class, 'print'])->name('admin.reports.print');

    // POS / Transaksi Baru
    Route::get('/transaksi', [\App\Http\Controllers\TransaksiController::class, 'index'])->name('admin.transaksi.index');
    Route::get('/transaksi/baru', [\App\Http\Controllers\TransaksiController::class, 'create'])->name('admin.transaksi.baru');
    Route::post('/transaksi/baru', [\App\Http\Controllers\TransaksiController::class, 'store'])->name('admin.transaksi.store');
    Route::get('/transaksi/{id}/struk', [\App\Http\Controllers\TransaksiController::class, 'struk'])->name('admin.transaksi.struk');
    Route::get('/transaksi/search-code', [\App\Http\Controllers\TransaksiController::class, 'searchByCode'])->name('admin.transaksi.search-code');
    Route::patch('/transaksi/{id}/status', [\App\Http\Controllers\TransaksiController::class, 'updateStatus'])->name('admin.transaksi.update-status');
    Route::get('/transaksi/{id}/label', [\App\Http\Controllers\TransaksiController::class, 'label'])->name('admin.transaksi.label');
    Route::get('/transaksi/{id}/json', [\App\Http\Controllers\TransaksiController::class, 'getJson'])->name('admin.transaksi.json');

    Route::resource('pengeluaran', \App\Http\Controllers\Admin\PengeluaranController::class)->only(['index', 'store'])->names([
        'index' => 'admin.pengeluaran.index',
        'store' => 'admin.pengeluaran.store',
    ]);
});

// Kasir Routes
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kasir'])->name('kasir.dashboard');
    
    // Shared POS for Kasir
    Route::get('/transaksi', [\App\Http\Controllers\TransaksiController::class, 'index'])->name('kasir.transaksi.index');
    Route::get('/transaksi/baru', [\App\Http\Controllers\TransaksiController::class, 'create'])->name('kasir.transaksi.baru');
    Route::post('/transaksi/baru', [\App\Http\Controllers\TransaksiController::class, 'store'])->name('kasir.transaksi.store');
    Route::get('/transaksi/{id}/struk', [\App\Http\Controllers\TransaksiController::class, 'struk'])->name('kasir.transaksi.struk');
    Route::get('/transaksi/search-code', [\App\Http\Controllers\TransaksiController::class, 'searchByCode'])->name('kasir.transaksi.search-code');
    Route::patch('/transaksi/{id}/status', [\App\Http\Controllers\TransaksiController::class, 'updateStatus'])->name('kasir.transaksi.update-status');
    Route::get('/transaksi/{id}/label', [\App\Http\Controllers\TransaksiController::class, 'label'])->name('kasir.transaksi.label');
    Route::get('/transaksi/{id}/json', [\App\Http\Controllers\TransaksiController::class, 'getJson'])->name('kasir.transaksi.json');

    Route::resource('pengeluaran', \App\Http\Controllers\Admin\PengeluaranController::class)->only(['index', 'store'])->names([
        'index' => 'kasir.pengeluaran.index',
        'store' => 'kasir.pengeluaran.store',
    ]);

    // Pelanggan for Kasir
    Route::get('/pelanggan', [\App\Http\Controllers\PelangganController::class, 'index'])->name('kasir.pelanggan');
    Route::get('/pelanggan/create', [\App\Http\Controllers\PelangganController::class, 'create'])->name('kasir.pelanggan.create');
    Route::post('/pelanggan', [\App\Http\Controllers\PelangganController::class, 'store'])->name('kasir.pelanggan.store');
    Route::get('/pelanggan/{id}/edit', [\App\Http\Controllers\PelangganController::class, 'edit'])->name('kasir.pelanggan.edit');
    Route::put('/pelanggan/{id}', [\App\Http\Controllers\PelangganController::class, 'update'])->name('kasir.pelanggan.update');
    Route::delete('/pelanggan/{id}', [\App\Http\Controllers\PelangganController::class, 'destroy'])->name('kasir.pelanggan.destroy');

    // Layanan Index for Kasir (View Only)
    Route::get('/layanan', [\App\Http\Controllers\LayananController::class, 'index'])->name('kasir.layanan');

    // Profile Settings for Kasir
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('kasir.profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('kasir.profile.update');
});

// Owner Routes
Route::middleware(['auth', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
    
    // Reports for Owner
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('owner.reports');
    Route::get('/reports/export-excel', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('owner.reports.export-excel');
    Route::get('/reports/print', [\App\Http\Controllers\ReportController::class, 'print'])->name('owner.reports.print');

    // Expenses for Owner
    Route::get('/pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'index'])->name('owner.pengeluaran.index');
    Route::post('/pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'store'])->name('owner.pengeluaran.store');

    // Settings for Owner
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('owner.settings');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('owner.settings.update');

    // Profile for Owner
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('owner.profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('owner.profile.update');
});
// Production Staff Routes
Route::middleware(['auth', 'role:produksi'])->prefix('produksi')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'produksi'])->name('produksi.dashboard');
    
    // Limited access to Monitor Transaksi for production staff
    Route::get('/transaksi', [\App\Http\Controllers\TransaksiController::class, 'index'])->name('produksi.transaksi.index');
    Route::get('/transaksi/search-code', [\App\Http\Controllers\TransaksiController::class, 'searchByCode'])->name('produksi.transaksi.search-code');
    Route::patch('/transaksi/{id}/status', [\App\Http\Controllers\TransaksiController::class, 'updateStatus'])->name('produksi.transaksi.update-status');
    Route::get('/transaksi/{id}/label', [\App\Http\Controllers\TransaksiController::class, 'label'])->name('produksi.transaksi.label');
    Route::get('/transaksi/{id}/json', [\App\Http\Controllers\TransaksiController::class, 'getJson'])->name('produksi.transaksi.json');

    // Profile Settings for Production Staff
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('produksi.profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('produksi.profile.update');
});
