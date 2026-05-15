@extends('website.layouts.master')

@section('content')
<section class="section-py landing-pricing">
    <div class="container">
        <div class="text-center mb-8">
            <span class="badge bg-label-primary">عن المؤلف</span>
        </div>
        <h4 class="text-center mb-8">
            <span class="position-relative fw-extrabold z-1">عن المؤلف
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body p-6">
                        @if ($settings->about_us)
                            <div class="content-body">{!! $settings->about_us !!}</div>
                        @else
                            <p class="text-body-secondary text-center mb-0">لم يتم إضافة محتوى عن المؤلف بعد.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
