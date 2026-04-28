@extends('website.layouts.master')

@section('content')
<section class="section-py bg-body landing-faq">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-label-primary">FAQ</span>
        </div>
        <h4 class="text-center mb-1">
            الأسئلة
            <span class="position-relative fw-extrabold z-1">الشائعة
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="section title" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>
        <p class="text-center mb-12 pb-md-4">
            تصفح هذه الأسئلة الشائعة للعثور على إجابات للأسئلة المتكررة.
        </p>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="accordionFaq">
                    @forelse ($faqs as $index => $faq)
                    <div class="card accordion-item {{ $index === 0 ? 'active' : '' }}">
                        <h2 class="accordion-header" id="heading{{ $faq->id }}">
                            <button type="button" class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#accordion{{ $faq->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="accordion{{ $faq->id }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="accordion{{ $faq->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#accordionFaq">
                            <div class="accordion-body">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-body-secondary">
                        لا توجد أسئلة شائعة حالياً.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
