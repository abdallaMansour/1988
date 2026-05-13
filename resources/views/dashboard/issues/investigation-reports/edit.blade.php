@extends('dashboard.layouts.master')

@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">تعديل محضر التحقيق — {{ $issue->title }}</h4>
            <a href="{{ route('dashboard.issues.investigation-reports.index', $issue) }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع لمحاضر التحقيق
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="investigation-report-edit-form" action="{{ route('dashboard.issues.investigation-reports.update', [$issue, $investigationReport]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $investigationReport->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="issue_hint_id" class="form-label">إختيار المتهم <span class="text-danger">*</span></label>
                        <select class="form-select @error('issue_hint_id') is-invalid @enderror" id="issue_hint_id" name="issue_hint_id" required>
                            <option value="">اختر المتهم</option>
                            @foreach ($suspects as $suspect)
                                <option value="{{ $suspect->id }}" @selected((string) old('issue_hint_id', $investigationReport->issue_hint_id) === (string) $suspect->id)>
                                    {{ $suspect->title ?: 'متهم #' . $suspect->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('issue_hint_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">المحضر <span class="text-danger">*</span></label>
                        <div class="dashboard-rich-editor-wrap @error('report') is-invalid @enderror">
                            <div id="investigation_report_editor"></div>
                        </div>
                        <input type="hidden" name="report" id="investigation_report_input" value="" required />
                        @error('report')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">تحديث المحضر</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    @include('dashboard.partials.rich-text-editor-scripts', [
        'editorId' => 'investigation_report_editor',
        'hiddenInputId' => 'investigation_report_input',
        'formId' => 'investigation-report-edit-form',
        'initialHtml' => old('report', $investigationReport->report ?? ''),
        'placeholder' => 'نص محضر التحقيق…',
    ])
@endsection
