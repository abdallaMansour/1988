@extends('website.layouts.master')

@section('content')
<section class="section-py landing-features">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-label-primary">آراؤكم تهمنا</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">التقييمات
                <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="" class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>
        <p class="text-center mb-10">
            شاهد تجارب المستخدمين، أو أضف تقييمك أدناه.
        </p>

        @if (session('success'))
        <div class="alert alert-success text-center mb-8" role="alert">{{ session('success') }}</div>
        @endif

        <div class="row justify-content-center mb-12">
            <div class="col-lg-8">
                <div class="card shadow-none border">
                    <div class="card-body p-4 p-md-6">
                        <h5 class="mb-4">أضف تقييمك</h5>
                        <form action="{{ route('website.ratings.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="rating-name" class="form-label">الاسم</label>
                                <input type="text" name="name" id="rating-name" value="{{ old('name', auth('web')->user()?->name ?? '') }}"
                                    class="form-control @error('name') is-invalid @enderror" required maxlength="255">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="rating-email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" id="rating-email" value="{{ old('email', auth('web')->user()?->email ?? '') }}"
                                    class="form-control @error('email') is-invalid @enderror" required maxlength="255">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="rating-description" class="form-label">التعليق</label>
                                <textarea name="description" id="rating-description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label d-block">التقييم (من 1 إلى 5)</label>
                                <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                    <option value="" disabled @selected(old('rating', '') === '')>اختر عدد النجوم</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" @selected((string) old('rating') === (string) $i)>{{ $i }} / 5</option>
                                    @endfor
                                </select>
                                @error('rating')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-6 text-center">تقييمات المستخدمين</h5>
        <div class="row gy-6">
            @forelse ($ratings as $rating)
            <div class="col-md-6">
                <div class="card h-100 shadow-none border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <strong class="text-heading">{{ $rating->name }}</strong>
                            <span class="text-warning flex-shrink-0" aria-hidden="true">
                                @for ($s = 1; $s <= 5; $s++)
                                <i class="bx {{ $s <= $rating->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                @endfor
                            </span>
                        </div>
                        <p class="mb-0 text-body-secondary">{{ $rating->description }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-8 text-body-secondary">
                لا توجد تقييمات بعد. كن أول من يشاركنا رأيه.
            </div>
            @endforelse
        </div>

        @if ($ratings->hasPages())
        <div class="d-flex justify-content-center mt-10">
            {{ $ratings->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
