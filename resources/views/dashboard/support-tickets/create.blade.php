@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">إنشاء تذكرة جديدة</h4>
        <a href="{{ route('dashboard.support-tickets.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.support-tickets.store') }}" method="POST">
                @csrf

                @auth('admin')
                <div class="mb-4">
                    <label for="user_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                        <option value="">اختر المستخدم</option>
                        @foreach (\App\Models\User::orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endauth

                <div class="mb-4">
                    <label for="subject" class="form-label">الموضوع <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="موضوع التذكرة" required>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label">الرسالة <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" placeholder="اكتب تفاصيل الشكوى أو الاستفسار..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-send me-1"></i> إرسال التذكرة
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
