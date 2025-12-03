<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\AdminController;
 
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

Route::middleware('auth')->group(function () {
    
    Route::get('/payment', function () {
        return view('payment');
        }); 
        
        Route::get('/package', function (){
            return view('paket');
        });
    
    
    
        Route::get('pilih_tenda', function () {
            return view('pilih_tenda');
        });
    
        Route::get('pickdate', function () {
            return view('pickdate');
        });
    
    
        Route::get('hasil', function () {
            return view('hasil');
        });
    
        Route::get('order_summary', function () {
            return view('order_summary');
        });
    //Route::get('/booking', function () {
    // //  return view('pickdate');
    // //});


});

Route::get('admin_dashboard', [AdminController::class, 'index']) 
    ->  middleware('admin')
    -> name('admin_dashboard');

Route::get('/api/test', function () {
return "API via WEB OK";
});