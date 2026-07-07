<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ApprovalController;

//Rute awal web di-redirect ke dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

//Halaman Dashboard sekarang publik (tidak wajib login)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/peminjaman/detail/{id}', [PeminjamanController::class, 'showWeb'])->name('peminjaman.show');
Route::get('/cek-status', [PeminjamanController::class, 'cekStatus'])->name('peminjaman.cek_status');

// Rute untuk One-Click Approval Email (Menggunakan Signed URLs)
Route::get('/peminjaman/{id}/email-approve', [PeminjamanController::class, 'emailApprove'])->name('peminjaman.email_approve')->middleware('signed');
Route::get('/peminjaman/{id}/email-reject', [PeminjamanController::class, 'emailReject'])->name('peminjaman.email_reject')->middleware('signed');

//Rute untuk menampilkan halaman form login
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

//proses form login ketika tombol ditekan
Route::post('/login-web', [AuthController::class, 'loginWeb']);

//Rute Register
Route::get('/register', [AuthController::class, 'registerWebForm'])->middleware('guest');
Route::post('/register-web', [AuthController::class, 'registerWeb'])->middleware('guest');

//Rute Web terkunci (Wajib login)
Route::middleware('auth')->group(function () {

    //Rute Form Peminjaman Web
    Route::get('/peminjaman/create', [PeminjamanController::class, 'createWeb']);
    Route::post('/peminjaman/store-web', [PeminjamanController::class, 'storeWeb']);

    //proses tombol logout
    Route::post('/logout-web', [AuthController::class, 'logoutWeb']);

    Route::post('/peminjaman/{id}/approval-web', [ApprovalController::class, 'storeWeb']);

    // Manajemen Akun (User Management)
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    
    // Rute untuk update RFID mandiri (Semua User login)
    Route::post('/profil/rfid', [\App\Http\Controllers\UserController::class, 'updateRfid'])->name('profil.rfid.update');

    // Manajemen Jadwal Kuliah
    Route::get('/jadwal', [\App\Http\Controllers\JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [\App\Http\Controllers\JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [\App\Http\Controllers\JadwalController::class, 'destroy'])->name('jadwal.destroy');

});
