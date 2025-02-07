<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/login", function () {
    return "<p>Login required</p>";
});

Route::fallback(function () {
    return "<p>404 Not Found</p>";
});