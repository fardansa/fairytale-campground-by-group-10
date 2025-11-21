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

Route::get('/api/test', function () {
    return "API via WEB OK";
});
