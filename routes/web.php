<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Http\Controllers\ExerciseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/exercises/{id}', [ExerciseController::class, 'show'])->name('exercises.show');

Route::get('/terms-of-use', function () {
    return view('terms-of-use');
})->name('terms-of-use');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('delete-account', function () {
    return view('delete-account');
})->name('delete-account');

Route::get('/support', function () {
    return view('support');
})->name('support');

Route::get("/login", function () {
    return "<p>Login required</p>";
});

Route::fallback(function () {
    return "<p>404 Not Found</p>";
});