@extends('website.layouts.master')

@section('content')
<section class="section-py">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0">{{ ($isGift ?? false) ? 'إهداء الجريمة لصديق: '.$issue->title : 'شراء الجريمة: '.$issue->title }}</h4>
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
                            @if ($issue->hasMedia('main_image'))
                            <img src="{{ $issue->getFirstMediaUrl('main_image') }}" alt="{{ $issue->title }}" class="rounded me-4" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                            <span class="avatar avatar-xl me-4 bg-label-primary rounded d-flex align-items-center justify-content-center">
                                <i class="bx bx-briefcase-alt-2 icon-40px text-primary"></i>
                            </span>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $issue->title }}</h5>
                                <p class="text-body-secondary mb-0">المبلغ: <strong>{{ number_format((float) $issue->purchase_price_after_discount, 2) }} {{ config('ziina.currency') }}</strong></p>
                            </div>
                        </div>

                        <form action="{{ ($isGift ?? false) ? route('website.checkout.issue.gift.pay', $issue) : route('website.checkout.issue.pay', $issue) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="coupon_code" class="form-label">كوبون الخصم (اختياري)</label>
                                <input type="text" name="coupon_code" id="coupon_code" value="{{ old('coupon_code') }}" class="form-control @error('coupon_code') is-invalid @enderror" maxlength="191" autocomplete="off" placeholder="أدخل الكود إن وجد">
                                @error('coupon_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('website.issues.show', $issue) }}" class="btn btn-label-secondary">إلغاء</a>
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
