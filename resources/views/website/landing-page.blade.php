@extends('website.layouts.master')

@section('content')
    <!-- Sections:Start -->
    @if (session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="container mt-4">
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if (session('info'))
        <div class="container mt-4">
            <div class="alert alert-info alert-dismissible" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <div data-bs-spy="scroll" class="scrollspy-example">
        <!-- Hero: Start -->
        <section id="hero-animation">
            <div id="landingHero" class="section-py landing-hero position-relative">
                <img src="{{ asset('assets/img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background" class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100"
                    data-speed="1" />
                <div class="container">
                    <div class="hero-text-box text-center position-relative">
                        <h1 class="text-primary hero-title display-6 fw-extrabold">
                            لوحة تحكم واحدة لإدارة كل أعمالك
                        </h1>
                        <h2 class="hero-sub-title h6 mb-6">
                            منصة جاهزة وسهلة الاستخدام<br class="d-none d-lg-block" />
                            للموثوقية والتخصيص.
                        </h2>
                        <div class="landing-hero-btn d-inline-block position-relative">
                            <span class="hero-btn-item position-absolute d-none d-md-flex fw-medium">انضم إلينا
                                <img src="{{ asset('assets/img/front-pages/icons/Join-community-arrow.png') }}" alt="انضم إلينا" class="scaleX-n1-rtl" /></span>
                            <a href="#landingPricing" class="btn btn-primary btn-lg">ابدأ الآن</a>
                        </div>
                    </div>
                    <div id="heroDashboardAnimation" class="hero-animation-img">
                        <a href="../vertical-menu-template/app-ecommerce-dashboard.html" target="_blank">
                            <div id="heroAnimationImg" class="position-relative hero-dashboard-img">
                                <img src="{{ asset('assets/img/front-pages/landing-page/hero-dashboard-light.png') }}" alt="hero dashboard" class="animation-img"
                                    data-app-light-img="front-pages/landing-page/hero-dashboard-light.png" data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" />
                                <img src="{{ asset('assets/img/front-pages/landing-page/hero-elements-light.png') }}" alt="hero elements"
                                    class="position-absolute hero-elements-img animation-img top-0 start-0" data-app-light-img="front-pages/landing-page/hero-elements-light.png"
                                    data-app-dark-img="front-pages/landing-page/hero-elements-dark.png" />
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="landing-hero-blank"></div>
        </section>
        <!-- Hero: End -->

        <!-- Useful features: Start -->
        <section id="landingFeatures" class="section-py landing-features">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">مميزات مفيدة</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">كل ما تحتاجه
                        <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="شحن اللابتوب" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                    لبدء مشروعك القادم
                </h4>
                <p class="text-center mb-12">
                    ليست مجرد مجموعة أدوات، الباقة تتضمن تطبيقاً جاهزاً للنشر.
                </p>
                <div class="features-icon-wrapper row gx-0 gy-6 g-sm-12">
                    @forelse ($features as $feature)
                        <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                            <div class="mb-4 text-primary text-center">
                                @if ($feature->hasMedia('image'))
                                    <img src="{{ $feature->getFirstMediaUrl('image') }}" alt="{{ $feature->title }}" style="width: 64px; height: 64px; object-fit: contain;">
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-label-primary" style="width: 64px; height: 64px;">
                                        <i class="bx bx-star bx-lg text-primary"></i>
                                    </span>
                                @endif
                            </div>
                            <h5 class="mb-2">{{ $feature->title }}</h5>
                            <p class="features-icon-description">{{ $feature->description }}</p>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-body-secondary">
                            لا توجد مميزات لعرضها حالياً.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        <!-- Useful features: End -->

        <!-- Real customers reviews: Start -->
        <section id="landingReviews" class="section-py bg-body landing-reviews pb-0">
            <!-- What people say slider: Start -->
            <div class="container">
                <div class="row align-items-center gx-0 gy-4 g-lg-5 mb-5 pb-md-5">
                    <div class="col-md-6 col-lg-5 col-xl-3">
                        <div class="mb-4">
                            <span class="badge bg-label-primary">Real Customers Reviews</span>
                        </div>
                        <h4 class="mb-1">
                            <span class="position-relative fw-extrabold z-1">What people say
                                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="laptop charging"
                                    class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                            </span>
                        </h4>
                        <p class="mb-5 mb-md-12">
                            See what our customers have to<br class="d-none d-xl-block" />
                            say about their experience.
                        </p>
                        <div class="landing-reviews-btns">
                            <button id="reviews-previous-btn" class="btn btn-icon btn-label-primary reviews-btn me-3" type="button">
                                <i class="icon-base bx bx-chevron-left icon-md scaleX-n1-rtl"></i>
                            </button>
                            <button id="reviews-next-btn" class="btn btn-icon btn-label-primary reviews-btn" type="button">
                                <i class="icon-base bx bx-chevron-right icon-md scaleX-n1-rtl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-7 col-xl-9">
                        <div class="swiper-reviews-carousel overflow-hidden">
                            <div class="swiper" id="swiper-reviews">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="card h-100">
                                            <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                                                <div class="mb-4">
                                                    <img src="{{ asset('assets/img/front-pages/branding/logo-1.png') }}" alt="client logo" class="client-logo img-fluid" />
                                                </div>
                                                <p>
                                                    “Vuexy is hands down the most useful front end Bootstrap theme I've ever used. I can't wait
                                                    to use it again for my next project.”
                                                </p>
                                                <div class="text-warning mb-4">
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 avatar-sm">
                                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle" />
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Cecilia Payne</h6>
                                                        <p class="small text-body-secondary mb-0">CEO of Airbnb</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="card h-100">
                                            <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                                                <div class="mb-4">
                                                    <img src="{{ asset('assets/img/front-pages/branding/logo-2.png') }}" alt="client logo" class="client-logo img-fluid" />
                                                </div>
                                                <p>
                                                    “I've never used a theme as versatile and flexible as Vuexy. It's my go to for building
                                                    dashboard sites on almost any project.”
                                                </p>
                                                <div class="text-warning mb-4">
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 avatar-sm">
                                                        <img src="{{ asset('assets/img/avatars/2.png') }}" alt="Avatar" class="rounded-circle" />
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Eugenia Moore</h6>
                                                        <p class="small text-body-secondary mb-0">Founder of Hubspot</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="card h-100">
                                            <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                                                <div class="mb-4">
                                                    <img src="{{ asset('assets/img/front-pages/branding/logo-3.png') }}" alt="client logo" class="client-logo img-fluid" />
                                                </div>
                                                <p>
                                                    This template is really clean & well documented. The docs are really easy to understand and
                                                    it's always easy to find a screenshot from their website.
                                                </p>
                                                <div class="text-warning mb-4">
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 avatar-sm">
                                                        <img src="{{ asset('assets/img/avatars/3.png') }}" alt="Avatar" class="rounded-circle" />
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Curtis Fletcher</h6>
                                                        <p class="small text-body-secondary mb-0">Design Lead at Dribbble</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="card h-100">
                                            <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                                                <div class="mb-4">
                                                    <img src="{{ asset('assets/img/front-pages/branding/logo-4.png') }}" alt="client logo" class="client-logo img-fluid" />
                                                </div>
                                                <p>
                                                    All the requirements for developers have been taken into consideration, so I’m able to build
                                                    any interface I want.
                                                </p>
                                                <div class="text-warning mb-4">
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bx-star"></i>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 avatar-sm">
                                                        <img src="{{ asset('assets/img/avatars/4.png') }}" alt="Avatar" class="rounded-circle" />
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Sara Smith</h6>
                                                        <p class="small text-body-secondary mb-0">Founder of Continental</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="card h-100">
                                            <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                                                <div class="mb-4">
                                                    <img src="{{ asset('assets/img/front-pages/branding/logo-5.png') }}" alt="client logo" class="client-logo img-fluid" />
                                                </div>
                                                <p>
                                                    “I've never used a theme as versatile and flexible as Vuexy. It's my go to for building
                                                    dashboard sites on almost any project.”
                                                </p>
                                                <div class="text-warning mb-4">
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 avatar-sm">
                                                        <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" />
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Eugenia Moore</h6>
                                                        <p class="small text-body-secondary mb-0">Founder of Hubspot</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="card h-100">
                                            <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                                                <div class="mb-4">
                                                    <img src="{{ asset('assets/img/front-pages/branding/logo-6.png') }}" alt="client logo" class="client-logo img-fluid" />
                                                </div>
                                                <p>
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam nemo mollitia, ad eum
                                                    officia numquam nostrum repellendus consequuntur!
                                                </p>
                                                <div class="text-warning mb-4">
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bxs-star"></i>
                                                    <i class="icon-base bx bx-star"></i>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 avatar-sm">
                                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle" />
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Sara Smith</h6>
                                                        <p class="small text-body-secondary mb-0">Founder of Continental</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- What people say slider: End -->
            <hr class="m-0 mt-6 mt-md-12" />
            <!-- Logo slider: Start -->
            <div class="container">
                <div class="swiper-logo-carousel pt-8">
                    <div class="swiper" id="swiper-clients-logos">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ asset('assets/img/front-pages/branding/logo_1-light.png') }}" alt="client logo" class="client-logo"
                                    data-app-light-img="front-pages/branding/logo_1-light.png" data-app-dark-img="front-pages/branding/logo_1-dark.png" />
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('assets/img/front-pages/branding/logo_2-light.png') }}" alt="client logo" class="client-logo"
                                    data-app-light-img="front-pages/branding/logo_2-light.png" data-app-dark-img="front-pages/branding/logo_2-dark.png" />
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('assets/img/front-pages/branding/logo_3-light.png') }}" alt="client logo" class="client-logo"
                                    data-app-light-img="front-pages/branding/logo_3-light.png" data-app-dark-img="front-pages/branding/logo_3-dark.png" />
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('assets/img/front-pages/branding/logo_4-light.png') }}" alt="client logo" class="client-logo"
                                    data-app-light-img="front-pages/branding/logo_4-light.png" data-app-dark-img="front-pages/branding/logo_4-dark.png" />
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('assets/img/front-pages/branding/logo_5-light.png') }}" alt="client logo" class="client-logo"
                                    data-app-light-img="front-pages/branding/logo_5-light.png" data-app-dark-img="front-pages/branding/logo_5-dark.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Logo slider: End -->
        </section>
        <!-- Real customers reviews: End -->

        <!-- Our great team: Start -->
        <section id="landingTeam" class="section-py landing-team">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Our Great Team</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">Supported
                        <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="laptop charging"
                            class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                    by Real People
                </h4>
                <p class="text-center mb-md-11 pb-0 pb-xl-12">Who is behind these great-looking interfaces?</p>
                <div class="row gy-12 mt-2">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card mt-3 mt-lg-0 shadow-none">
                            <div class="bg-label-primary border border-bottom-0 border-primary-subtle position-relative team-image-box">
                                <img src="{{ asset('assets/img/front-pages/landing-page/team-member-1.png') }}" class="position-absolute card-img-position bottom-0 start-50" alt="human image" />
                            </div>
                            <div class="card-body border border-top-0 border-primary-subtle text-center py-5">
                                <h5 class="card-title mb-0">Sophie Gilbert</h5>
                                <p class="text-body-secondary mb-0">Project Manager</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card mt-3 mt-lg-0 shadow-none">
                            <div class="bg-label-info border border-bottom-0 border-info-subtle position-relative team-image-box">
                                <img src="{{ asset('assets/img/front-pages/landing-page/team-member-2.png') }}" class="position-absolute card-img-position bottom-0 start-50" alt="human image" />
                            </div>
                            <div class="card-body border border-top-0 border-info-subtle text-center py-5">
                                <h5 class="card-title mb-0">Paul Miles</h5>
                                <p class="text-body-secondary mb-0">UI Designer</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card mt-3 mt-lg-0 shadow-none">
                            <div class="bg-label-danger border border-bottom-0 border-danger-subtle position-relative team-image-box">
                                <img src="{{ asset('assets/img/front-pages/landing-page/team-member-3.png') }}" class="position-absolute card-img-position bottom-0 start-50" alt="human image" />
                            </div>
                            <div class="card-body border border-top-0 border-danger-subtle text-center py-5">
                                <h5 class="card-title mb-0">Nannie Ford</h5>
                                <p class="text-body-secondary mb-0">Development Lead</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card mt-3 mt-lg-0 shadow-none">
                            <div class="bg-label-success border border-bottom-0 border-success-subtle position-relative team-image-box">
                                <img src="{{ asset('assets/img/front-pages/landing-page/team-member-4.png') }}" class="position-absolute card-img-position bottom-0 start-50" alt="human image" />
                            </div>
                            <div class="card-body border border-top-0 border-success-subtle text-center py-5">
                                <h5 class="card-title mb-0">Chris Watkins</h5>
                                <p class="text-body-secondary mb-0">Marketing Manager</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Our great team: End -->

        <!-- Pricing plans: Start -->
        <section id="landingPricing" class="section-py bg-body landing-pricing">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">خطط الأسعار</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">خطط أسعار مصممة
                        <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="شحن اللابتوب" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                    خصيصاً لك
                </h4>
                <p class="text-center pb-2 mb-7">
                    جميع الخطط تتضمن أدوات ومميزات متقدمة لتعزيز منتجك.<br />اختر الخطة الأنسب لاحتياجاتك.
                </p>
                <div class="text-center mb-12">
                    <div class="position-relative d-inline-block pt-3 pt-md-0">
                        <label class="switch switch-sm switch-primary me-0">
                            <span class="switch-label fs-6 text-body me-3">دفع شهري</span>
                            <input type="checkbox" class="switch-input price-duration-toggler" checked />
                            <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                            </span>
                            <span class="switch-label fs-6 text-body ms-3">دفع سنوي</span>
                        </label>
                        <div class="pricing-plans-item position-absolute d-flex">
                            <img src="{{ asset('assets/img/front-pages/icons/pricing-plans-arrow.png') }}" alt="pricing plans arrow" class="scaleX-n1-rtl" />
                            <span class="fw-medium mt-2 ms-1"> وفر 25%</span>
                        </div>
                    </div>
                </div>
                <div class="row g-6 pt-lg-5">
                    @forelse ($packages as $index => $package)
                        <div class="col-xl-4 col-lg-6">
                            <div class="card {{ $index === 1 && $packages->count() > 2 ? 'border border-primary shadow-xl' : '' }}">
                                <div class="card-header">
                                    <div class="text-center">
                                        @if ($package->hasMedia('icon'))
                                            <img src="{{ $package->getFirstMediaUrl('icon') }}" alt="{{ $package->title }}" class="mb-8 pb-2" style="max-height: 60px; object-fit: contain;" />
                                        @else
                                            <span class="avatar avatar-xl mb-8 mx-auto d-flex align-items-center justify-content-center bg-label-primary rounded">
                                                <i class="bx bx-package icon-32px text-primary"></i>
                                            </span>
                                        @endif
                                        <h4 class="mb-0">{{ $package->title }}</h4>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="price-monthly h2 text-primary fw-extrabold mb-0">${{ number_format($package->monthly_price, 0) }}</span>
                                            <span class="price-yearly h2 text-primary fw-extrabold mb-0 d-none">${{ number_format($package->yearly_price / 12, 0) }}</span>
                                            <sub class="h6 text-body-secondary mb-n1 ms-1">/mo</sub>
                                        </div>
                                        <div class="position-relative pt-2">
                                            <div class="price-yearly text-body-secondary price-yearly-toggle d-none">$ {{ number_format($package->yearly_price, 0) }} / year</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled pricing-list">
                                        @forelse ($package->features ?? [] as $feature)
                                            @if ($feature)
                                                <li>
                                                    <h6 class="d-flex align-items-center mb-3">
                                                        <span class="badge badge-center rounded-pill {{ $index === 1 && $packages->count() > 2 ? 'bg-primary' : 'bg-label-primary' }} p-0 me-3"><i
                                                                class="icon-base bx bx-check icon-12px"></i></span>
                                                        {{ $feature }}
                                                    </h6>
                                                </li>
                                            @endif
                                        @empty
                                            <li class="text-body-secondary">لا توجد مميزات مدرجة</li>
                                        @endforelse
                                    </ul>
                                    <div class="d-grid mt-8">
                                        @auth
                                            <a href="{{ route('subscribe.page', $package) }}" class="btn {{ $index === 1 && $packages->count() > 2 ? 'btn-primary' : 'btn-label-primary' }}">ابدأ الآن</a>
                                        @else
                                            <a href="{{ route('auth.login') }}" class="btn {{ $index === 1 && $packages->count() > 2 ? 'btn-primary' : 'btn-label-primary' }}">ابدأ الآن</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-10">
                            <p class="text-body-secondary mb-0">لا توجد باقات متاحة حالياً.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        <!-- Pricing plans: End -->

        <!-- Fun facts: Start -->
        <section id="landingFunFacts" class="section-py landing-fun-facts">
            <div class="container">
                <div class="row gy-6">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card border border-primary shadow-none">
                            <div class="card-body text-center">
                                <div class="mb-4 text-primary">
                                    <svg width="64" height="65" viewBox="0 0 64 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.2"
                                            d="M10 44.4663V18.4663C10 17.4054 10.4214 16.388 11.1716 15.6379C11.9217 14.8877 12.9391 14.4663 14 14.4663H50C51.0609 14.4663 52.0783 14.8877 52.8284 15.6379C53.5786 16.388 54 17.4054 54 18.4663V44.4663H10Z"
                                            fill="currentColor" />
                                        <path
                                            d="M10 44.4663V18.4663C10 17.4054 10.4214 16.388 11.1716 15.6379C11.9217 14.8877 12.9391 14.4663 14 14.4663H50C51.0609 14.4663 52.0783 14.8877 52.8284 15.6379C53.5786 16.388 54 17.4054 54 18.4663V44.4663M36 22.4663H28M6 44.4663H58V48.4663C58 49.5272 57.5786 50.5446 56.8284 51.2947C56.0783 52.0449 55.0609 52.4663 54 52.4663H10C8.93913 52.4663 7.92172 52.0449 7.17157 51.2947C6.42143 50.5446 6 49.5272 6 48.4663V44.4663Z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h3 class="mb-0">7.1k+</h3>
                                <p class="fw-medium mb-0">
                                    Support Tickets<br />
                                    Resolved
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card border border-success shadow-none">
                            <div class="card-body text-center">
                                <div class="mb-4 text-success">
                                    <svg width="65" height="65" viewBox="0 0 65 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="User">
                                            <path id="Vector" opacity="0.2"
                                                d="M32.4999 8.52881C27.6437 8.52739 22.9012 9.99922 18.899 12.7499C14.8969 15.5005 11.8233 19.4006 10.0844 23.9348C8.34542 28.4691 8.02291 33.4242 9.15945 38.1456C10.296 42.867 12.8381 47.1326 16.4499 50.3788C17.9549 47.4151 20.2511 44.9261 23.0841 43.1875C25.917 41.4489 29.176 40.5287 32.4999 40.5288C30.5221 40.5288 28.5887 39.9423 26.9442 38.8435C25.2997 37.7447 24.018 36.1829 23.2611 34.3556C22.5043 32.5284 22.3062 30.5177 22.6921 28.5779C23.0779 26.6381 24.0303 24.8563 25.4289 23.4577C26.8274 22.0592 28.6092 21.1068 30.549 20.721C32.4888 20.3351 34.4995 20.5331 36.3268 21.29C38.154 22.0469 39.7158 23.3286 40.8146 24.9731C41.9135 26.6176 42.4999 28.551 42.4999 30.5288C42.4999 33.181 41.4464 35.7245 39.571 37.5999C37.6956 39.4752 35.1521 40.5288 32.4999 40.5288C35.8238 40.5287 39.0829 41.4489 41.9158 43.1875C44.7487 44.9261 47.045 47.4151 48.5499 50.3788C52.1618 47.1326 54.7039 42.867 55.8404 38.1456C56.977 33.4242 56.6545 28.4691 54.9155 23.9348C53.1766 19.4006 50.103 15.5005 46.1008 12.7499C42.0987 9.99922 37.3562 8.52739 32.4999 8.52881Z"
                                                fill="currentColor" />
                                            <path id="Vector_2"
                                                d="M32.5 40.5288C38.0228 40.5288 42.5 36.0517 42.5 30.5288C42.5 25.006 38.0228 20.5288 32.5 20.5288C26.9772 20.5288 22.5 25.006 22.5 30.5288C22.5 36.0517 26.9772 40.5288 32.5 40.5288ZM32.5 40.5288C29.1759 40.5288 25.9168 41.4477 23.0839 43.1866C20.2509 44.9255 17.9548 47.4149 16.45 50.3788M32.5 40.5288C35.8241 40.5288 39.0832 41.4477 41.9161 43.1866C44.7491 44.9255 47.0452 47.4149 48.55 50.3788M56.5 32.5288C56.5 45.7836 45.7548 56.5288 32.5 56.5288C19.2452 56.5288 8.5 45.7836 8.5 32.5288C8.5 19.274 19.2452 8.52881 32.5 8.52881C45.7548 8.52881 56.5 19.274 56.5 32.5288Z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </g>
                                    </svg>
                                </div>
                                <h3 class="mb-0">50k+</h3>
                                <p class="fw-medium mb-0">
                                    Join Creatives<br />
                                    Community
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card border border-info shadow-none">
                            <div class="card-body text-center">
                                <div class="mb-4 text-info">
                                    <svg width="65" height="65" viewBox="0 0 65 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.2" d="M46.5001 10.5288H32.5001L20.2251 26.5288L32.5001 56.5288L60.5001 26.5288L46.5001 10.5288Z" fill="currentColor" />
                                        <path d="M18.5 10.5288H46.5L60.5 26.5288L32.5 56.5288L4.5 26.5288L18.5 10.5288Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M33.2934 9.92012C33.1042 9.67343 32.8109 9.52881 32.5 9.52881C32.1891 9.52881 31.8958 9.67343 31.7066 9.92012L19.7318 25.5288H4.5C3.94772 25.5288 3.5 25.9765 3.5 26.5288C3.5 27.0811 3.94772 27.5288 4.5 27.5288H19.5537L31.5745 56.9075C31.7282 57.2833 32.094 57.5288 32.5 57.5288C32.906 57.5288 33.2718 57.2833 33.4255 56.9075L45.4463 27.5288H60.5C61.0523 27.5288 61.5 27.0811 61.5 26.5288C61.5 25.9765 61.0523 25.5288 60.5 25.5288H45.2682L33.2934 9.92012ZM42.7474 25.5288L32.5 12.1717L22.2526 25.5288H42.7474ZM21.7146 27.5288L32.5 53.8881L43.2854 27.5288H21.7146Z"
                                            fill="currentColor" />
                                    </svg>
                                </div>
                                <h3 class="mb-0">4.8/5</h3>
                                <p class="fw-medium mb-0">
                                    Highly Rated<br />
                                    Products
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card border border-warning shadow-none">
                            <div class="card-body text-center">
                                <div class="mb-4 text-warning">
                                    <svg width="65" height="65" viewBox="0 0 65 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.2"
                                            d="M14.125 50.9038C11.825 48.6038 13.35 43.7788 12.175 40.9538C11 38.1288 6.5 35.6538 6.5 32.5288C6.5 29.4038 10.95 27.0288 12.175 24.1038C13.4 21.1788 11.825 16.4538 14.125 14.1538C16.425 11.8538 21.25 13.3788 24.075 12.2038C26.9 11.0288 29.375 6.52881 32.5 6.52881C35.625 6.52881 38 10.9788 40.925 12.2038C43.85 13.4288 48.575 11.8538 50.875 14.1538C53.175 16.4538 51.65 21.2788 52.825 24.1038C54 26.9288 58.5 29.4038 58.5 32.5288C58.5 35.6538 54.05 38.0288 52.825 40.9538C51.6 43.8788 53.175 48.6038 50.875 50.9038C48.575 53.2038 43.75 51.6788 40.925 52.8538C38.1 54.0288 35.625 58.5288 32.5 58.5288C29.375 58.5288 27 54.0788 24.075 52.8538C21.15 51.6288 16.425 53.2038 14.125 50.9038Z"
                                            fill="currentColor" />
                                        <path
                                            d="M43.5 26.5288L28.825 40.5288L21.5 33.5288M14.125 50.9038C11.825 48.6038 13.35 43.7788 12.175 40.9538C11 38.1288 6.5 35.6538 6.5 32.5288C6.5 29.4038 10.95 27.0288 12.175 24.1038C13.4 21.1788 11.825 16.4538 14.125 14.1538C16.425 11.8538 21.25 13.3788 24.075 12.2038C26.9 11.0288 29.375 6.52881 32.5 6.52881C35.625 6.52881 38 10.9788 40.925 12.2038C43.85 13.4288 48.575 11.8538 50.875 14.1538C53.175 16.4538 51.65 21.2788 52.825 24.1038C54 26.9288 58.5 29.4038 58.5 32.5288C58.5 35.6538 54.05 38.0288 52.825 40.9538C51.6 43.8788 53.175 48.6038 50.875 50.9038C48.575 53.2038 43.75 51.6788 40.925 52.8538C38.1 54.0288 35.625 58.5288 32.5 58.5288C29.375 58.5288 27 54.0788 24.075 52.8538C21.15 51.6288 16.425 53.2038 14.125 50.9038Z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h3 class="mb-0">100%</h3>
                                <p class="fw-medium mb-0">
                                    Money Back<br />
                                    Guarantee
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Fun facts: End -->

        <!-- FAQ: Start -->
        <section id="landingFAQ" class="section-py bg-body landing-faq">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">FAQ</span>
                </div>
                <h4 class="text-center mb-1">
                    Frequently asked
                    <span class="position-relative fw-extrabold z-1">questions
                        <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="laptop charging"
                            class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                </h4>
                <p class="text-center mb-12 pb-md-4">
                    Browse through these FAQs to find answers to commonly asked questions.
                </p>
                <div class="row gy-12 align-items-center">
                    <div class="col-lg-5">
                        <div class="text-center">
                            <img src="{{ asset('assets/img/front-pages/landing-page/faq-boy-with-logos.png') }}" alt="faq boy with logos" class="faq-image" />
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="accordion" id="accordionExample">
                            @forelse ($faqs as $index => $faq)
                                <div class="card accordion-item {{ $index === 0 ? 'active' : '' }}">
                                    <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                        <button type="button" class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#accordion{{ $faq->id }}"
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="accordion{{ $faq->id }}">
                                            {{ $faq->question }}
                                        </button>
                                    </h2>
                                    <div id="accordion{{ $faq->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $faq->id }}"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-body-secondary">
                                    لا توجد أسئلة شائعة حالياً.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ: End -->

        <!-- CTA: Start -->
        <section id="landingCTA" class="section-py landing-cta position-relative p-lg-0 pb-0">
            <img src="{{ asset('assets/img/front-pages/backgrounds/cta-bg-light.png') }}" class="position-absolute bottom-0 end-0 scaleX-n1-rtl h-100 w-100 z-n1" alt="cta image"
                data-app-light-img="front-pages/backgrounds/cta-bg-light.png" data-app-dark-img="front-pages/backgrounds/cta-bg-dark.png" />
            <div class="container">
                <div class="row align-items-center gy-12">
                    <div class="col-lg-6 text-start text-sm-center text-lg-start">
                        <h3 class="cta-title text-primary fw-bold mb-1">Ready to Get Started?</h3>
                        <h5 class="text-body mb-8">Start your project with a 14-day free trial</h5>
                        <a href="payment-page.html" class="btn btn-lg btn-primary">Get Started</a>
                    </div>
                    <div class="col-lg-6 pt-lg-12 text-center text-lg-end">
                        <img src="{{ asset('assets/img/front-pages/landing-page/cta-dashboard.png') }}" alt="cta dashboard" class="img-fluid mt-lg-4" />
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA: End -->

        <!-- Contact Us: Start -->
        <section id="landingContact" class="section-py bg-body landing-contact">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Contact US</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">Let's work
                        <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="laptop charging"
                            class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                    together
                </h4>
                <p class="text-center mb-12 pb-md-4">Any question or remark? just write us a message</p>
                <div class="row g-6">
                    <div class="col-lg-5">
                        <div class="contact-img-box position-relative border p-2 h-100">
                            <img src="{{ asset('assets/img/front-pages/icons/contact-border.png') }}" alt="contact border"
                                class="contact-border-img position-absolute d-none d-lg-block scaleX-n1-rtl" />
                            <img src="{{ asset('assets/img/front-pages/landing-page/contact-customer-service.png') }}" alt="contact customer service" class="contact-img w-100 scaleX-n1-rtl" />
                            <div class="p-4 pb-2">
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-12 col-xl-6">
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-label-primary rounded p-1_5 me-3">
                                                <i class="icon-base bx bx-envelope icon-lg"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0">Email</p>
                                                <h6 class="mb-0">
                                                    <a href="mailto:example@gmail.com" class="text-heading">example@gmail.com</a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-12 col-xl-6">
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-label-success rounded p-1_5 me-3">
                                                <i class="icon-base bx bx-phone-call icon-lg"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0">Phone</p>
                                                <h6 class="mb-0"><a href="tel:+1234-568-963" class="text-heading">+1234 568 963</a></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="mb-2">إرسال رسالة</h4>
                                <p class="mb-6">
                                    إذا كنت ترغب في التحدث عن أي شيء متعلق بالدفع أو الحساب أو الترخيص أو التعاون أو لديك أسئلة حول المبيعات المسبقة، فأنت في المكان الصحيح.
                                </p>
                                @if (session('success'))
                                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                                @endif
                                <form action="{{ route('website.contact.store') }}" method="POST">
                                    @csrf
                                    <div class="row g-4">
                                        @guest
                                        <div class="col-md-6">
                                            <label class="form-label" for="contact-form-fullname">الاسم الكامل</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="contact-form-fullname" placeholder="الاسم الكامل" value="{{ old('name') }}" required />
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="contact-form-email">البريد الإلكتروني</label>
                                            <input type="email" name="email" id="contact-form-email" class="form-control @error('email') is-invalid @enderror" placeholder="البريد الإلكتروني" value="{{ old('email') }}" required />
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endguest
                                        <div class="col-12">
                                            <label class="form-label" for="contact-form-message">الرسالة</label>
                                            <textarea id="contact-form-message" name="message" class="form-control @error('message') is-invalid @enderror" rows="11" placeholder="اكتب رسالتك هنا..." required>{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">إرسال</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact Us: End -->
    </div>

    <!-- / Sections:End -->
@endsection
