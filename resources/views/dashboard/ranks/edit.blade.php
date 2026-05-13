@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">تعديل رانك</h4>
            <a href="{{ route('dashboard.ranks.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.ranks.update', $rank) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($rank->hasMedia('image'))
                        <div class="mb-4">
                            <p class="form-label mb-2">صورة المستوى الحالية</p>
                            <img src="{{ $rank->getFirstMediaUrl('image') }}" alt="{{ $rank->name }}" class="rounded border mb-2" style="max-height: 120px;">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="clear_image" name="clear_image" value="1">
                                <label class="form-check-label" for="clear_image">حذف الصورة الحالية</label>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="image" class="form-label">استبدال صورة المستوى</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $rank->name) }}" required maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="solved_issues_from" class="form-label">عدد الجرائم المحلولة — من <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="1" class="form-control @error('solved_issues_from') is-invalid @enderror" id="solved_issues_from" name="solved_issues_from" value="{{ old('solved_issues_from', $rank->solved_issues_from) }}" required>
                            @error('solved_issues_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="solved_issues_to" class="form-label">إلى <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="1" class="form-control @error('solved_issues_to') is-invalid @enderror" id="solved_issues_to" name="solved_issues_to" value="{{ old('solved_issues_to', $rank->solved_issues_to) }}" required>
                            @error('solved_issues_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $rank->is_active))>
                            <label class="form-check-label" for="is_active">نشط</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">تحديث</button>
                </form>
            </div>
        </div>
    </div>
@endsection
