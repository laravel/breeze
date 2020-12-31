<?php

use DummyControllerNamespace\AuthenticatedSessionController;
use DummyControllerNamespace\ConfirmablePasswordController;
use DummyControllerNamespace\EmailVerificationNotificationController;
use DummyControllerNamespace\EmailVerificationPromptController;
use DummyControllerNamespace\NewPasswordController;
use DummyControllerNamespace\PasswordResetLinkController;
use DummyControllerNamespace\RegisteredUserController;
use DummyControllerNamespace\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('DummyRoutePrefix')->group(function () {
    Route::middleware('guestDummyRouteGuestMiddlewareGuard')->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])
            ->name('DummyRouteNamePrefixregister');

        Route::post('/register', [RegisteredUserController::class, 'store']);

        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->name('DummyRouteNamePrefixlogin');

        Route::post('/login', [AuthenticatedSessionController::class, 'store']);

        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('DummyRouteNamePrefixpassword.request');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('DummyRouteNamePrefixpassword.email');

        Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('DummyRouteNamePrefixpassword.reset');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->name('DummyRouteNamePrefixpassword.update');
    });

    Route::middleware('authDummyRouteGuestMiddlewareGuard')->group(function () {
        Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
            ->name('DummyRouteNamePrefixverification.notice');

        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('DummyRouteNamePrefixverification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['throttle:6,1'])
            ->name('DummyRouteNamePrefixverification.send');

        Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('DummyRouteNamePrefixpassword.confirm');

        Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('DummyRouteNamePrefixlogout');
    });
});
