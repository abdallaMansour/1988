@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">المنتجات</h4>
            @if (auth('admin')->check() && auth('admin')->user()->hasPermission('products.manage'))
                <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة منتج
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
                            <th width="70">الصورة</th>
                            <th>SKU</th>
                            <th>الاسم</th>
                            <th>سعر الشراء</th>
                            <th>قبل الخصم</th>
                            <th>بعد الخصم</th>
                            <th>الكمية</th>
                            <th>الحالة</th>
                            @if (auth('admin')->check() && auth('admin')->user()->hasPermission('products.manage'))
                                <th width="130">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    @if ($product->hasMedia('images'))
                                        <img src="{{ $product->getFirstMediaUrl('images') }}" alt="{{ $product->name }}" class="rounded" style="width: 44px; height: 44px; object-fit: cover;">
                                    @else
                                        <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-image"></i></span>
                                    @endif
                                </td>
                                <td><strong>{{ $product->sku }}</strong></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format((float) $product->purchase_price, 2) }}</td>
                                <td>{{ number_format((float) $product->sale_price_before_discount, 2) }}</td>
                                <td>{{ number_format((float) $product->sale_price_after_discount, 2) }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>
                                    @if ($product->is_active)
                                        <span class="badge bg-label-success">نشط</span>
                                    @else
                                        <span class="badge bg-label-secondary">غير نشط</span>
                                    @endif
                                </td>
                                @if (auth('admin')->check() && auth('admin')->user()->hasPermission('products.manage'))
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('dashboard.products.edit', $product) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> تعديل
                                                </a>
                                                <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟');">
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
                                <td colspan="{{ auth('admin')->check() && auth('admin')->user()->hasPermission('products.manage') ? '9' : '8' }}" class="text-center py-5 text-body-secondary">
                                    لا توجد منتجات بعد.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="card-footer">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
