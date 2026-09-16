<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get("set-password/{token}", [SetPasswordController::class, 'create'])
    ->name('password-set.create');

    Route::post("set-password", [SetPasswordController::class, "store"])->name("password-set.store");


    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');


    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::post('profile/unlink-ciencia-vitae', [\App\Http\Controllers\Auth\CienciaVitaeAuthController::class, 'unlink'])
                ->name('auth.ciencia-vitae.unlink');
});

// Ciência Vitae OAuth SSO Routes (Accessible by guests for login/registration and by auth users for account linking)
Route::get('auth/ciencia-vitae', [\App\Http\Controllers\Auth\CienciaVitaeAuthController::class, 'redirect'])
    ->name('auth.ciencia-vitae.redirect');

Route::match(['get', 'post'], 'auth/ciencia-vitae/callback', [\App\Http\Controllers\Auth\CienciaVitaeAuthController::class, 'callback'])
    ->name('auth.ciencia-vitae.callback');

