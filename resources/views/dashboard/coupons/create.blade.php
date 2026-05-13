@extends('dashboard.layouts.master')

@section('content')
    @php
        $appliesLabels = \App\Models\Coupon::appliesToLabels();
        $discountLabels = \App\Models\Coupon::discountTypeLabels();
        $SP = \App\Models\Coupon::APPLIES_SPECIFIC_PRODUCTS;
        $SI = \App\Models\Coupon::APPLIES_SPECIFIC_ISSUES;
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">إضافة كوبون خصم</h4>
            <a href="{{ route('dashboard.coupons.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.coupons.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label">اسم الرمز <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code" class="form-label">الرمز <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required maxlength="100">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="discount_type" class="form-label">نوع الخصم <span class="text-danger">*</span></label>
                            <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required>
                                @foreach ($discountLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('discount_type', \App\Models\Coupon::DISCOUNT_PERCENT) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="discount_value" class="form-label">قيمة الخصم <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" required>
                            @error('discount_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">للنسبة: حتى 100. للمبلغ الثابت: أدخل المبلغ.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="total_usage_limit" class="form-label">العدد (حد الاستخدام الكلي)</label>
                            <input type="number" min="1" step="1" class="form-control @error('total_usage_limit') is-invalid @enderror" id="total_usage_limit" name="total_usage_limit" value="{{ old('total_usage_limit') }}" placeholder="اتركه فارغاً لعدم التحديد">
                            @error('total_usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="per_user_usage_limit" class="form-label">عدد مرات الاستخدام للمستخدم</label>
                            <input type="number" min="1" step="1" class="form-control @error('per_user_usage_limit') is-invalid @enderror" id="per_user_usage_limit" name="per_user_usage_limit" value="{{ old('per_user_usage_limit') }}" placeholder="اتركه فارغاً لعدم التحديد">
                            @error('per_user_usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="starts_at" class="form-label">تاريخ بدء الصلاحية <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required>
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="ends_at" class="form-label">تاريخ نهاية الصلاحية <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror" id="ends_at" name="ends_at" value="{{ old('ends_at') }}" required>
                            @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="applies_to" class="form-label">خاص بـ <span class="text-danger">*</span></label>
                        <select class="form-select @error('applies_to') is-invalid @enderror" id="applies_to" name="applies_to" required>
                            @foreach ($appliesLabels as $value => $label)
                                <option value="{{ $value }}" @selected(old('applies_to', \App\Models\Coupon::APPLIES_ALL) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('applies_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="section-specific-products" class="mb-4 d-none">
                        <label class="form-label">المنتجات المحددة <span class="text-danger">*</span></label>
                        <select name="product_ids[]" multiple class="form-select @error('product_ids') is-invalid @enderror @error('product_ids.*') is-invalid @enderror" style="min-height: 180px;">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(in_array($product->id, old('product_ids', [])))>{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
                        @error('product_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('product_ids.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">اضغط Ctrl أو Cmd لاختيار أكثر من منتج.</div>
                    </div>

                    <div id="section-specific-issues" class="mb-4 d-none">
                        <label class="form-label">الجرائم المحددة <span class="text-danger">*</span></label>
                        <select name="issue_ids[]" multiple class="form-select @error('issue_ids') is-invalid @enderror @error('issue_ids.*') is-invalid @enderror" style="min-height: 180px;">
                            @foreach ($issues as $issue)
                                <option value="{{ $issue->id }}" @selected(in_array($issue->id, old('issue_ids', [])))>{{ $issue->title }}</option>
                            @endforeach
                        </select>
                        @error('issue_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('issue_ids.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">اضغط Ctrl أو Cmd لاختيار أكثر من جريمة.</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @checked(old('is_active', true))>
                            <label class="form-check-label" for="is_active">نشط</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">حفظ</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const applies = document.getElementById('applies_to');
            const secP = document.getElementById('section-specific-products');
            const secI = document.getElementById('section-specific-issues');
            const SP = @json($SP);
            const SI = @json($SI);
            function toggle() {
                const v = applies.value;
                secP.classList.toggle('d-none', v !== SP);
                secI.classList.toggle('d-none', v !== SI);
            }
            applies.addEventListener('change', toggle);
            toggle();
        })();
    </script>
@endsection
