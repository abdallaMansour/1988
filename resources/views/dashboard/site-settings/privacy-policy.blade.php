@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">سياسة الخصوصية</h4>
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
            <form action="{{ route('dashboard.privacy-policy.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="privacy_policy" class="form-label">محتوى سياسة الخصوصية</label>
                    <textarea class="form-control @error('privacy_policy') is-invalid @enderror" id="privacy_policy" name="privacy_policy" rows="15">{{ old('privacy_policy', $settings->privacy_policy) }}</textarea>
                    @error('privacy_policy')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @else
            <div class="prose">
                @if ($settings->privacy_policy)
                    {!! nl2br(e($settings->privacy_policy)) !!}
                @else
                    <p class="text-body-secondary mb-0">لم يتم إضافة محتوى سياسة الخصوصية بعد.</p>
                @endif
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
