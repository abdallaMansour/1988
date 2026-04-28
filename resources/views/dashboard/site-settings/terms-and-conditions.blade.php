@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">الشروط والأحكام</h4>
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
            <form action="{{ route('dashboard.terms-and-conditions.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="terms_and_conditions" class="form-label">محتوى الشروط والأحكام</label>
                    <textarea class="form-control @error('terms_and_conditions') is-invalid @enderror" id="terms_and_conditions" name="terms_and_conditions" rows="15">{{ old('terms_and_conditions', $settings->terms_and_conditions) }}</textarea>
                    @error('terms_and_conditions')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @else
            <div class="prose">
                @if ($settings->terms_and_conditions)
                    {!! nl2br(e($settings->terms_and_conditions)) !!}
                @else
                    <p class="text-body-secondary mb-0">لم يتم إضافة محتوى الشروط والأحكام بعد.</p>
                @endif
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
