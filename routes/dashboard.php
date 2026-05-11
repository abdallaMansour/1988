<?php

use App\Http\Controllers\Dashboard\AdminAuthController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AvailabilityPlaceController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\FaqController;
use App\Http\Controllers\Dashboard\FeatureController;
use App\Http\Controllers\Dashboard\IssueController;
use App\Http\Controllers\Dashboard\IssueEvidenceController;
use App\Http\Controllers\Dashboard\IssueHintController;
use App\Http\Controllers\Dashboard\IssueInvestigationReportController;
use App\Http\Controllers\Dashboard\IssueRoundController;
use App\Http\Controllers\Dashboard\IssueVideosController;
use App\Http\Controllers\Dashboard\IssueWitnessController;
use App\Http\Controllers\Dashboard\MediaDepartmentController;
use App\Http\Controllers\Dashboard\PackageController;
use App\Http\Controllers\Dashboard\PagesController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\RankController;
use App\Http\Controllers\Dashboard\RatingController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SiteSettingController;
use App\Http\Controllers\Dashboard\SubscriptionController as DashboardSubscriptionController;
use App\Http\Controllers\Dashboard\SupportTicketController;
use App\Http\Controllers\Dashboard\TechnicalSupportController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\VerificationController;
use App\Http\Middleware\EnsureUserVerified;
use Illuminate\Support\Facades\Route;

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
    Route::get('availability-places', [AvailabilityPlaceController::class, 'index'])->name('availability-places.index');
    Route::get('products', [ProductController::class, 'index'])->name('products.index')->middleware(['auth:admin', 'permission:products.view']);
    Route::get('issues', [IssueController::class, 'index'])->name('issues.index')->middleware(['auth:admin', 'permission:issues.view']);
    Route::get('ratings', [RatingController::class, 'index'])->name('ratings.index')->middleware(['auth:admin', 'permission:ratings.view']);
    Route::get('privacy-policy', [SiteSettingController::class, 'privacyPolicy'])->name('privacy-policy.index');
    Route::get('terms-and-conditions', [SiteSettingController::class, 'termsAndConditions'])->name('terms-and-conditions.index');
    Route::get('about-us', [SiteSettingController::class, 'aboutUs'])->name('about-us.index');
    Route::get('about-novel', [SiteSettingController::class, 'aboutNovel'])->name('about-novel.index');
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

        // Products CRUD (admin only)
        Route::middleware('permission:products.manage')->group(function () {
            Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('products', [ProductController::class, 'store'])->name('products.store');
            Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        });

        // Issues CRUD (admin only)
        Route::middleware('permission:issues.manage')->group(function () {
            Route::get('issues/create', [IssueController::class, 'create'])->name('issues.create');
            Route::post('issues', [IssueController::class, 'store'])->name('issues.store');
            Route::get('issues/{issue}/videos', [IssueVideosController::class, 'edit'])->name('issues.videos.edit');
            Route::put('issues/{issue}/videos', [IssueVideosController::class, 'update'])->name('issues.videos.update');
            Route::delete('issues/{issue}/videos/evidence/{media}', [IssueVideosController::class, 'destroyEvidence'])->name('issues.videos.evidence.destroy');
            Route::get('issues/{issue}/hints', [IssueHintController::class, 'index'])->name('issues.hints.index');
            Route::post('issues/{issue}/hints', [IssueHintController::class, 'store'])->name('issues.hints.store');
            Route::delete('issues/{issue}/hints/{hint}', [IssueHintController::class, 'destroy'])->name('issues.hints.destroy');
            Route::get('issues/{issue}/witnesses', [IssueWitnessController::class, 'index'])->name('issues.witnesses.index');
            Route::post('issues/{issue}/witnesses', [IssueWitnessController::class, 'store'])->name('issues.witnesses.store');
            Route::delete('issues/{issue}/witnesses/{witness}', [IssueWitnessController::class, 'destroy'])->name('issues.witnesses.destroy');
            Route::get('issues/{issue}/evidences', [IssueEvidenceController::class, 'index'])->name('issues.evidences.index');
            Route::post('issues/{issue}/evidences', [IssueEvidenceController::class, 'store'])->name('issues.evidences.store');
            Route::delete('issues/{issue}/evidences/{evidence}', [IssueEvidenceController::class, 'destroy'])->name('issues.evidences.destroy');
            Route::get('issues/{issue}/rounds', [IssueRoundController::class, 'edit'])->name('issues.rounds.edit');
            Route::put('issues/{issue}/rounds', [IssueRoundController::class, 'update'])->name('issues.rounds.update');
            Route::get('issues/{issue}/investigation-reports', [IssueInvestigationReportController::class, 'index'])->name('issues.investigation-reports.index');
            Route::get('issues/{issue}/investigation-reports/create', [IssueInvestigationReportController::class, 'create'])->name('issues.investigation-reports.create');
            Route::post('issues/{issue}/investigation-reports', [IssueInvestigationReportController::class, 'store'])->name('issues.investigation-reports.store');
            Route::get('issues/{issue}/investigation-reports/{investigation_report}/edit', [IssueInvestigationReportController::class, 'edit'])->name('issues.investigation-reports.edit');
            Route::put('issues/{issue}/investigation-reports/{investigation_report}', [IssueInvestigationReportController::class, 'update'])->name('issues.investigation-reports.update');
            Route::delete('issues/{issue}/investigation-reports/{investigation_report}', [IssueInvestigationReportController::class, 'destroy'])->name('issues.investigation-reports.destroy');
            Route::get('issues/{issue}/edit', [IssueController::class, 'edit'])->name('issues.edit');
            Route::put('issues/{issue}', [IssueController::class, 'update'])->name('issues.update');
            Route::delete('issues/{issue}', [IssueController::class, 'destroy'])->name('issues.destroy');
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

        // أماكن التوفر CRUD (admin only)
        Route::middleware('permission:availability-places.manage')->group(function () {
            Route::get('availability-places/create', [AvailabilityPlaceController::class, 'create'])->name('availability-places.create');
            Route::post('availability-places', [AvailabilityPlaceController::class, 'store'])->name('availability-places.store');
            Route::get('availability-places/{availability_place}/edit', [AvailabilityPlaceController::class, 'edit'])->name('availability-places.edit');
            Route::put('availability-places/{availability_place}', [AvailabilityPlaceController::class, 'update'])->name('availability-places.update');
            Route::delete('availability-places/{availability_place}', [AvailabilityPlaceController::class, 'destroy'])->name('availability-places.destroy');
        });

        // Ratings (admin can delete only)
        Route::middleware('permission:ratings.delete')->group(function () {
            Route::delete('ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
        });

        // Ranks CRUD (admin only — الصلاحيات داخل المتحكم)
        Route::get('ranks', [RankController::class, 'index'])->name('ranks.index');
        Route::get('ranks/create', [RankController::class, 'create'])->name('ranks.create');
        Route::post('ranks', [RankController::class, 'store'])->name('ranks.store');
        Route::get('ranks/{rank}/edit', [RankController::class, 'edit'])->name('ranks.edit');
        Route::put('ranks/{rank}', [RankController::class, 'update'])->name('ranks.update');
        Route::delete('ranks/{rank}', [RankController::class, 'destroy'])->name('ranks.destroy');

        // Coupons CRUD (admin only — الصلاحيات داخل المتحكم)
        Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::get('coupons/create', [CouponController::class, 'create'])->name('coupons.create');
        Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

        // Site Settings (privacy policy & terms - same table, separate sections)
        Route::middleware('permission:site-settings.manage')->group(function () {
            Route::put('privacy-policy', [SiteSettingController::class, 'updatePrivacyPolicy'])->name('privacy-policy.update');
            Route::put('terms-and-conditions', [SiteSettingController::class, 'updateTermsAndConditions'])->name('terms-and-conditions.update');
            Route::put('about-us', [SiteSettingController::class, 'updateAboutUs'])->name('about-us.update');
            Route::put('about-novel', [SiteSettingController::class, 'updateAboutNovel'])->name('about-novel.update');
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
