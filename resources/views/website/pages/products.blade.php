@extends('website.layouts.master')

@section('content')
<section class="section-py landing-features">
    <div class="container">
        @if (session('success'))
        <div class="alert alert-success text-center mb-8" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger text-center mb-8" role="alert">{{ session('error') }}</div>
        @endif

        <div class="text-center mb-4">
            <span class="badge bg-label-primary">المتجر</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">منتجاتنا
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>
        <p class="text-center mb-12">
            تصفح المنتجات المتوفرة حالياً.
        </p>

        <div class="row gy-6">
            @forelse ($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-none border">
                    @if ($product->hasMedia('images'))
                    <div class="ratio ratio-4x3 bg-label-secondary rounded-top overflow-hidden">
                        <img src="{{ $product->getFirstMediaUrl('images') }}" alt="{{ $product->name }}" class="object-fit-cover">
                    </div>
                    @else
                    <div class="ratio ratio-4x3 bg-label-secondary rounded-top d-flex align-items-center justify-content-center">
                        <i class="bx bx-package bx-lg text-secondary"></i>
                    </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">{{ $product->name }}</h5>
                        <p class="small text-body-secondary mb-2">رمز المنتج: {{ $product->sku }}</p>
                        @if ($product->sale_price_before_discount > $product->sale_price_after_discount)
                        <p class="mb-1">
                            <span class="text-decoration-line-through text-body-secondary me-2">{{ number_format((float) $product->sale_price_before_discount, 2) }}</span>
                            <span class="fw-semibold text-primary">{{ number_format((float) $product->sale_price_after_discount, 2) }}</span>
                            <span class="small text-body-secondary">ر.س</span>
                        </p>
                        @else
                        <p class="mb-1 fw-semibold text-primary">{{ number_format((float) $product->sale_price_after_discount, 2) }} <span class="small text-body-secondary fw-normal">ر.س</span></p>
                        @endif
                        <p class="small mb-3">
                            @if ($product->quantity > 0)
                            <span class="text-success">متوفر</span>
                            @else
                            <span class="text-danger">غير متوفر حالياً</span>
                            @endif
                        </p>
                        @if ($product->details)
                        <p class="features-icon-description small flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($product->details), 140) }}</p>
                        @endif
                        <div class="d-flex flex-wrap gap-2 mt-auto align-self-start">
                            <a href="{{ route('website.products.show', $product) }}" class="btn btn-sm btn-primary">التفاصيل</a>
                            @if ($product->quantity > 0)
                                @include('website.partials.add-to-cart-form', ['type' => 'product', 'id' => $product->id])
                                @auth('web')
                                <a href="{{ route('website.checkout.product', $product) }}" class="btn btn-sm btn-label-primary">شراء</a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-8 text-body-secondary">
                لا توجد منتجات لعرضها حالياً.
            </div>
            @endforelse
        </div>

        @if ($products->hasPages())
        <div class="d-flex justify-content-center mt-10">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
