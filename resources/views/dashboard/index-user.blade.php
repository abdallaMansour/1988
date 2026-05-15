@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if ($media->hasMedia('dashboard_banner'))
    <div class="mb-6">
        <img src="{{ $media->getFirstMediaUrl('dashboard_banner') }}" alt="إعلان" class="w-100 rounded" style="max-height: 200px; object-fit: cover;">
    </div>
    @endif

    <div class="row">
        <div class="col-xxl-8 mb-6 order-0">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">مرحباً بك أيها المحقق {{ $user->investigator_name }}</h5>
                            <p class="mb-0 text-body-secondary">
                                تابع تقدمك في التحقيقات، مشترياتك، وإحصائياتك من هنا.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-start">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175" class="scaleX-n1-rtl" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <span class="d-block fw-medium mb-1 text-body-secondary">مستواك (الرانك)</span>
                            @if ($currentRank)
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    @if ($currentRank->hasMedia('image'))
                                        <img src="{{ $currentRank->getFirstMediaUrl('image') }}" alt="{{ $currentRank->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: contain;">
                                    @endif
                                    <h4 class="card-title mb-0">{{ $currentRank->name }}</h4>
                                </div>
                            @else
                                <h4 class="card-title mb-0">—</h4>
                                <small class="text-body-secondary mt-1">لم يُحدَّد رانك بعد</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="avatar flex-shrink-0 mb-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-store icon-lg"></i></span>
                            </div>
                            <p class="mb-1 text-body-secondary">المنتجات المشتراة</p>
                            <h4 class="card-title mb-0">{{ $stats['products_purchased'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($media->hasMedia('dashboard_banner_video'))
        <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <h5 class="card-title mb-0">إعلان</h5>
                </div>
                <div class="card-body p-0">
                    @if ($media->dashboardPromoIsVideo())
                        <video class="w-100" controls style="max-height: 360px; object-fit: cover;">
                            <source src="{{ $media->getFirstMediaUrl('dashboard_banner_video') }}" type="{{ $media->dashboardPromoMedia()?->mime_type }}">
                        </video>
                    @else
                        <img src="{{ $media->getFirstMediaUrl('dashboard_banner_video') }}" alt="إعلان" class="w-100" style="max-height: 360px; object-fit: cover;">
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
            <div class="row">
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="avatar flex-shrink-0 mb-3">
                                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-trophy icon-lg"></i></span>
                            </div>
                            <p class="mb-1 text-body-secondary">مرات الفوز</p>
                            <h4 class="card-title mb-0">{{ $stats['wins'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="avatar flex-shrink-0 mb-3">
                                <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-x-circle icon-lg"></i></span>
                            </div>
                            <p class="mb-1 text-body-secondary">مرات الخسارة</p>
                            <h4 class="card-title mb-0">{{ $stats['losses'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
