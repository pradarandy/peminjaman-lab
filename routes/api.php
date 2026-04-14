<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\AuthController;

// --- ROUTE PUBLIK (Bisa diakses tanpa token) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Endpoint untuk ESP32 (Mesin tidak perlu login seperti manusia)
Route::post('/rfid/check', [RfidController::class, 'checkAccess']);


// --- ROUTE TERKUNCI (Wajib login dan bawa Bearer Token) ---
Route::middleware('auth:sanctum')->group(function () {

    // User get profil
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Peminjaman dan Approval
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::post('/peminjaman/{id}/approval', [ApprovalController::class, 'store']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

});