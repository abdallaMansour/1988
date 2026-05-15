@extends('website.layouts.master')

@section('content')
<section class="section-py" id="cart-page" data-currency="{{ $currency }}">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-6">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('website.landing-page') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active" aria-current="page">سلة المشتريات</li>
            </ol>
        </nav>

        @if (session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        @if ($lines->isEmpty())
        <div class="card">
            <div class="card-body text-center py-10">
                <i class="bx bx-cart bx-lg text-body-secondary mb-4 d-block"></i>
                <h5 class="mb-2">سلة المشتريات فارغة</h5>
                <p class="text-body-secondary mb-4">لم تضف أي منتجات أو جرائم بعد.</p>
                <a href="{{ route('website.products') }}" class="btn btn-primary">تصفح المنتجات</a>
            </div>
        </div>
        @else
        <div class="row g-6">
            <div class="col-lg-8 order-lg-1 order-2">
                <h4 class="mb-4 fw-bold">سلة المشتريات</h4>
                <div id="cart-lines">
                    @foreach ($lines as $line)
                    @php
                        $allocation = $pricing['line_allocations'][$line['key']] ?? [
                            'line_subtotal' => $line['line_subtotal'],
                            'discount_amount' => 0,
                            'final_amount' => $line['line_subtotal'],
                        ];
                        $lineTotal = $allocation['final_amount'];
                    @endphp
                    <div class="card mb-4 cart-line" data-key="{{ $line['key'] }}" data-type="{{ $line['type'] }}">
                        <div class="card-body">
                            <div class="row align-items-center g-4">
                                <div class="col-auto">
                                    @if ($line['image_url'])
                                    <img src="{{ $line['image_url'] }}" alt="{{ $line['name'] }}" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                    <span class="d-flex align-items-center justify-content-center rounded bg-label-primary" style="width: 100px; height: 100px;">
                                        <i class="bx {{ $line['type'] === 'product' ? 'bx-package' : 'bx-search-alt' }} icon-lg text-primary"></i>
                                    </span>
                                    @endif
                                </div>
                                <div class="col">
                                    <h6 class="mb-1 fw-bold">{{ $line['name'] }}</h6>
                                    <p class="text-body-secondary small mb-1">{{ number_format($line['unit_price'], 2) }} {{ $currency }}</p>
                                    @if ($line['details'])
                                    <p class="text-body-secondary small mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($line['details']), 80) }}</p>
                                    @endif
                                </div>
                                <div class="col-auto text-center">
                                    @if ($line['type'] === 'product')
                                    <div class="input-group input-group-sm cart-qty-group" style="width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary cart-qty-minus" data-key="{{ $line['key'] }}">−</button>
                                        <input type="number" class="form-control text-center cart-qty-input" value="{{ $line['quantity'] }}" min="1" max="99" readonly data-key="{{ $line['key'] }}">
                                        <button type="button" class="btn btn-outline-secondary cart-qty-plus" data-key="{{ $line['key'] }}">+</button>
                                    </div>
                                    @else
                                    <span class="text-body-secondary small">الكمية: 1</span>
                                    @endif
                                </div>
                                <div class="col-auto text-end">
                                    <p class="small text-body-secondary mb-1">المجموع:</p>
                                    <p class="fw-bold text-danger mb-2 cart-line-total">{{ number_format($lineTotal, 2) }} {{ $currency }}</p>
                                    <button type="button" class="btn btn-sm btn-danger rounded-circle cart-remove-btn" data-key="{{ $line['key'] }}" title="حذف" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="bx bx-x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4 order-lg-2 order-1">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-body p-5">
                        <h5 class="fw-bold mb-4">ملخص الطلب</h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-body-secondary">مجموع المنتجات (بدون ضريبة)</span>
                            <span class="fw-medium" id="cart-subtotal">{{ number_format($pricing['subtotal'] ?? 0, 2) }} {{ $currency }}</span>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small mb-2">هل لديك كود خصم</label>
                            <div class="input-group">
                                <input type="text" id="cart-coupon-input" class="form-control" placeholder="ادخل كود الخصم" value="{{ $couponCode }}" maxlength="191" autocomplete="off"
                                    @guest('web') disabled @endguest>
                                @auth('web')
                                <button type="button" class="btn btn-danger" id="cart-coupon-apply">تطبيق</button>
                                @else
                                <a href="{{ route('auth.login') }}" class="btn btn-outline-secondary">دخول</a>
                                @endauth
                            </div>
                            <div id="cart-coupon-error" class="text-danger small mt-1 {{ ($pricing['ok'] ?? true) || ! $couponCode ? 'd-none' : '' }}">
                                {{ $pricing['message'] ?? '' }}
                            </div>
                        </div>

                        @if (($pricing['discount_amount'] ?? 0) > 0)
                        <div class="d-flex justify-content-between mb-3 text-success" id="cart-discount-row">
                            <span>الخصم</span>
                            <span id="cart-discount">− {{ number_format($pricing['discount_amount'], 2) }} {{ $currency }}</span>
                        </div>
                        @else
                        <div class="d-flex justify-content-between mb-3 text-success d-none" id="cart-discount-row">
                            <span>الخصم</span>
                            <span id="cart-discount"></span>
                        </div>
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">الإجمالي</span>
                            <span class="fw-bold text-danger fs-5" id="cart-final">{{ number_format($pricing['final_amount'] ?? 0, 2) }} {{ $currency }}</span>
                        </div>

                        @auth('web')
                        <form action="{{ route('website.cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 btn-lg">اتمام الطلب</button>
                        </form>
                        @else
                        <a href="{{ route('auth.login') }}" class="btn btn-danger w-100 btn-lg">سجّل الدخول لإتمام الطلب</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
window.cartRoutes = {
    update: @json(url('/cart')),
    destroy: @json(url('/cart')),
    coupon: @json(route('website.cart.coupon')),
    csrf: @json(csrf_token()),
};
</script>
<script src="{{ asset('assets/js/cart.js') }}"></script>
@endpush
