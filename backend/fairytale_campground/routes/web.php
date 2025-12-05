<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\TentController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PemesananController;

// Testing new registration routes
Route::view('/test-register', 'auth.register')
    ->middleware('guest')
    ->name('test-register');
 
Route::post('/test-register', Register::class)
    ->middleware('guest');

// Testing new Login routes
Route::view('/test-login', 'auth.login')
    ->middleware('guest')
    ->name('test-login');
 
Route::post('/test-login', Login::class)
    ->middleware('guest');
 
// Testing Logout route
Route::post('/test-logout', Logout::class)
    ->middleware('auth')
    ->name('test-logout');



Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/login_success', function () {
    return view('login_success');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/register_success', function () {
    return view('register_success');
});

Route::get('/contact_us', function () {
    return view('contact_us');
}); 

Route::get('home', function () {
    return view('home');
});

Route::get('pickdate', function () {
    return view('pickdate');
}); // bisa diakses publik

Route::get('/api/check-login', function(){
    return response()->json(['logged_in' => Auth::check()]);
})->name('api.check-login');

Route::middleware('auth')->group(function () {
    Route::get('/paket', [App\Http\Controllers\PaketController::class, 'packagePage'])->name('package.index');
    
    Route::get('pilih_tenda', function () { 
        return view('pilih_tenda'); 
    })->name('pilih_tenda');

    Route::post('/pembayaran', [PembayaranController::class, 'store']);

    Route::post('/pemesanan', [PemesananController::class, 'store']);
    Route::get('/pemesanan', [PemesananController::class, 'index']);
    Route::get('/pemesanan/{id}', [PemesananController::class, 'show']);

    Route::get('hasil', function () { return view('hasil'); });
    Route::get('order_summary', function () { return view('order_summary'); });
    Route::get('payment', function () { return view('payment'); });
});



// ROUTE KHUSUS ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('/paket', PaketController::class)->except(['show']);

    Route::resource('tent', TentController::class)->except(['show']);

    Route::prefix('booking')->name('bookings.')->group(function () {
        Route::get('/', [BookingAdminController::class, 'index'])->name('index');
        Route::get('/{id}', [BookingAdminController::class, 'show'])->name('show');
        Route::post('/{id}/verify', [BookingAdminController::class, 'verify'])->name('verify');
        Route::post('/{id}/reject', [BookingAdminController::class, 'reject'])->name('reject');
    });

    
});