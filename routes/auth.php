<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\VerifyEmailControllerAPI;
use Illuminate\Support\Facades\Route;

// API-only
Route::post('api/register', [RegisteredUserController::class, 'storeAPI'])
    ->middleware('guest')
    ->name('register');

Route::post('api/login', [AuthenticatedSessionController::class, 'storeAPI'])
    ->middleware('guest')
    ->name('login');

Route::post('api/forgot-password', [PasswordResetLinkController::class, 'storeAPI'])
    ->middleware('guest')
    ->name('password.email');

Route::post('api/reset-password', [NewPasswordController::class, 'storeAPI'])
    ->middleware('guest')
    ->name('password.store');

Route::get('api/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('api/email/verification-notification', [EmailVerificationNotificationController::class, 'storeAPI'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('api/logout', [AuthenticatedSessionController::class, 'destroyAPI'])
    ->middleware('auth')
    ->name('logout');

// Web API Authentication (works with Laravel Blade)
Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('verify-email/{id}/{hash}', VerifyEmailControllerAPI::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');