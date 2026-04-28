@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">روابط التطبيقات</h4>
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
            <form action="{{ route('dashboard.ios-and-android-app-link.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="ios_app_link" class="form-label">رابط التطبيق الأي أو إس (App Store)</label>
                    <input type="url" class="form-control @error('ios_app_link') is-invalid @enderror" id="ios_app_link" name="ios_app_link" value="{{ old('ios_app_link', $settings->ios_app_link) }}" placeholder="https://apps.apple.com/app/id1234567890">
                    @error('ios_app_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="android_app_link" class="form-label">رابط التطبيق الأندرويد (Google Play)</label>
                    <input type="url" class="form-control @error('android_app_link') is-invalid @enderror" id="android_app_link" name="android_app_link" value="{{ old('android_app_link', $settings->android_app_link) }}" placeholder="https://play.google.com/store/apps/details?id=com.example.app">
                    @error('android_app_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @endauth
        </div>
    </div>
</div>
@endsection
