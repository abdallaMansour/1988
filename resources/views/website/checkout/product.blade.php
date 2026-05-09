@extends('website.layouts.master')

@section('content')
<section class="section-py">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0">شراء {{ $product->name }}</h4>
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
                            @if ($product->hasMedia('images'))
                            <img src="{{ $product->getFirstMediaUrl('images') }}" alt="{{ $product->name }}" class="rounded me-4" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                            <span class="avatar avatar-xl me-4 bg-label-primary rounded d-flex align-items-center justify-content-center">
                                <i class="bx bx-package icon-40px text-primary"></i>
                            </span>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $product->name }}</h5>
                                <p class="text-body-secondary mb-0">المبلغ: <strong>{{ number_format((float) $product->sale_price_after_discount, 2) }} {{ config('ziina.currency') }}</strong></p>
                            </div>
                        </div>

                        <form action="{{ route('website.checkout.product.pay', $product) }}" method="POST">
                            @csrf
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('website.products.show', $product) }}" class="btn btn-label-secondary">إلغاء</a>
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
