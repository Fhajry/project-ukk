<?php

use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriBukuController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PenerbitController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PenulisController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Route untuk "mengakali" symlink yang error di Linux
// Route::get('/storage/buku/{filename}', function ($filename) {
//     $path = 'public/buku/' . $filename;

//     // Cek apakah file benar-benar ada di folder storage/app/public/buku
//     if (!Storage::exists($path)) {
//         abort(404);
//     }

//     // Ambil file dan tipe mimenya (jpg/png)
//     $file = Storage::get($path);
//     $type = Storage::mimeType($path);

//     // Tampilkan gambar langsung ke browser
//     return Response::make($file, 200)->header("Content-Type", $type);
// });
/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('home.dashboard');
});
Route::get('/home', [HomeController::class, 'dashboard'])->name('home.dashboard');

Route::get('/home/buku', [HomeController::class, 'buku'])->name('home.buku');
Route::get('/home/detail/{id}', [HomeController::class, 'show'])->name('home.detail');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'prosesLogin']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'prosesRegister']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- Akses Member (User) ---
    Route::get('/home/riwayat', [HomeController::class, 'riwayat'])->name('home.riwayat');
    Route::post('/pinjam/{id}', [TransaksiController::class, 'pinjam'])->name('transaksi.pinjam');
    Route::post('/kembali/{id}', [TransaksiController::class, 'kembali'])->name('transaksi.kembali');

    // Pengaturan Profil
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan/update', [PengaturanController::class, 'update'])->name('pengaturan.update');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Hanya Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminindex'])->name('admin.dashboard');

        // Resource CRUD
        Route::resource('users', UserController::class);
        Route::resource('kategori', KategoriBukuController::class);
        Route::resource('bukus', BukuController::class);
        Route::resource('penulis', PenulisController::class);
        Route::resource('penerbit', PenerbitController::class);

        // Manajemen Transaksi Admin
        Route::prefix('admin/transaksi')->group(function () {
            Route::get('/', [TransaksiController::class, 'adminTransaksi'])->name('admin.transaksi.index');
            Route::post('/{id}/kembali', [TransaksiController::class, 'kembali']);
            Route::post('/{id}/hilang', [TransaksiController::class, 'hilang']);
            Route::post('/{id}/setujui', [TransaksiController::class, 'setujuiPeminjaman']);
            Route::post('/{id}/tolak', [TransaksiController::class, 'tolakPeminjaman']);
        });

        // Laporan
        Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/admin/laporan/user/{user}', [LaporanController::class, 'userPdf'])->name('laporan.userPdf');
        Route::get('/laporan/excel', [TransaksiController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/admin/transaksi/export', [TransaksiController::class, 'exportExcel'])->name('admin.transaksi.export');
    });
});
