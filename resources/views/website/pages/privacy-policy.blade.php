@extends('website.layouts.master')

@section('content')
<section class="section-py landing-pricing">
    <div class="container">
        <div class="text-center mb-8">
            <span class="badge bg-label-primary">سياسة الخصوصية</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">سياسة الخصوصية
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="section title" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>
        <p class="text-center mb-8">نلتزم بحماية خصوصيتك وبياناتك الشخصية</p>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body p-6">
                        @if ($settings->privacy_policy)
                            <div class="content-body" style="white-space: pre-line;">{!! nl2br(e($settings->privacy_policy)) !!}</div>
                        @else
                            <p class="text-body-secondary text-center mb-0">لم يتم إضافة محتوى سياسة الخصوصية بعد.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
