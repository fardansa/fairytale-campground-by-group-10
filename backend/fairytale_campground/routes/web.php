<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/booking', function () {
    return view('pickdate');
});

Route::get('/booking/single', function () {
    return view('popup1');
});

Route::get('/booking/double', function () {
    return view('popup2');
});

Route::get('/booking/family', function () {
    return view('popup3');
});

Route::get('/api/test', function () {
    return "API via WEB OK";
});
