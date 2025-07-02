<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/login", function () {
    return "<p>Login required</p>";
});

Route::fallback(function () {
    return "<p>404 Not Found</p>";
});