@extends('website.layouts.master')

@section('content')
<section class="section-py landing-features">
    <div class="container">
        <div class="mb-6">
            <a href="{{ route('website.products') }}" class="text-body-secondary small">
                <i class="bx bx-chevron-right align-middle"></i> العودة إلى المنتجات
            </a>
        </div>

        <div class="row gy-8 align-items-start">
            <div class="col-lg-5">
                @php
                    $images = $product->getMedia('images');
                @endphp
                @if ($images->isNotEmpty())
                <div id="productCarousel" class="carousel slide rounded border overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($images as $index => $media)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="ratio ratio-1x1 bg-label-secondary">
                                <img src="{{ $media->getUrl() }}" class="d-block w-100 object-fit-cover" alt="{{ $product->name }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if ($images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">السابق</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">التالي</span>
                    </button>
                    @endif
                </div>
                @else
                <div class="ratio ratio-1x1 bg-label-secondary rounded border d-flex align-items-center justify-content-center">
                    <i class="bx bx-package bx-lg text-secondary"></i>
                </div>
                @endif
            </div>
            <div class="col-lg-7">
                <h4 class="mb-3">{{ $product->name }}</h4>
                <p class="text-body-secondary mb-4">رمز المنتج: <span class="text-heading">{{ $product->sku }}</span></p>

                @if ($product->sale_price_before_discount > $product->sale_price_after_discount)
                <p class="mb-3 fs-5">
                    <span class="text-decoration-line-through text-body-secondary me-2">{{ number_format((float) $product->sale_price_before_discount, 2) }}</span>
                    <span class="fw-bold text-primary">{{ number_format((float) $product->sale_price_after_discount, 2) }}</span>
                    <span class="text-body-secondary">ر.س</span>
                </p>
                @else
                <p class="mb-3 fs-5 fw-bold text-primary">{{ number_format((float) $product->sale_price_after_discount, 2) }} <span class="text-body-secondary fw-normal fs-6">ر.س</span></p>
                @endif

                <p class="mb-6">
                    @if ($product->quantity > 0)
                    <span class="badge bg-label-success">متوفر</span>
                    @else
                    <span class="badge bg-label-danger">غير متوفر حالياً</span>
                    @endif
                </p>

                @if ($product->details)
                <div class="border rounded p-4 bg-label-secondary bg-opacity-25">
                    <h6 class="mb-3">التفاصيل</h6>
                    <div class="text-body">{!! nl2br(e($product->details)) !!}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
