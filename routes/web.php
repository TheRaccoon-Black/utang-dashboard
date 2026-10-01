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

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/admin', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.home');

Route::get('/admin/kabupaten', [App\Http\Controllers\Admin\KabupatenController::class, 'index'])->name('admin.kabupaten');
Route::post('/admin/kabupaten', [App\Http\Controllers\Admin\KabupatenController::class, 'store'])->name('admin.kabupaten.store');
Route::put('/admin/kabupaten/{kabupaten}', [App\Http\Controllers\Admin\KabupatenController::class, 'update'])->name('admin.kabupaten.update');
Route::delete('/admin/kabupaten/{kabupaten}', [App\Http\Controllers\Admin\KabupatenController::class, 'destroy'])->name('admin.kabupaten.destroy');

Route::get('/admin/jenis-pajak', [App\Http\Controllers\Admin\JenisPajakController::class, 'index'])->name('admin.jenis_pajak');
Route::post('/admin/jenis-pajak', [App\Http\Controllers\Admin\JenisPajakController::class, 'store'])->name('admin.jenis_pajak.store');
Route::put('/admin/jenis-pajak/{jenisPajak}', [App\Http\Controllers\Admin\JenisPajakController::class, 'update'])->name('admin.jenis_pajak.update');
Route::delete('/admin/jenis-pajak/{jenisPajak}', [App\Http\Controllers\Admin\JenisPajakController::class, 'destroy'])->name('admin.jenis_pajak.destroy');

Route::get('/admin/transaksi', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'index'])->name('admin.transaksi');
Route::get('/admin/transaksi/create', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'create'])->name('admin.transaksi.create');
Route::post('/admin/transaksi', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'store'])->name('admin.transaksi.store');
Route::get('/admin/transaksi/{transaksi}/edit', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'edit'])->name('admin.transaksi.edit');
Route::put('/admin/transaksi/{transaksi}', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'update'])->name('admin.transaksi.update');
Route::delete('/admin/transaksi/{transaksi}', [App\Http\Controllers\Admin\TransaksiUtangController::class, 'destroy'])->name('admin.transaksi.destroy');
