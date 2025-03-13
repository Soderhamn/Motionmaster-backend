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

Route::get('/test-email', function () {
    Mail::to('marcus.andersson@sandarnecreations.com')->send(new WelcomeMail('Test User', 'test@example.com', '123456'));
    return 'Email sent!';
});

Route::fallback(function () {
    return "<p>404 Not Found</p>";
});