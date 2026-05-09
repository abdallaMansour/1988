@extends('website.layouts.master')

@section('content')
<section class="section-py landing-features">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-label-primary">مشترياتي</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">القضايا التي تم شراؤها
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>
        <p class="text-center mb-10">
            القضايا المتاحة لك بعد إتمام الدفع عبر زينه.
        </p>

        @if (session('success'))
        <div class="alert alert-success text-center mb-8" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger text-center mb-8" role="alert">{{ session('error') }}</div>
        @endif

        <div class="card shadow-none border">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>القضية</th>
                            <th width="140">المبلغ</th>
                            <th width="180">تاريخ الشراء</th>
                            <th width="120"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                        @php
                            $issue = $purchase->purchasable;
                        @endphp
                        <tr>
                            <td>
                                @if ($issue instanceof \App\Models\Issue)
                                <strong>{{ $issue->title }}</strong>
                                @else
                                <span class="text-body-secondary">—</span>
                                @endif
                            </td>
                            <td>{{ number_format((float) $purchase->amount, 2) }} {{ $purchase->currency }}</td>
                            <td>{{ $purchase->updated_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @if ($issue instanceof \App\Models\Issue && $issue->is_active)
                                <a href="{{ route('website.issues.show', $issue) }}" class="btn btn-sm btn-primary">عرض</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-body-secondary">
                                لم تقم بشراء أي قضية بعد.
                                <a href="{{ route('website.issues') }}" class="d-block mt-2">تصفح القضايا</a>
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
</section>
@endsection
