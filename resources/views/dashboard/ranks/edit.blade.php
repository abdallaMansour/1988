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
                <form action="{{ route('dashboard.ranks.update', $rank) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $rank->name) }}" required maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <p class="form-label mb-1">النطاق الحالي</p>
                        <p class="text-body-secondary mb-0">
                            من {{ $rank->solved_issues_from }}
                            إلى
                            @if ($rank->isOpenEnded())
                                فأعلى
                            @else
                                {{ $rank->solved_issues_to }}
                            @endif
                        </p>
                    </div>

                    <div class="mb-4">
                        <label for="solved_issues_from" class="form-label">عدد الجرائم المحلولة — من <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('solved_issues_from') is-invalid @enderror" id="solved_issues_from" name="solved_issues_from" value="{{ old('solved_issues_from', $rank->solved_issues_from) }}" required>
                        <div class="form-text">يُحدَّث نهاية الرانك السابق تلقائياً إلى (البداية − 1)، ونهاية هذا الرانك حسب الرانك التالي إن وُجد.</div>
                        @error('solved_issues_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
