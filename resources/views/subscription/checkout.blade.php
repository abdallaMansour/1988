@extends('website.layouts.master')

@section('content')
<section class="section-py">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0">الإشتراك في {{ $package->title }}</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="d-flex align-items-center mb-6">
                            @if ($package->hasMedia('icon'))
                            <img src="{{ $package->getFirstMediaUrl('icon') }}" alt="{{ $package->title }}" class="rounded me-4" style="width: 80px; height: 80px; object-fit: contain;">
                            @else
                            <span class="avatar avatar-xl me-4 bg-label-primary rounded d-flex align-items-center justify-content-center">
                                <i class="bx bx-package icon-40px text-primary"></i>
                            </span>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $package->title }}</h5>
                                <p class="text-body-secondary mb-0">اختر فترة الفوترة</p>
                            </div>
                        </div>

                        <form action="{{ route('subscribe', $package) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="form-label fw-medium">فترة الفوترة</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check custom-option custom-option-basic">
                                            <label class="form-check-label custom-option-content" for="period-monthly">
                                                <span class="custom-option-header">
                                                    <span class="h6 mb-0">شهري</span>
                                                    <span class="badge bg-label-primary">{{ config('ziina.currency') }} {{ number_format($package->monthly_price, 2) }}/mo</span>
                                                </span>
                                            </label>
                                            <input name="period" class="form-check-input" type="radio" id="period-monthly" value="monthly" checked>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check custom-option custom-option-basic">
                                            <label class="form-check-label custom-option-content" for="period-yearly">
                                                <span class="custom-option-header">
                                                    <span class="h6 mb-0">سنوي</span>
                                                    <span class="badge bg-label-success">{{ config('ziina.currency') }} {{ number_format($package->yearly_price, 2) }}/year</span>
                                                </span>
                                            </label>
                                            <input name="period" class="form-check-input" type="radio" id="period-yearly" value="yearly">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <a href="{{ route('website.landing-page') }}#landingPricing" class="btn btn-label-secondary">إلغاء</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-lock-alt me-2"></i> الدفع عبر زينه
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
