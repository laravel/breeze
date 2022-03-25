<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::controller(RegisteredUserController::class)->group(function() {
        Route::get('register', 'create')
            ->name('register');

        Route::post('register', 'store');
    });

    Route::controller(AuthenticatedSessionController::class)->group(function() {
        Route::get('login', 'create')
            ->name('login');

        Route::post('login', 'store');
    });

    Route::controller(PasswordResetLinkController::class)->group(function() {
        Route::get('forgot-password', 'create')
            ->name('password.request');

        Route::post('forgot-password', 'store')
            ->name('password.email');
    });

    Route::controller(NewPasswordController::class)->group(function() {
        Route::get('reset-password/{token}', 'create')
            ->name('password.reset');

        Route::post('reset-password', 'store')
            ->name('password.update');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::controller(ConfirmablePasswordController::class)->group(function() {
        Route::get('confirm-password', 'show')
            ->name('password.confirm');

        Route::post('confirm-password', 'store');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
