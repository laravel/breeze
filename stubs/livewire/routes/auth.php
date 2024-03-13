<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Route::route('register', 'pages.auth.register')
        ->name('register');

    Route::route('login', 'pages.auth.login')
        ->name('login');

    Route::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Route::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
