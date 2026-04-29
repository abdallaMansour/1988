@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">عن المؤلف</h4>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            @auth('admin')
            <form action="{{ route('dashboard.about-us.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="about_us" class="form-label">محتوى عن المؤلف</label>
                    <textarea class="form-control @error('about_us') is-invalid @enderror" id="about_us" name="about_us" rows="15">{{ old('about_us', $settings->about_us) }}</textarea>
                    @error('about_us')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @else
            <div class="prose">
                @if ($settings->about_us)
                    {!! nl2br(e($settings->about_us)) !!}
                @else
                    <p class="text-body-secondary mb-0">لم يتم إضافة محتوى عن المؤلف بعد.</p>
                @endif
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
