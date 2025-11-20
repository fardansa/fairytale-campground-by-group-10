<?php

use Illuminate\Support\Facades\Route;
use routes\api;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/test', function () {
    return "API via WEB OK";
});
