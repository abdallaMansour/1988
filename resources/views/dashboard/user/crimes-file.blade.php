@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">ملف الجرائم</h4>
            <p class="text-body-secondary mb-0">الجرائم التي اشتريتها ويمكنك التحقيق فيها</p>
        </div>
        <a href="{{ route('website.issues') }}" class="btn btn-label-primary" target="_blank">
            <i class="bx bx-search-alt me-1"></i> تصفح المزيد
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($purchases->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bx bx-folder-open bx-lg text-body-secondary mb-3 d-block"></i>
            <p class="text-body-secondary mb-3">لم تشتِ أي جريمة بعد.</p>
            <a href="{{ route('website.issues') }}" class="btn btn-primary" target="_blank">تصفح الجرائم</a>
        </div>
    </div>
    @else
    <div class="row g-4">
        @foreach ($purchases as $purchase)
        @php
            $issue = $purchase->purchasable;
        @endphp
        @continue(! $issue instanceof \App\Models\Issue)
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100">
                @if ($issue->hasMedia('main_image'))
                <div class="ratio ratio-16x9 bg-label-secondary overflow-hidden">
                    <img src="{{ $issue->getFirstMediaUrl('main_image') }}" alt="{{ $issue->title }}" class="object-fit-cover">
                </div>
                @else
                <div class="ratio ratio-16x9 bg-label-secondary d-flex align-items-center justify-content-center">
                    <i class="bx bx-briefcase-alt-2 bx-lg text-secondary"></i>
                </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2">{{ $issue->title }}</h5>
                    @if ($issue->crime_type)
                    <p class="mb-2"><span class="badge bg-label-warning">{{ $issue->crime_type }}</span></p>
                    @endif
                    @if ($issue->crime_year && $issue->crime_month)
                    <p class="small text-body-secondary mb-2">{{ $issue->crime_year }} / {{ $issue->crime_month }}</p>
                    @endif
                    @if ($purchase->gift_from_user_id)
                    <p class="small mb-2">
                        <span class="badge bg-label-success">هدية</span>
                        <span class="text-body-secondary">من {{ $purchase->giftFrom?->name ?? 'مستخدم' }}</span>
                    </p>
                    @endif
                    <p class="small text-body-secondary mb-3">تاريخ الشراء: {{ $purchase->updated_at->format('Y-m-d') }}</p>
                    <div class="mt-auto">
                        @if ($issue->is_active)
                        <a href="{{ route('website.issues.show', $issue) }}" class="btn btn-sm btn-primary w-100" target="_blank">
                            <i class="bx bx-play-circle me-1"></i> بدء التحقيق
                        </a>
                        @else
                        <span class="badge bg-label-secondary w-100 py-2">غير متاحة حالياً</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if ($purchases->hasPages())
    <div class="mt-4">{{ $purchases->links() }}</div>
    @endif
    @endif
</div>
@endsection
