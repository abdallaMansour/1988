@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">إضافة دور</h4>
        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.roles.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label">اسم الدور <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="form-label">المعرّف (slug)</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="يتم إنشاؤه تلقائياً من الاسم إن تُرِك فارغاً">
                    @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">الصلاحيات</label>
                    <div class="border rounded p-3">
                        @foreach($permissions as $group => $items)
                        <div class="mb-3">
                            <strong class="d-block mb-2 text-primary">{{ $group }}</strong>
                            <div class="row g-2">
                                @foreach($items as $permission)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('permissions')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">إنشاء الدور</button>
            </form>
        </div>
    </div>
</div>
@endsection
