<?php

use App\Http\Controllers\StoreCheckoutController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\PagesController;
use Illuminate\Support\Facades\Route;

Route::as('website.')->group(function () {
    require_once __DIR__.'/website.php';
});

Route::post('/contact', [ContactController::class, 'store'])->name('website.contact.store');

require_once __DIR__.'/auth.php';

Route::middleware('auth:web')->group(function () {
    Route::get('/subscribe/{package}', [SubscriptionController::class, 'checkoutPage'])->name('subscribe.page');
    Route::post('/subscribe/{package}', [SubscriptionController::class, 'checkout'])->name('subscribe');
    Route::get('/payments/success', [SubscriptionController::class, 'success'])->name('payments.success');
    Route::get('/payments/cancel', [SubscriptionController::class, 'cancel'])->name('payments.cancel');

    Route::get('/checkout/products/{product}', [StoreCheckoutController::class, 'productCheckout'])->name('website.checkout.product');
    Route::post('/checkout/products/{product}', [StoreCheckoutController::class, 'productPay'])->name('website.checkout.product.pay');
    Route::get('/checkout/issues/{issue}', [StoreCheckoutController::class, 'issueCheckout'])->name('website.checkout.issue');
    Route::post('/checkout/issues/{issue}', [StoreCheckoutController::class, 'issuePay'])->name('website.checkout.issue.pay');
    Route::get('/my-purchases/issues', [PagesController::class, 'purchasedIssues'])->name('website.purchased-issues');
});

Route::prefix('dashboard')->as('dashboard.')->group(function () {
    require_once __DIR__.'/dashboard.php';
});
