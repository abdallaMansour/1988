<?php

use App\Http\Controllers\Website\PagesController;
use App\Http\Controllers\Website\RatingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class, 'landingPage'])->name('landing-page');
Route::get('/privacy-policy', [PagesController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [PagesController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('/faq', [PagesController::class, 'faq'])->name('faq');
Route::get('/features', [PagesController::class, 'features'])->name('features');
Route::get('/products', [PagesController::class, 'products'])->name('products');
Route::get('/products/{product}', [PagesController::class, 'product'])->name('products.show');
Route::get('/ratings', [RatingController::class, 'index'])->name('ratings');
Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
