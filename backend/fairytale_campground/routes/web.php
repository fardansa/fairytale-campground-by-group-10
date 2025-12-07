<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\TentController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\BookingUserController;


// ===========================
// PUBLIC ROUTES
// ===========================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/contact_us', function () {
    return view('contact_us');
})->name('contact');

Route::get('/login', function () {
    return redirect()->route('test-login');
})->name('login');
Route::view('/test-login', 'auth.login')->middleware('guest')->name('test-login');
Route::post('/test-login', Login::class)->middleware('guest');
Route::view('/test-register', 'auth.register')->middleware('guest')->name('test-register');
Route::post('/test-register', Register::class)->middleware('guest');
Route::post('/test-logout', Logout::class)->middleware('auth')->name('test-logout');


// AJAX check login
Route::get('/api/check-login', function () {
    return response()->json(['logged_in' => Auth::check()]);
})->name('api.check-login');

// PUBLIC ROUTES — USER BOOKING DATE
Route::prefix('booking')->name('booking.')->group(function () {

    Route::get('/date', [BookingUserController::class, 'DatePage'])->name('date');
    Route::post('/date', [BookingUserController::class, 'storeDate'])->name('date.store');
});
// ===========================
// USER ROUTES — BOOKING PROSES
// ===========================
Route::middleware('auth')->prefix('booking')->name('booking.')->group(function () {

    Route::get('/paket', [BookingUserController::class, 'paketPage'])->name('paket');
    Route::post('/paket', [BookingUserController::class, 'storePaket'])->name('paket.store');

    Route::get('/tent', [BookingUserController::class, 'tentPage'])->name('tent');
    Route::post('/select-tent', [BookingUserController::class, 'selectTent'])->name('selectTent'); // perbaikan

    Route::get('/summary', [BookingUserController::class, 'summaryPage'])->name('summary');
    Route::post('/summary', [BookingUserController::class, 'storeBooking'])->name('summary.store');

    Route::get('/payment', [BookingUserController::class, 'paymentPage'])->name('payment');
    Route::post('/payment/upload', [BookingUserController::class, 'uploadPayment'])->name('payment.upload');

    Route::get('/booking/complete', [\App\Http\Controllers\BookingUserController::class, 'completePage'])->name('complete');
    Route::get('/booking/complete/{id?}', [BookingUserController::class, 'completePage'])->name('booking.complete');

});
Route::get('/booking/history', [BookingUserController::class, 'historyPage'])
    ->middleware('auth')
    ->name('booking.history');

// ===========================
// ADMIN ROUTES
// ===========================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/paket', PaketController::class)->except(['show']);
    Route::resource('/tent', TentController::class)->except(['show']);

    Route::prefix('booking')->name('bookings.')->group(function () {
        Route::get('/', [BookingAdminController::class, 'index'])->name('index');
        Route::get('/{id}', [BookingAdminController::class, 'show'])->name('show');
        Route::post('/{id}/verify', [BookingAdminController::class, 'verify'])->name('verify');
        Route::post('/{id}/reject', [BookingAdminController::class, 'reject'])->name('reject');
    });

});
