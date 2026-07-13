<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\QuickSignupController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * Rowaad platform auth routes.
 *  • Public: login only (admin + consultant + regular user).
 *  • Regular users register via quick signup (from booking flow).
 *  • Consultants apply via /become-a-consultant (creates user automatically).
 */

Route::middleware('guest')->group(function () {
    Route::get ('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Dedicated register page for regular users
    Route::get ('register', [QuickSignupController::class, 'create'])->name('register');
    Route::post('register', [QuickSignupController::class, 'store'])->name('register.store');

    // Legacy quick signup endpoint kept for booking flow compatibility
    Route::post('signup', [QuickSignupController::class, 'store'])->name('signup');

    // Forgot password
    Route::get ('forgot-password',        [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password',        [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get ('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password',         [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Email verification
    Route::get('verify-email', [VerifyEmailController::class, 'notice'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [VerifyEmailController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});
