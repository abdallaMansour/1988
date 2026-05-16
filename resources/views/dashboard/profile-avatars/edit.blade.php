@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">تعديل صورة بروفايل</h4>
            <a href="{{ route('dashboard.profile-avatars.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($profileAvatar->hasMedia('image'))
                    <div class="mb-4">
                        <p class="form-label mb-2">الصورة الحالية</p>
                        <img src="{{ $profileAvatar->getFirstMediaUrl('image') }}" alt="صورة بروفايل" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                @endif

                <form action="{{ route('dashboard.profile-avatars.update', $profileAvatar) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="image" class="form-label">استبدال الصورة</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">تحديث</button>
                </form>
            </div>
        </div>
    </div>
@endsection
