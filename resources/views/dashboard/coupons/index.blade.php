@extends('dashboard.layouts.master')

@section('content')
    @php
        $admin = auth('admin')->user();
        $showActions = $admin && ($admin->roles()->count() === 0 || $admin->hasRole('super_admin') || $admin->hasPermission('coupons.manage'));
        $appliesLabels = \App\Models\Coupon::appliesToLabels();
        $discountLabels = \App\Models\Coupon::discountTypeLabels();
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">كوبونات الخصم</h4>
            @if ($showActions)
                <a href="{{ route('dashboard.coupons.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة كوبون
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>اسم الرمز</th>
                            <th>الرمز</th>
                            <th>نوع القيمة</th>
                            <th>قيمة الخصم</th>
                            <th>خاص بـ</th>
                            <th>من — إلى</th>
                            <th>الحدود</th>
                            <th width="90">الحالة</th>
                            @if ($showActions)
                                <th width="120">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td><strong>{{ $coupon->name }}</strong></td>
                                <td><code>{{ $coupon->code }}</code></td>
                                <td>{{ $discountLabels[$coupon->discount_type] ?? $coupon->discount_type }}</td>
                                <td>
                                    @if ($coupon->discount_type === \App\Models\Coupon::DISCOUNT_PERCENT)
                                        {{ number_format((float) $coupon->discount_value, 2) }}%
                                    @else
                                        {{ number_format((float) $coupon->discount_value, 2) }}
                                    @endif
                                </td>
                                <td>
                                    {{ $appliesLabels[$coupon->applies_to] ?? $coupon->applies_to }}
                                    @if ($coupon->applies_to === \App\Models\Coupon::APPLIES_SPECIFIC_PRODUCTS)
                                        <span class="small text-body-secondary d-block">({{ $coupon->products_count }} منتج)</span>
                                    @elseif ($coupon->applies_to === \App\Models\Coupon::APPLIES_SPECIFIC_ISSUES)
                                        <span class="small text-body-secondary d-block">({{ $coupon->issues_count }} قضية)</span>
                                    @endif
                                </td>
                                <td class="small">
                                    {{ $coupon->starts_at->format('Y-m-d H:i') }}<br>
                                    {{ $coupon->ends_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="small">
                                    كلي: {{ $coupon->total_usage_limit ?? '∞' }}<br>
                                    للمستخدم: {{ $coupon->per_user_usage_limit ?? '∞' }}
                                </td>
                                <td>
                                    @if ($coupon->is_active)
                                        <span class="badge bg-label-success">نشط</span>
                                    @else
                                        <span class="badge bg-label-secondary">متوقف</span>
                                    @endif
                                </td>
                                @if ($showActions)
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('dashboard.coupons.edit', $coupon) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> تعديل
                                                </a>
                                                <form action="{{ route('dashboard.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bx bx-trash me-2"></i> حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $showActions ? '10' : '9' }}" class="text-center py-5 text-body-secondary">
                                    لا توجد كوبونات بعد.
                                    @if ($showActions)
                                        <a href="{{ route('dashboard.coupons.create') }}">إضافة كوبون</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($coupons->hasPages())
                <div class="card-footer">
                    {{ $coupons->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
