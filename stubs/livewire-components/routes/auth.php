<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::middleware('guest')->group(function () {
    Route::view('register', 'auth.register')
        ->name('register');

    Route::view('login', 'auth.login')
        ->name('login');

    Route::view('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Route::view('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::view('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});
