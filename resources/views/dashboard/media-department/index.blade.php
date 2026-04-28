@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">القسم الإعلامي</h4>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @auth('admin')
    <form action="{{ route('dashboard.media-department.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-4 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bx bx-log-in-circle me-2"></i>
                            صورة صفحة تسجيل الدخول
                        </h5>
                        <p class="text-body-secondary small mb-3">تظهر في صفحة تسجيل الدخول للمستخدمين</p>
                        @if ($media->hasMedia('login_image'))
                        <div class="mb-3">
                            <img src="{{ $media->getFirstMediaUrl('login_image') }}" alt="Login" class="img-fluid rounded border" style="max-height: 150px; object-fit: contain;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('login_image') is-invalid @enderror" name="login_image" accept="image/*">
                        @error('login_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bx bx-user-plus me-2"></i>
                            صورة صفحة التسجيل
                        </h5>
                        <p class="text-body-secondary small mb-3">تظهر في صفحة إنشاء حساب جديد</p>
                        @if ($media->hasMedia('register_image'))
                        <div class="mb-3">
                            <img src="{{ $media->getFirstMediaUrl('register_image') }}" alt="Register" class="img-fluid rounded border" style="max-height: 150px; object-fit: contain;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('register_image') is-invalid @enderror" name="register_image" accept="image/*">
                        @error('register_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bx bx-purchase-tag me-2"></i>
                            الصورة الإعلانية
                        </h5>
                        <p class="text-body-secondary small mb-3">تظهر في الصفحة الرئيسية للوحة التحكم</p>
                        @if ($media->hasMedia('dashboard_banner'))
                        <div class="mb-3">
                            <img src="{{ $media->getFirstMediaUrl('dashboard_banner') }}" alt="Banner" class="img-fluid rounded border" style="max-height: 150px; object-fit: contain;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('dashboard_banner') is-invalid @enderror" name="dashboard_banner" accept="image/*">
                        @error('dashboard_banner')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
    </form>
    @else
    <div class="alert alert-info">لا يمكنك تعديل الصور. يرجى تسجيل الدخول كمسؤول.</div>
    @endauth
</div>
@endsection
