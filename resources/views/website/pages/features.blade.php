@extends('website.layouts.master')

@section('content')
<section class="section-py landing-features">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-label-primary">مميزات مفيدة</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">كل ما تحتاجه
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="section title" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
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
            <div class="col-12 text-center py-8 text-body-secondary">
                لا توجد مميزات لعرضها حالياً.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
