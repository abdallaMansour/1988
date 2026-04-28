<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\PagesController;

Route::get('/', [PagesController::class, 'landingPage'])->name('landing-page');
Route::get('/privacy-policy', [PagesController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [PagesController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('/faq', [PagesController::class, 'faq'])->name('faq');
Route::get('/features', [PagesController::class, 'features'])->name('features');
