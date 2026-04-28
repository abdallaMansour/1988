@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">الإشتراكات</h4>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select" style="width: auto;">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
                <button type="submit" class="btn btn-primary">تصفية</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>الباقة</th>
                        <th>المبلغ</th>
                        <th>الفترة</th>
                        <th>الحالة</th>
                        <th>تاريخ الانتهاء</th>
                        <th>تاريخ الإنشاء</th>
                        <th width="80">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscriptions as $subscription)
                    <tr>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">{{ $subscription->user->name }}</span>
                                <small class="text-body-secondary">{{ $subscription->user->email }}</small>
                            </div>
                        </td>
                        <td>{{ $subscription->package->title }}</td>
                        <td>{{ $subscription->currency }} {{ number_format($subscription->amount, 2) }}</td>
                        <td>
                            @php
                            $periodAr = $subscription->period === 'monthly' ? 'شهري' : 'سنوي';
                            @endphp
                            <span class="badge bg-label-info">{{ $periodAr }}</span>
                        </td>
                        <td>
                            @php
                            $statusClass = match($subscription->status) {
                                'active' => 'bg-label-success',
                                'pending' => 'bg-label-warning',
                                'expired' => 'bg-label-secondary',
                                'cancelled' => 'bg-label-danger',
                                default => 'bg-label-secondary'
                            };
                            $statusAr = match($subscription->status) {
                                'active' => 'نشط',
                                'pending' => 'قيد الانتظار',
                                'expired' => 'منتهي',
                                'cancelled' => 'ملغي',
                                default => $subscription->status
                            };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $statusAr }}</span>
                        </td>
                        <td>{{ $subscription->expires_at->format('Y-m-d') }}</td>
                        <td>{{ $subscription->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('dashboard.subscriptions.show', $subscription) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                <i class="bx bx-show"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-body-secondary">لا توجد إشتراكات بعد.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($subscriptions->hasPages())
        <div class="card-footer">
            {{ $subscriptions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
