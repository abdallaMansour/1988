<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Website\ContactController;

Route::as('website.')->group(function () {
    require_once __DIR__ . '/website.php';
});

Route::post('/contact', [ContactController::class, 'store'])->name('website.contact.store');

require_once __DIR__ . '/auth.php';

Route::middleware('auth:web')->group(function () {
    Route::get('/subscribe/{package}', [SubscriptionController::class, 'checkoutPage'])->name('subscribe.page');
    Route::post('/subscribe/{package}', [SubscriptionController::class, 'checkout'])->name('subscribe');
    Route::get('/payments/success', [SubscriptionController::class, 'success'])->name('payments.success');
    Route::get('/payments/cancel', [SubscriptionController::class, 'cancel'])->name('payments.cancel');
});

Route::prefix('dashboard')->as('dashboard.')->group(function () {
    require_once __DIR__ . '/dashboard.php';
});
