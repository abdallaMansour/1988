@extends('website.layouts.master')

@section('content')
<section class="section-py landing-features">
    <div class="container">
        @if (session('success'))
        <div class="alert alert-success text-center mb-6" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger text-center mb-6" role="alert">{{ session('error') }}</div>
        @endif

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

                <div class="mb-6 d-flex flex-wrap gap-2 align-items-center">
                    @auth('web')
                        @if ($ownsIssue)
                        <span class="badge bg-label-success py-2 px-3"><i class="bx bx-check me-1"></i> تم شراء هذه القضية — المحتوى الكامل أسفل الصفحة</span>
                        @else
                        <a href="{{ route('website.checkout.issue', $issue) }}" class="btn btn-primary btn-sm">شراء عبر زينه</a>
                        <a href="{{ route('website.checkout.issue.gift', $issue) }}" class="btn btn-label-primary btn-sm"><i class="bx bx-gift me-1"></i> اهديه لصديقك</a>
                        <a href="{{ route('website.my-purchases') }}" class="btn btn-label-secondary btn-sm">قسم المشتريات</a>
                        @endif
                    @else
                    <a href="{{ route('auth.login') }}" class="btn btn-outline-primary btn-sm">سجّل الدخول للشراء</a>
                    @endauth
                </div>

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
                    <div class="text-body">{!! $issue->details !!}</div>
                </div>
                @endif
            </div>
        </div>

        @if ($ownsIssue)
        <div class="mt-10 pt-10 border-top">
            <div class="text-center mb-8">
                <span class="badge bg-label-success mb-2">محتوى حصري</span>
                <h5 class="mb-0">فيديوهات القضية والمتهمين</h5>
            </div>

            @if ($issue->hasMedia('story_video'))
            <div class="card shadow-none border mb-8">
                <div class="card-body">
                    <h6 class="mb-3">فيديو القصة</h6>
                    <div class="ratio ratio-16x9 bg-dark rounded overflow-hidden">
                        <video src="{{ $issue->getFirstMediaUrl('story_video') }}" controls class="w-100 h-100 object-fit-contain" playsinline></video>
                    </div>
                </div>
            </div>
            @endif

            @php
                $evidenceMedia = $issue->getMedia('evidence');
            @endphp
            @if ($evidenceMedia->isNotEmpty())
            <div class="card shadow-none border mb-8">
                <div class="card-body">
                    <h6 class="mb-4">الأدلة</h6>
                    <div class="row g-4">
                        @foreach ($evidenceMedia as $media)
                        <div class="col-md-6 col-lg-4">
                            @if (str_starts_with((string) $media->mime_type, 'video'))
                            <div class="ratio ratio-16x9 rounded overflow-hidden bg-label-secondary">
                                <video src="{{ $media->getUrl() }}" controls class="w-100 h-100 object-fit-contain" playsinline></video>
                            </div>
                            @else
                            <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ $media->getUrl() }}" alt="" class="img-fluid rounded border w-100 object-fit-cover" style="max-height: 240px;">
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if ($issue->hasMedia('ending_video'))
            <div class="card shadow-none border mb-8">
                <div class="card-body">
                    <h6 class="mb-3">النهاية — الحل</h6>
                    <div class="ratio ratio-16x9 bg-dark rounded overflow-hidden">
                        <video src="{{ $issue->getFirstMediaUrl('ending_video') }}" controls class="w-100 h-100 object-fit-contain" playsinline></video>
                    </div>
                </div>
            </div>
            @endif

            @if ($issue->hints->isNotEmpty())
            <div class="card shadow-none border mb-0">
                <div class="card-body">
                    <h6 class="mb-4">المتهمين</h6>
                    <div class="row g-4">
                        @foreach ($issue->hints as $hint)
                        <div class="col-6 col-md-4 col-lg-3">
                            @if ($hint->hasMedia('image'))
                            <div class="border rounded overflow-hidden p-2 bg-label-secondary bg-opacity-25">
                                <img src="{{ $hint->getFirstMediaUrl('image') }}" alt="متهم {{ $loop->iteration }}" class="img-fluid rounded w-100 object-fit-cover" style="max-height: 200px;">
                            </div>
                            @if ($hint->title)
                            <p class="small fw-medium text-center mt-2 mb-0">{{ $hint->title }}</p>
                            @endif
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if (! $issue->hasMedia('story_video') && ! $issue->hasMedia('ending_video') && $evidenceMedia->isEmpty() && $issue->hints->isEmpty())
            <p class="text-center text-body-secondary mb-0">لا يوجد محتوى إضافي مرفوع لهذه القضية بعد.</p>
            @endif
        </div>
        @elseif ($hasPremiumAssets)
        <div class="alert alert-secondary mt-10 mb-0 text-center" role="alert">
            <strong>محتوى حصري</strong><br>
            تشمل هذه القضية فيديوهات وأدلة وقائمة المتهمين التي تظهر بعد إتمام الشراء عبر زينه.
            @guest('web')
            <div class="mt-3">
                <a href="{{ route('auth.login') }}" class="btn btn-sm btn-primary">سجّل الدخول ثم اشترِ القضية</a>
            </div>
            @else
            <div class="mt-3">
                <a href="{{ route('website.checkout.issue', $issue) }}" class="btn btn-sm btn-primary">شراء القضية</a>
            </div>
            @endguest
        </div>
        @endif
    </div>
</section>
@endsection
