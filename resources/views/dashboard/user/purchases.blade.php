@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">قائمة المشتريات</h4>
        <p class="text-body-secondary mb-0">كل ما اشتريته بنجاح</p>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="100">النوع</th>
                        <th>البند</th>
                        <th width="140">المبلغ</th>
                        <th width="100">الخصم</th>
                        <th width="160">تاريخ الشراء</th>
                        <th width="100"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchases as $purchase)
                    @php
                        $item = $purchase->purchasable;
                        $isIssue = $item instanceof \App\Models\Issue;
                        $isProduct = $item instanceof \App\Models\Product;
                        $discount = (float) ($purchase->discount_amount ?? 0);
                    @endphp
                    <tr>
                        <td>
                            @if ($isIssue)
                            <span class="badge bg-label-info">جريمة</span>
                            @elseif ($isProduct)
                            <span class="badge bg-label-success">منتج</span>
                            @else
                            <span class="text-body-secondary">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($isIssue)
                            <strong>{{ $item->title }}</strong>
                            @elseif ($isProduct)
                            <strong>{{ $item->name }}</strong>
                            @else
                            <span class="text-body-secondary">غير متاح</span>
                            @endif
                        </td>
                        <td>
                            @if ($purchase->gift_from_user_id)
                            <span class="fw-medium text-success">هدية</span>
                            <small class="d-block text-body-secondary">من {{ $purchase->giftFrom?->name ?? 'مستخدم' }}</small>
                            @else
                            {{ number_format((float) $purchase->amount, 2) }} {{ $purchase->currency }}
                            @endif
                        </td>
                        <td>
                            @if ($purchase->gift_from_user_id)
                            <span class="text-body-secondary">—</span>
                            @elseif ($discount > 0)
                            {{ number_format($discount, 2) }} {{ $purchase->currency }}
                            @else
                            <span class="text-body-secondary">—</span>
                            @endif
                        </td>
                        <td>{{ $purchase->updated_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if ($isIssue && $item->is_active)
                            <a href="{{ route('website.issues.show', $item) }}" class="btn btn-sm btn-primary" target="_blank">عرض</a>
                            @elseif ($isProduct && $item->is_active)
                            <a href="{{ route('website.products.show', $item) }}" class="btn btn-sm btn-primary" target="_blank">عرض</a>
                            @else
                            <span class="text-body-secondary small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-body-secondary">
                            لم تقم بأي مشتريات بعد.
                            <span class="d-block mt-2">
                                <a href="{{ route('website.products') }}" class="me-3">تصفح المنتجات</a>
                                <a href="{{ route('website.issues') }}">تصفح الجرائم</a>
                            </span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($purchases->hasPages())
        <div class="card-footer">
            {{ $purchases->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
