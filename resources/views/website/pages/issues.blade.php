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
            <span class="badge bg-label-primary">القضايا</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">قضايانا
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>
        <p class="text-center mb-12">
            تصفح القضايا المتاحة حالياً.
        </p>

        <div class="row gy-6">
            @forelse ($issues as $issue)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-none border">
                    @if ($issue->hasMedia('main_image'))
                    <div class="ratio ratio-4x3 bg-label-secondary rounded-top overflow-hidden">
                        <img src="{{ $issue->getFirstMediaUrl('main_image') }}" alt="{{ $issue->title }}" class="object-fit-cover">
                    </div>
                    @else
                    <div class="ratio ratio-4x3 bg-label-secondary rounded-top d-flex align-items-center justify-content-center">
                        <i class="bx bx-briefcase-alt-2 bx-lg text-secondary"></i>
                    </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">{{ $issue->title }}</h5>
                        @if ($issue->is_linked_to_novel)
                        <p class="small mb-2"><span class="badge bg-label-info">مرتبط بالرواية</span></p>
                        @endif
                        @if ($issue->purchase_price_before_discount > $issue->purchase_price_after_discount)
                        <p class="mb-1">
                            <span class="text-decoration-line-through text-body-secondary me-2">{{ number_format((float) $issue->purchase_price_before_discount, 2) }}</span>
                            <span class="fw-semibold text-primary">{{ number_format((float) $issue->purchase_price_after_discount, 2) }}</span>
                            <span class="small text-body-secondary">ر.س</span>
                        </p>
                        @else
                        <p class="mb-1 fw-semibold text-primary">{{ number_format((float) $issue->purchase_price_after_discount, 2) }} <span class="small text-body-secondary fw-normal">ر.س</span></p>
                        @endif
                        @if ($issue->languages && count($issue->languages))
                        <p class="small mb-3">
                            @foreach ($issue->languages as $lang)
                            <span class="badge bg-label-secondary me-1">{{ $lang }}</span>
                            @endforeach
                        </p>
                        @endif
                        @if ($issue->details)
                        <p class="features-icon-description small flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($issue->details), 140) }}</p>
                        @endif
                        <div class="d-flex flex-wrap gap-2 mt-auto align-self-start">
                            <a href="{{ route('website.issues.show', $issue) }}" class="btn btn-sm btn-primary">التفاصيل</a>
                            @auth('web')
                            <a href="{{ route('website.checkout.issue', $issue) }}" class="btn btn-sm btn-label-primary">شراء</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-8 text-body-secondary">
                لا توجد قضايا لعرضها حالياً.
            </div>
            @endforelse
        </div>

        @if ($issues->hasPages())
        <div class="d-flex justify-content-center mt-10">
            {{ $issues->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
