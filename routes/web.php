<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ---- Autentikasi ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ---- Dashboard (butuh login) ----
Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // ---- Panel Admin (butuh login + role admin) ----
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.home');

        Route::get('/kabupaten', [App\Http\Controllers\Admin\KabupatenController::class, 'index'])->name('admin.kabupaten');
        Route::post('/kabupaten', [App\Http\Controllers\Admin\KabupatenController::class, 'store'])->name('admin.kabupaten.store');
        Route::put('/kabupaten/{kabupaten}', [App\Http\Controllers\Admin\KabupatenController::class, 'update'])->name('admin.kabupaten.update');
        Route::delete('/kabupaten/{kabupaten}', [App\Http\Controllers\Admin\KabupatenController::class, 'destroy'])->name('admin.kabupaten.destroy');

        Route::get('/jenis-pajak', [App\Http\Controllers\Admin\JenisPajakController::class, 'index'])->name('admin.jenis_pajak');
        Route::post('/jenis-pajak', [App\Http\Controllers\Admin\JenisPajakController::class, 'store'])->name('admin.jenis_pajak.store');
        Route::put('/jenis-pajak/{jenisPajak}', [App\Http\Controllers\Admin\JenisPajakController::class, 'update'])->name('admin.jenis_pajak.update');
        Route::delete('/jenis-pajak/{jenisPajak}', [App\Http\Controllers\Admin\JenisPajakController::class, 'destroy'])->name('admin.jenis_pajak.destroy');

        Route::get('/transaksi', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'index'])->name('admin.transaksi');
        Route::get('/transaksi/create', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'create'])->name('admin.transaksi.create');
        Route::post('/transaksi', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'store'])->name('admin.transaksi.store');
        Route::get('/transaksi/{transaksi}/edit', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'edit'])->name('admin.transaksi.edit');
        Route::put('/transaksi/{transaksi}', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'update'])->name('admin.transaksi.update');
        Route::delete('/transaksi/{transaksi}', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'destroy'])->name('admin.transaksi.destroy');

        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    });
});
