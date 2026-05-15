<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SiteSettingController;

Route::prefix('packages')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->name('index');
});

Route::prefix('auth')->group(function () {

    // Login and Register
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // Forgot Password
    Route::post('send-reset-code', [AuthController::class, 'sendResetCode'])->middleware('throttle:2,1');
    Route::post('update-password', [AuthController::class, 'updatePassword']);

    Route::middleware('auth:sanctum')->group(function () {

        // Subscription
        Route::post('subscribe/{package}', [SubscriptionController::class, 'checkout'])->middleware('throttle:5,1');

        // Logout
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::get('payments/success', [SubscriptionController::class, 'success'])->name('payments.success');
Route::get('payments/cancel', [SubscriptionController::class, 'cancel'])->name('payments.cancel');

Route::get('site-settings', [SiteSettingController::class, 'siteSetting']);

Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProfileController::class, 'getProfile']);
    // Route::post('/', [ProfileController::class, 'updateProfile'])->middleware('throttle:5,1');
});