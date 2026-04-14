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

});
