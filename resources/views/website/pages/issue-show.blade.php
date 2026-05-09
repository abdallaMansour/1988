@extends('website.layouts.master')

@section('content')
<section class="section-py landing-features">
    <div class="container">
        <div class="mb-6">
            <a href="{{ route('website.issues') }}" class="text-body-secondary small">
                <i class="bx bx-chevron-right align-middle"></i> العودة إلى القضايا
            </a>
        </div>

        <div class="row gy-8 align-items-start">
            <div class="col-lg-5">
                @if ($issue->hasMedia('main_image'))
                <div class="ratio ratio-1x1 bg-label-secondary rounded border overflow-hidden">
                    <img src="{{ $issue->getFirstMediaUrl('main_image') }}" class="object-fit-cover w-100 h-100" alt="{{ $issue->title }}">
                </div>
                @else
                <div class="ratio ratio-1x1 bg-label-secondary rounded border d-flex align-items-center justify-content-center">
                    <i class="bx bx-briefcase-alt-2 bx-lg text-secondary"></i>
                </div>
                @endif
            </div>
            <div class="col-lg-7">
                <h4 class="mb-3">{{ $issue->title }}</h4>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    @if ($issue->is_linked_to_novel)
                    <span class="badge bg-label-info">مرتبط بالرواية</span>
                    @endif
                    @if ($issue->languages && count($issue->languages))
                        @foreach ($issue->languages as $lang)
                        <span class="badge bg-label-secondary">{{ $lang }}</span>
                        @endforeach
                    @endif
                </div>

                @if ($issue->purchase_price_before_discount > $issue->purchase_price_after_discount)
                <p class="mb-3 fs-5">
                    <span class="text-decoration-line-through text-body-secondary me-2">{{ number_format((float) $issue->purchase_price_before_discount, 2) }}</span>
                    <span class="fw-bold text-primary">{{ number_format((float) $issue->purchase_price_after_discount, 2) }}</span>
                    <span class="text-body-secondary">ر.س</span>
                </p>
                @else
                <p class="mb-3 fs-5 fw-bold text-primary">{{ number_format((float) $issue->purchase_price_after_discount, 2) }} <span class="text-body-secondary fw-normal fs-6">ر.س</span></p>
                @endif

                @if ($issue->is_related_to_another_issue && $issue->relatedIssue && $issue->relatedIssue->is_active)
                <p class="mb-6">
                    <a href="{{ route('website.issues.show', $issue->relatedIssue) }}" class="btn btn-sm btn-label-primary">
                        <i class="bx bx-link-external me-1"></i> قضية مرتبطة: {{ $issue->relatedIssue->title }}
                    </a>
                </p>
                @endif

                @if ($issue->details)
                <div class="border rounded p-4 bg-label-secondary bg-opacity-25">
                    <h6 class="mb-3">التفاصيل</h6>
                    <div class="text-body">{!! nl2br(e($issue->details)) !!}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
