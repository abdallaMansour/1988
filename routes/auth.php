<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;

Route::prefix('auth')->as('auth.')->middleware('guest:web')->group(function () {
    Route::get('login', [UserAuthController::class, 'login'])->name('login');
    Route::post('login', [UserAuthController::class, 'processLogin'])->name('login.process');
    Route::get('register', [UserAuthController::class, 'register'])->name('register');
    Route::post('register', [UserAuthController::class, 'processRegister'])->name('register.process');
    Route::get('forgot-password', [UserAuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('forgot-password', [UserAuthController::class, 'sendResetCode'])->name('forgot-password.send');
    Route::get('reset-password', [UserAuthController::class, 'resetPassword'])->name('reset-password');
    Route::post('reset-password', [UserAuthController::class, 'updatePassword'])->name('reset-password.update');
});

Route::post('auth/logout', [UserAuthController::class, 'logout'])->name('auth.logout')->middleware('auth:web');
