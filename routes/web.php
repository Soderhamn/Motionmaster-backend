<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('delete-account', function () {
    return view('delete-account');
})->name('delete-account');

Route::get("/login", function () {
    return "<p>Login required</p>";
});

Route::fallback(function () {
    return "<p>404 Not Found</p>";
});