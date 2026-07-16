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

// Rute SSO Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google')->middleware('guest');
Route::get('/fix-db', function () {
    try {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE peminjaman DROP FOREIGN KEY peminjaman_ibfk_2');
        echo "FK dropped successfully.<br>";
    } catch (\Exception $e) {
        echo "Error dropping FK: " . $e->getMessage() . "<br>";
    }
    
    try {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE peminjaman DROP INDEX id_lab');
        echo "Index dropped successfully.<br>";
    } catch (\Exception $e) {
        echo "Error dropping Index: " . $e->getMessage() . "<br>";
    }

    return "Done.";
});

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->middleware('guest');

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

    // Manajemen Akun (User Management - POST saja karena index ada di dashboard)
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    
    // Rute untuk update RFID mandiri (Semua User login)
    Route::post('/profil/rfid', [\App\Http\Controllers\UserController::class, 'updateRfid'])->name('profil.rfid.update');
    
    // Rute untuk update RFID oleh Admin BAA
    Route::post('/admin/rfid/update', [\App\Http\Controllers\UserController::class, 'updateRfidAdmin'])->name('admin.rfid.update');

    // Manajemen Jadwal Kuliah
    Route::get('/jadwal', [\App\Http\Controllers\JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [\App\Http\Controllers\JadwalController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [\App\Http\Controllers\JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [\App\Http\Controllers\JadwalController::class, 'destroy'])->name('jadwal.destroy');

    // Manajemen Ruang Lab
    Route::get('/labs', [\App\Http\Controllers\LabController::class, 'index'])->name('labs.index');
    Route::post('/labs', [\App\Http\Controllers\LabController::class, 'store'])->name('labs.store');
    Route::put('/labs/{id}', [\App\Http\Controllers\LabController::class, 'update'])->name('labs.update');
    Route::delete('/labs/{id}', [\App\Http\Controllers\LabController::class, 'destroy'])->name('labs.destroy');

    // Manajemen Asset
    Route::get('/assets', [\App\Http\Controllers\AssetController::class, 'index'])->name('assets.index');
    Route::post('/assets', [\App\Http\Controllers\AssetController::class, 'store'])->name('assets.store');
    Route::put('/assets/{id}', [\App\Http\Controllers\AssetController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{id}', [\App\Http\Controllers\AssetController::class, 'destroy'])->name('assets.destroy');

});
