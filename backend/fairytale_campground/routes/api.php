<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TentController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// DEBUG
Route::get('/tes-debug', function() {
    return 'API route works!';
});

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// USER AUTH REQUIRED
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::post('/pembayaran', [PembayaranController::class, 'store'])->middleware('auth');
    Route::get('/pembayaran/', [PembayaranController::class, 'index']);
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show']);

    // Route::post('/pemesanan', [PemesananController::class, 'store']);
    // Route::get('/pemesanan', [PemesananController::class, 'index']);
    // Route::get('/pemesanan/{id}', [PemesananController::class, 'show']);
});

// PUBLIC
Route::get('/paket', [PaketController::class, 'index']);
Route::get('/paket/{id}', [PaketController::class, 'show']);

Route::get('/tent', [TentController::class, 'index']);
Route::get('/tent/{id}', [TentController::class, 'show']);
Route::post('/tent/available', [TentController::class, 'checkAvailability']);

// ADMIN ONLY
Route::middleware(['auth:sanctum','admin'])->group(function() {
    Route::post('/paket', [PaketController::class, 'store']);
    Route::put('/paket/{id}', [PaketController::class, 'update']);
    Route::delete('/paket/{id}', [PaketController::class, 'destroy']);

    Route::post('/tent', [TentController::class, 'store']);
    Route::put('/tent/{id}', [TentController::class, 'update']);
    Route::delete('/tent/{id}', [TentController::class, 'destroy']);

    Route::get('/admin/bookings', [AdminController::class, 'bookings']);
    Route::get('/admin/bookings/{id}', [AdminController::class, 'bookingDetail']);

    Route::put('/admin/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verifikasi']);
});
