@extends('dashboard.layouts.master')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        #country_select2 + .select2-container { width: 100% !important; }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">تعديل مكان توفر</h4>
            <a href="{{ route('dashboard.availability-places.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.availability-places.update', $availabilityPlace) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="image" class="form-label">الصورة</label>
                        @if ($availabilityPlace->hasMedia('image'))
                            <div class="mb-2">
                                <img src="{{ $availabilityPlace->getFirstMediaUrl('image') }}" alt="{{ $availabilityPlace->title }}" class="rounded" style="max-height: 80px;">
                                <small class="d-block text-body-secondary">الصورة الحالية. ارفع صورة جديدة للاستبدال.</small>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @include('dashboard.availability-places.partials.country-select', ['countryValue' => old('country', $availabilityPlace->country)])

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $availabilityPlace->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="url" class="form-label">الرابط <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $availabilityPlace->url) }}" placeholder="https://..." required>
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">الوصف <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $availabilityPlace->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">تحديث مكان التوفر</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    @include('dashboard.availability-places.partials.country-select-init', ['countryValue' => old('country', $availabilityPlace->country)])
@endsection
