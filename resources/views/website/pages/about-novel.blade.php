@extends('website.layouts.master')

@section('content')
<section class="section-py landing-pricing">
    <div class="container">
        <div class="text-center mb-8">
            <span class="badge bg-label-primary">الرواية</span>
        </div>
        <h4 class="text-center mb-8">
            <span class="position-relative fw-extrabold z-1">الرواية
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>
        @if ($settings->novel_title)
        <p class="text-center mb-6 fw-bold fs-5">{{ $settings->novel_title }}</p>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body p-6">
                        @if ($settings->hasMedia('novel_image'))
                        <div class="text-center mb-6">
                            <img src="{{ $settings->getFirstMediaUrl('novel_image') }}" alt="{{ $settings->novel_title }}" class="img-fluid rounded" style="max-height: 320px; object-fit: contain;">
                        </div>
                        @endif
                        @if ($settings->novel_description)
                            <div class="content-body">{!! $settings->novel_description !!}</div>
                        @else
                            <p class="text-body-secondary text-center mb-0">لم يتم إضافة محتوى الرواية بعد.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
