@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">تعديل محضر الطب الشرعي — {{ $issue->title }}</h4>
            <a href="{{ route('dashboard.issues.forensic-reports.index', $issue) }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع للطب الشرعي
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.issues.forensic-reports.update', [$issue, $forensicReport]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $forensicReport->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="report" class="form-label">المحضر <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('report') is-invalid @enderror" id="report" name="report" rows="8" required>{{ old('report', $forensicReport->report) }}</textarea>
                        @error('report')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">تحديث المحضر</button>
                </form>
            </div>
        </div>
    </div>
@endsection
