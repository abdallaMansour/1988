<!doctype html>

<html lang="ar" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="rtl" data-skin="default" data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template"
    data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>لوحة التحكم | 1988</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/svg/icons/1988_icon.svg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->
    @yield('page-css')

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    {{-- <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script> --}}

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu">
                <div class="app-brand demo my-5">
                    <a href="{{ route('website.landing-page') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span class="text-primary">
                                <img width="100" src="{{ asset('assets/svg/icons/1988_logo_dark.svg') }}" alt="1988" class="img-fluid">
                            </span>
                        </span>
                        {{-- <span class="app-brand-text demo menu-text fw-bold ms-2">1988</span> --}}
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="icon-base bx bx-chevron-left"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow" style="background: #00000000;"></div>

                <ul class="menu-inner py-1">

                    <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.index') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-home-smile"></i>
                            <div data-i18n="Dashboard">لوحة التحكم</div>
                        </a>
                    </li>

                    @if (auth('web')->check() && ! auth('admin')->check())
                        <li class="menu-item {{ request()->routeIs('dashboard.user.crimes-file') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.user.crimes-file') }}" class="menu-link">
                                <i class="menu-icon icon-base bx bx-folder-open"></i>
                                <div>ملف الجرائم</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('dashboard.user.purchases') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.user.purchases') }}" class="menu-link">
                                <i class="menu-icon icon-base bx bx-receipt"></i>
                                <div>قائمة المشتريات</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('dashboard.user.friends') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.user.friends') }}" class="menu-link">
                                <i class="menu-icon icon-base bx bx-group"></i>
                                <div>اصدقائي</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('dashboard.user.notifications') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.user.notifications') }}" class="menu-link">
                                <i class="menu-icon icon-base bx bx-bell"></i>
                                <div>الإشعارات</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('dashboard.support-tickets.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.support-tickets.index') }}" class="menu-link">
                                <i class="menu-icon icon-base bx bx-support"></i>
                                <div>الدعم الفني</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('dashboard.user.profile') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.user.profile') }}" class="menu-link">
                                <i class="menu-icon icon-base bx bx-user-circle"></i>
                                <div>الملف الشخصي</div>
                            </a>
                        </li>
                    @endif

                    @auth('admin')
                    <li class="menu-header small">
                        <span class="menu-header-text" data-i18n="Settings">الإعدادات</span>
                    </li>
                        @if (auth('admin')->user()->canAccess('site-settings.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.about-novel.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-book-content"></i>
                                    <div data-i18n="About Novel">عن الروايه</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('ratings.view') || auth('admin')->user()->canAccess('ratings.delete'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.ratings.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-star-half"></i>
                                    <div data-i18n="Ratings">التقييمات</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('faq.view') || auth('admin')->user()->canAccess('faq.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.faq.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-help-circle"></i>
                                    <div data-i18n="FAQ">الأسئلة الشائعة</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('features.view') || auth('admin')->user()->canAccess('features.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.features.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-star"></i>
                                    <div data-i18n="Features">المميزات</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('availability-places.view') || auth('admin')->user()->canAccess('availability-places.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.availability-places.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-map-pin"></i>
                                    <div data-i18n="Availability Places">أماكن التوفر</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('media-department.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.media-department.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-news"></i>
                                    <div data-i18n="Media Department">القسم الإعلامي</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('site-settings.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.privacy-policy.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-shield-quarter"></i>
                                    <div data-i18n="Privacy Policy">سياسة الخصوصيه</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('site-settings.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.terms-and-conditions.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-file-blank"></i>
                                    <div data-i18n="Terms and Conditions">الشروط و الأحكام</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('site-settings.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.about-us.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-info-circle"></i>
                                    <div data-i18n="About Us">عن المؤلف</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('site-settings.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.how-to-play.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-book-reader"></i>
                                    <div data-i18n="How to Play">كيف تلعب</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('site-settings.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.return-replacement-policy.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-transfer"></i>
                                    <div data-i18n="Return and Replacement">سياسة الاستبدال والإرجاع</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->canAccess('site-settings.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.ios-and-android-app-link.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-mobile"></i>
                                    <div data-i18n="iOS and Android App Link">روابط التطبيقات</div>
                                </a>
                            </li>
                        @endif

                        @if (auth('admin')->user()->canAccess('ranks.view') || auth('admin')->user()->canAccess('ranks.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.ranks.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-trophy"></i>
                                    <div data-i18n="Ranks">الرانكات</div>
                                </a>
                            </li>
                        @endif

                        @if (auth('admin')->user()->canAccess('coupons.view') || auth('admin')->user()->canAccess('coupons.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.coupons.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-purchase-tag"></i>
                                    <div data-i18n="Coupons">كوبونات الخصم</div>
                                </a>
                            </li>
                        @endif

                        @if (auth('admin')->user()->canAccess('languages.view') || auth('admin')->user()->canAccess('languages.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.languages.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-globe"></i>
                                    <div data-i18n="Languages">اللغات</div>
                                </a>
                            </li>
                        @endif

                        @if (auth('admin')->user()->canAccess('users.view'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.users.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-user"></i>
                                    <div data-i18n="Users">المشتركين</div>
                                </a>
                            </li>
                        @endif
                    @endauth

                    @auth('admin')
                    <li class="menu-header small">
                        <span class="menu-header-text" data-i18n="Store">المتجر</span>
                    </li>
                        @if (auth('admin')->user()->canAccess('products.view') || auth('admin')->user()->canAccess('products.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.products.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-store"></i>
                                    <div data-i18n="Products">المنتجات</div>
                                </a>
                            </li>
                        @endif

                    <li class="menu-header small">
                        <span class="menu-header-text" data-i18n="Issues">الجرائم</span>
                    </li>
                        @if (auth('admin')->user()->canAccess('issues.view') || auth('admin')->user()->canAccess('issues.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.issues.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-briefcase-alt-2"></i>
                                    <div data-i18n="Issues">الجرائم</div>
                                </a>
                            </li>
                        @endif

                    <li class="menu-header small">
                        <span class="menu-header-text" data-i18n="Support">الدعم الفني</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('dashboard.support-tickets.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.support-tickets.index') }}" class="menu-link">
                            <i class="menu-icon icon-base bx bx-support"></i>
                            <div data-i18n="Support Tickets">تذاكر الدعم الفني</div>
                        </a>
                    </li>
                        @if (auth('admin')->user()->canAccess('technical-support.view') || auth('admin')->user()->canAccess('technical-support.manage'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.technical-support.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-envelope"></i>
                                    <div data-i18n="Messages">المراسلات</div>
                                </a>
                            </li>
                        @endif
                    @endauth

                    @if (auth('admin')->check() && auth('admin')->user()->canAccessAdminManagement())
                        <li class="menu-header small">
                            <span class="menu-header-text">إدارة الأدمن</span>
                        </li>
                        @if (auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty() || auth('admin')->user()->hasPermission('admins.view'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.admins.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-user-circle"></i>
                                    <div data-i18n="Admins">الأدمن</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty() || auth('admin')->user()->hasPermission('roles.view'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.roles.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-id-card"></i>
                                    <div data-i18n="Roles">الأدوار</div>
                                </a>
                            </li>
                        @endif
                        @if (auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty() || auth('admin')->user()->hasPermission('permissions.view'))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.permissions.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-lock-alt"></i>
                                    <div data-i18n="Permissions">الصلاحيات</div>
                                </a>
                            </li>
                        @endif
                    @endif

                </ul>
            </aside>

            <div class="menu-mobile-toggler d-xl-none rounded-1">
                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                    <i class="bx bx-menu icon-base"></i>
                    <i class="bx bx-chevron-right icon-base"></i>
                </a>
            </div>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="icon-base bx bx-menu icon-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">
                                <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
                                    <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
                                </a>
                            </div>
                        </div>

                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <!-- Style Switcher -->
                            <li class="nav-item dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-sun icon-md theme-icon-active"></i>
                                    <span class="d-none ms-2" id="nav-theme-text">تبديل المظهر</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                                            <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>فاتح</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                                            <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>داكن</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system" aria-pressed="false">
                                            <span><i class="icon-base bx bx-desktop icon-md me-3" data-icon="desktop"></i>النظام</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>
                            <!-- / Style Switcher-->

                            <!-- Quick links  -->
                            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="icon-base bx bx-grid-alt icon-md"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end p-0">
                                    <div class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Shortcuts</h6>
                                            <a href="javascript:void(0)" class="dropdown-shortcuts-add py-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Add shortcuts"><i
                                                    class="icon-base bx bx-plus-circle text-heading"></i></a>
                                        </div>
                                    </div>
                                    <div class="dropdown-shortcuts-list scrollable-container">
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-calendar icon-26px text-heading"></i>
                                                </span>
                                                <a href="app-calendar.html" class="stretched-link">Calendar</a>
                                                <small>Appointments</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-food-menu icon-26px text-heading"></i>
                                                </span>
                                                <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                                                <small>Manage Accounts</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-user icon-26px text-heading"></i>
                                                </span>
                                                <a href="app-user-list.html" class="stretched-link">User App</a>
                                                <small>Manage Users</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-check-shield icon-26px text-heading"></i>
                                                </span>
                                                <a href="app-access-roles.html" class="stretched-link">Role Management</a>
                                                <small>Permission</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-pie-chart-alt-2 icon-26px text-heading"></i>
                                                </span>
                                                <a href="index.html" class="stretched-link">Dashboard</a>
                                                <small>User Dashboard</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-cog icon-26px text-heading"></i>
                                                </span>
                                                <a href="pages-account-settings-account.html" class="stretched-link">Setting</a>
                                                <small>Account Settings</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-help-circle icon-26px text-heading"></i>
                                                </span>
                                                <a href="pages-faq.html" class="stretched-link">FAQs</a>
                                                <small>FAQs & Articles</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-window-open icon-26px text-heading"></i>
                                                </span>
                                                <a href="modal-examples.html" class="stretched-link">Modals</a>
                                                <small>Useful Popups</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Quick links -->

                            <!-- Notification -->
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <span class="position-relative">
                                        <i class="icon-base bx bx-bell icon-md"></i>
                                        <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Notification</h6>
                                            <div class="d-flex align-items-center h6 mb-0">
                                                <span class="badge bg-label-primary me-2">8 New</span>
                                                <a href="javascript:void(0)" class="dropdown-notifications-all p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i
                                                        class="icon-base bx bx-envelope-open text-heading"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Congratulation Lettie 🎉</h6>
                                                        <small class="mb-1 d-block text-body">Won the monthly best seller gold badge</small>
                                                        <small class="text-body-secondary">1h ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Charles Franklin</h6>
                                                        <small class="mb-1 d-block text-body">Accepted your connection</small>
                                                        <small class="text-body-secondary">12hr ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/2.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">New Message ✉️</h6>
                                                        <small class="mb-1 d-block text-body">You have new message from Natalie</small>
                                                        <small class="text-body-secondary">1h ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-success"><i class="icon-base bx bx-cart"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Whoo! You have new order 🛒</h6>
                                                        <small class="mb-1 d-block text-body">ACME Inc. made new order $1,154</small>
                                                        <small class="text-body-secondary">1 day ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/9.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Application has been approved 🚀</h6>
                                                        <small class="mb-1 d-block text-body">Your ABC project application has been approved.</small>
                                                        <small class="text-body-secondary">2 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-success"><i class="icon-base bx bx-pie-chart-alt"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Monthly report is generated</h6>
                                                        <small class="mb-1 d-block text-body">July monthly financial report is generated </small>
                                                        <small class="text-body-secondary">3 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/5.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Send connection request</h6>
                                                        <small class="mb-1 d-block text-body">Peter sent you connection request</small>
                                                        <small class="text-body-secondary">4 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/6.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">New message from Jane</h6>
                                                        <small class="mb-1 d-block text-body">Your have new message from Jane</small>
                                                        <small class="text-body-secondary">5 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-warning"><i class="icon-base bx bx-error"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">CPU is running high</h6>
                                                        <small class="mb-1 d-block text-body">CPU Utilization Percent is currently at 88.63%,</small>
                                                        <small class="text-body-secondary">5 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="border-top">
                                        <div class="d-grid p-4">
                                            <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                                                <small class="align-middle">View all notifications</small>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Notification -->
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ auth('admin')->user()?->name ?? (auth('web')->user()?->name ?? 'مستخدم') }}</h6>
                                                    <small class="text-body-secondary">{{ auth('admin')->check() ? 'مدير' : 'مستخدم' }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-profile-user.html">
                                            <i class="icon-base bx bx-user icon-md me-3"></i><span>ملفي الشخصي</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <i class="icon-base bx bx-cog icon-md me-3"></i><span>الإعدادات</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-billing.html">
                                            <span class="d-flex align-items-center align-middle">
                                                <i class="flex-shrink-0 icon-base bx bx-credit-card icon-md me-3"></i><span class="flex-grow-1 align-middle">خطة الفوترة</span>
                                                <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-pricing.html">
                                            <i class="icon-base bx bx-dollar icon-md me-3"></i><span>الأسعار</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-faq.html">
                                            <i class="icon-base bx bx-help-circle icon-md me-3"></i><span>الأسئلة الشائعة</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        @auth('admin')
                                            <form method="POST" action="{{ route('dashboard.logout') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item w-100 text-start border-0 bg-transparent">
                                                    <i class="icon-base bx bx-power-off icon-md me-3"></i><span>تسجيل الخروج</span>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item w-100 text-start border-0 bg-transparent">
                                                    <i class="icon-base bx bx-power-off icon-md me-3"></i><span>تسجيل الخروج</span>
                                                </button>
                                            </form>
                                        @endauth
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    @yield('content')

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by
                                    <a href="https://themeselection.com" target="_blank" class="footer-link">ThemeSelection</a>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                    <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                                    <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>

                                    <a href="https://themeselection.com/support/" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>

    {{-- <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script> --}}

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    @hasSection('load-dashboard-charts')
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    @endif

    <!-- Main JS -->

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    @hasSection('load-dashboard-charts')
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    @endif

    @yield('page-js')
</body>

</html>
