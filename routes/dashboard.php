<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserVerified;
use App\Http\Controllers\Dashboard\FaqController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\PagesController;
use App\Http\Controllers\Dashboard\FeatureController;
use App\Http\Controllers\Dashboard\PackageController;
use App\Http\Controllers\Dashboard\AdminAuthController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\RatingController;
use App\Http\Controllers\Dashboard\SiteSettingController;
use App\Http\Controllers\Dashboard\VerificationController;
use App\Http\Controllers\Dashboard\SupportTicketController;
use App\Http\Controllers\Dashboard\MediaDepartmentController;
use App\Http\Controllers\Dashboard\TechnicalSupportController;
use App\Http\Controllers\Dashboard\SubscriptionController as DashboardSubscriptionController;

// Admin Auth (login only - no register, no forgot-password)
Route::prefix('auth')->middleware('guest:admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('login', [AdminAuthController::class, 'processLogin'])->name('login.process');
});

Route::post('auth/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth:admin');

// Dashboard Pages (users + admins - users see limited menu)
Route::middleware(['auth:web,admin', EnsureUserVerified::class])->group(function () {
    Route::get('/', [PagesController::class, 'index'])->name('index');

    // Verification (must be accessible before full verification)
    Route::get('verification', [VerificationController::class, 'index'])->name('verification.index');
    Route::post('verification/email/send', [VerificationController::class, 'sendEmailCode'])->name('verification.email.send');
    Route::post('verification/email', [VerificationController::class, 'verifyEmail'])->name('verification.email.verify');
    Route::post('verification/phone/send', [VerificationController::class, 'sendPhoneCode'])->name('verification.phone.send');
    Route::post('verification/phone', [VerificationController::class, 'verifyPhone'])->name('verification.phone.verify');

    // Packages: index for both, create/store/edit/update/destroy for admin only
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('features', [FeatureController::class, 'index'])->name('features.index');
    Route::post('ratings', [RatingController::class, 'store'])->name('ratings.store')->middleware('auth:web');
    Route::get('ratings', [RatingController::class, 'index'])->name('ratings.index')->middleware(['auth:admin', 'permission:ratings.view']);
    Route::get('privacy-policy', [SiteSettingController::class, 'privacyPolicy'])->name('privacy-policy.index');
    Route::get('terms-and-conditions', [SiteSettingController::class, 'termsAndConditions'])->name('terms-and-conditions.index');
    Route::get('about-us', [SiteSettingController::class, 'aboutUs'])->name('about-us.index');
    Route::get('ios-and-android-app-link', [SiteSettingController::class, 'iosAndAndroidAppLink'])->name('ios-and-android-app-link.index');
    Route::get('media-department', [MediaDepartmentController::class, 'index'])->name('media-department.index')->middleware('permission:media-department.manage');

    // Support Tickets (users + admins)
    Route::get('support-tickets', [SupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::get('support-tickets/create', [SupportTicketController::class, 'create'])->name('support-tickets.create');
    Route::post('support-tickets', [SupportTicketController::class, 'store'])->name('support-tickets.store');
    Route::get('support-tickets/{support_ticket}', [SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('support-tickets/{support_ticket}/reply', [SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::put('support-tickets/{support_ticket}/status', [SupportTicketController::class, 'updateStatus'])->name('support-tickets.status')->middleware(['auth:admin', 'permission:support-tickets.manage']);

    Route::middleware('auth:admin')->group(function () {
        // المشتركين
        Route::middleware('permission:users.view')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
        });
        Route::middleware('permission:users.edit')->group(function () {
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        });
        Route::middleware('permission:users.delete')->group(function () {
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });
        Route::middleware('permission:users.ban')->group(function () {
            Route::post('users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
            Route::post('users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
        });

        Route::middleware('permission:packages.create')->group(function () {
            Route::get('packages/create', [PackageController::class, 'create'])->name('packages.create');
            Route::post('packages', [PackageController::class, 'store'])->name('packages.store');
        });
        Route::middleware('permission:packages.edit')->group(function () {
            Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
            Route::put('packages/{package}', [PackageController::class, 'update'])->name('packages.update');
        });
        Route::middleware('permission:packages.delete')->group(function () {
            Route::delete('packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
        });

        Route::middleware('permission:subscriptions.view')->group(function () {
            Route::get('subscriptions', [DashboardSubscriptionController::class, 'index'])->name('subscriptions.index');
            Route::get('subscriptions/{subscription}', [DashboardSubscriptionController::class, 'show'])->name('subscriptions.show');
        });

        // FAQ CRUD (admin only)
        Route::middleware('permission:faq.manage')->group(function () {
            Route::get('faq/create', [FaqController::class, 'create'])->name('faq.create');
            Route::post('faq', [FaqController::class, 'store'])->name('faq.store');
            Route::get('faq/{faq}/edit', [FaqController::class, 'edit'])->name('faq.edit');
            Route::put('faq/{faq}', [FaqController::class, 'update'])->name('faq.update');
            Route::delete('faq/{faq}', [FaqController::class, 'destroy'])->name('faq.destroy');
        });

        // Features CRUD (admin only)
        Route::middleware('permission:features.manage')->group(function () {
            Route::get('features/create', [FeatureController::class, 'create'])->name('features.create');
            Route::post('features', [FeatureController::class, 'store'])->name('features.store');
            Route::get('features/{feature}/edit', [FeatureController::class, 'edit'])->name('features.edit');
            Route::put('features/{feature}', [FeatureController::class, 'update'])->name('features.update');
            Route::delete('features/{feature}', [FeatureController::class, 'destroy'])->name('features.destroy');
        });

        // Ratings (admin can delete only)
        Route::middleware('permission:ratings.delete')->group(function () {
            Route::delete('ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
        });

        // Site Settings (privacy policy & terms - same table, separate sections)
        Route::middleware('permission:site-settings.manage')->group(function () {
            Route::put('privacy-policy', [SiteSettingController::class, 'updatePrivacyPolicy'])->name('privacy-policy.update');
            Route::put('terms-and-conditions', [SiteSettingController::class, 'updateTermsAndConditions'])->name('terms-and-conditions.update');
            Route::put('about-us', [SiteSettingController::class, 'updateAboutUs'])->name('about-us.update');
            Route::put('ios-and-android-app-link', [SiteSettingController::class, 'updateIosAndAndroidAppLink'])->name('ios-and-android-app-link.update');
        });

        // Media Department (admin only)
        Route::middleware('permission:media-department.manage')->group(function () {
            Route::put('media-department', [MediaDepartmentController::class, 'update'])->name('media-department.update');
        });

        // Technical Support (admin only)
        Route::middleware('permission:technical-support.view')->group(function () {
            Route::get('technical-support', [TechnicalSupportController::class, 'index'])->name('technical-support.index');
            Route::get('technical-support/mails', [TechnicalSupportController::class, 'mails'])->name('technical-support.mails');
            Route::get('technical-support/{contact_message}', [TechnicalSupportController::class, 'show'])->name('technical-support.show');
        });
        Route::middleware('permission:technical-support.manage')->group(function () {
            Route::post('technical-support/{contact_message}/reply', [TechnicalSupportController::class, 'reply'])->name('technical-support.reply');
        });

        // Admin Management (role & permissions)
        Route::middleware('permission:admins.view')->group(function () {
            Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
        });
        Route::middleware('permission:admins.create')->group(function () {
            Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create');
            Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
        });
        Route::middleware('permission:admins.edit')->group(function () {
            Route::get('admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
            Route::put('admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
        });
        Route::middleware('permission:admins.delete')->group(function () {
            Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
        });

        Route::middleware('permission:roles.view')->group(function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        });
        Route::middleware('permission:roles.create')->group(function () {
            Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        });
        Route::middleware('permission:roles.edit')->group(function () {
            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        });
        Route::middleware('permission:roles.delete')->group(function () {
            Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });

        Route::middleware('permission:permissions.view')->group(function () {
            Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        });
        Route::middleware('permission:permissions.edit')->group(function () {
            Route::post('permissions/update', [PermissionController::class, 'updateRolePermissions'])->name('permissions.update');
        });
    });
});
