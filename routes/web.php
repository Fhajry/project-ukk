<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'prosesLogin']);
});

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'prosesRegister']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');
Route::get('/buku', [BukuController::class, 'index'])
    ->middleware('auth');

Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])
    ->middleware(['auth', 'admin']);

Route::put('/buku/{id}', [BukuController::class, 'update'])
    ->middleware(['auth', 'admin']);

Route::delete('/buku/{id}', [BukuController::class, 'destroy'])
    ->middleware(['auth', 'admin']);

Route::get('/buku/create', [BukuController::class, 'create'])
    ->middleware(['auth', 'admin']);

Route::post('/buku', [BukuController::class, 'store'])
    ->middleware(['auth', 'admin']);

Route::get('/pinjam/{id}', [TransaksiController::class, 'pinjam'])
    ->middleware('auth');

Route::post('/kembali/{id}', [TransaksiController::class, 'kembali'])
    ->middleware('auth');

Route::get('/riwayat', [UserController::class, 'riwayat'])
    ->middleware('auth');

// Route::post('/hilang/{id}', [TransaksiController::class, 'hilang']);

Route::middleware('auth')->group(function () {

    Route::get('/admin/users', [TransaksiController::class, 'adminUsers']);
    Route::get('/admin/transaksi', [TransaksiController::class, 'adminTransaksi']);
    // Route::get('/admin/transaksi', [TransaksiController::class, 'adminIndex']);

    Route::post('/admin/transaksi/{id}/kembali', [TransaksiController::class, 'kembali']);
    Route::post('/admin/transaksi/{id}/hilang', [TransaksiController::class, 'hilang']);
    Route::post('/admin/transaksi/{id}/setujui', [TransaksiController::class, 'setujuiPeminjaman']);
    Route::post('/admin/transaksi/{id}/tolak', [TransaksiController::class, 'tolakPeminjaman']);

});

Route::get('/user/buku', [UserController::class, 'index'])->name('user.buku');

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/laporan', [LaporanController::class, 'index']);
    Route::get('/admin/laporan/user/{user}', [LaporanController::class, 'userPdf']);

});
