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

Route::middleware('auth')->group(function ()
{
    Route::get ('/', Controllers\DashboardController::class)->name('dashboard');
    Route::post('/logout', [Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get   ('/account', [Controllers\AccountController::class, 'edit'])->name('account.edit');
    Route::patch ('/account', [Controllers\AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [Controllers\AccountController::class, 'destroy'])->name('account.destroy');
    Route::get   ('/account/password', [Controllers\AccountController::class, 'editPassword'])->name('account.password');
    Route::patch ('/account/password', [Controllers\AccountController::class, 'updatePassword'])->middleware('throttle:6,1')->name('account.password.update');
    Route::get   ('/account/two-factor', [Controllers\AccountController::class, 'twoFactorAuthentication'])->name('account.two-factor-authentication');
    Route::get   ('/account/appearance', [Controllers\AccountController::class, 'appearance'])->name('account.appearance');

    //    Route::get ('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');
//    Route::get ('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
//    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

});