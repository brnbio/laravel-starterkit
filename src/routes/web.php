<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

// @formatter:off

Route::middleware('guest')->group(function () {
    Route::get ('/login', [Controllers\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [Controllers\AuthController::class, 'login']);
    Route::get ('/forgot-password', [Controllers\AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [Controllers\AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get ('/reset-password/{token}', [Controllers\AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [Controllers\AuthController::class, 'reset'])->name('password.store');
    Route::get ('/register', [Controllers\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [Controllers\AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => inertia('Dashboard'))->name('dashboard');
    //    Route::get ('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');
//    Route::get ('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
//    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    Route::post('/logout', [Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get   ('/account', Controllers\AccountController::class)->name('account');
//    Route::patch ('/account/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/account/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//    Route::get   ('/account/password', [PasswordController::class, 'edit'])->name('password.edit');
//    Route::put   ('/account/password', [PasswordController::class, 'update'])->middleware('throttle:6,1')->name('password.update');
//    Route::get   ('/account/two-factor', [TwoFactorAuthenticationController::class, 'show'])->name('two-factor.show');
//    Route::get   ('/account/appearance', fn () => Inertia::render('settings/Appearance'))->name('appearance.edit');
});