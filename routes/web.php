<?php

use App\Http\Controllers\GiftController;
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
    Route::get('/checkout/products/{product}/gift', [StoreCheckoutController::class, 'productGiftCheckout'])->name('website.checkout.product.gift');
    Route::post('/checkout/products/{product}/gift', [StoreCheckoutController::class, 'productGiftPay'])->name('website.checkout.product.gift.pay');
    Route::get('/checkout/issues/{issue}', [StoreCheckoutController::class, 'issueCheckout'])->name('website.checkout.issue');
    Route::post('/checkout/issues/{issue}', [StoreCheckoutController::class, 'issuePay'])->name('website.checkout.issue.pay');
    Route::get('/checkout/issues/{issue}/gift', [StoreCheckoutController::class, 'issueGiftCheckout'])->name('website.checkout.issue.gift');
    Route::post('/checkout/issues/{issue}/gift', [StoreCheckoutController::class, 'issueGiftPay'])->name('website.checkout.issue.gift.pay');
    Route::get('/my-purchases', [PagesController::class, 'myPurchases'])->name('website.my-purchases');
    Route::get('/gifts/sent/{purchase}', [GiftController::class, 'sent'])->name('website.gift.sent');
    Route::post('/gifts/sent/{purchase}/invite', [GiftController::class, 'sendInvite'])->name('website.gift.sent.invite');
    Route::get('/gifts/claim/{token}', [GiftController::class, 'claimShow'])->where('token', '[a-fA-F0-9\-]{36}')->name('website.gifts.claim.show');
    Route::post('/gifts/claim/{token}', [GiftController::class, 'claimAccept'])->where('token', '[a-fA-F0-9\-]{36}')->name('website.gifts.claim.accept');
});

Route::redirect('/my-purchases/issues', '/my-purchases', 301);

Route::prefix('dashboard')->as('dashboard.')->group(function () {
    require_once __DIR__.'/dashboard.php';
});
