@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">إضافة محضر تحقيق — {{ $issue->title }}</h4>
            <a href="{{ route('dashboard.issues.investigation-reports.index', $issue) }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع لمحاضر التحقيق
            </a>
        </div>

        @if ($suspects->isEmpty())
            <div class="alert alert-warning" role="alert">
                يجب إضافة متهم واحد على الأقل قبل إنشاء محضر تحقيق.
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.issues.investigation-reports.store', $issue) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="issue_hint_id" class="form-label">إختيار المتهم <span class="text-danger">*</span></label>
                        <select class="form-select @error('issue_hint_id') is-invalid @enderror" id="issue_hint_id" name="issue_hint_id" required>
                            <option value="">اختر المتهم</option>
                            @foreach ($suspects as $suspect)
                                <option value="{{ $suspect->id }}" @selected((string) old('issue_hint_id') === (string) $suspect->id)>
                                    {{ $suspect->title ?: 'متهم #' . $suspect->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('issue_hint_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="report" class="form-label">المحضر <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('report') is-invalid @enderror" id="report" name="report" rows="8" required>{{ old('report') }}</textarea>
                        @error('report')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" @disabled($suspects->isEmpty())>إضافة المحضر</button>
                </form>
            </div>
        </div>
    </div>
@endsection
