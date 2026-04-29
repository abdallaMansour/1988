@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">عن الروايه</h4>
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
            <form action="{{ route('dashboard.about-novel.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="novel_title" class="form-label">عنوان الروايه</label>
                    <input type="text" class="form-control @error('novel_title') is-invalid @enderror" id="novel_title" name="novel_title" value="{{ old('novel_title', $settings->novel_title) }}" placeholder="اكتب عنوان الرواية">
                    @error('novel_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="novel_image" class="form-label">صورة الروايه</label>
                    @if ($settings->hasMedia('novel_image'))
                    <div class="mb-2">
                        <img src="{{ $settings->getFirstMediaUrl('novel_image') }}" alt="Novel Image" class="img-fluid rounded border" style="max-height: 180px; object-fit: contain;">
                    </div>
                    @endif
                    <input type="file" class="form-control @error('novel_image') is-invalid @enderror" id="novel_image" name="novel_image" accept="image/*">
                    @error('novel_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="novel_description" class="form-label">وصف الروايه</label>
                    <textarea class="form-control @error('novel_description') is-invalid @enderror" id="novel_description" name="novel_description" rows="8">{{ old('novel_description', $settings->novel_description) }}</textarea>
                    @error('novel_description')
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
