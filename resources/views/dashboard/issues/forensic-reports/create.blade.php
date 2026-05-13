@extends('dashboard.layouts.master')

@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">إضافة محضر طب شرعي — {{ $issue->title }}</h4>
            <a href="{{ route('dashboard.issues.forensic-reports.index', $issue) }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع للطب الشرعي
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="forensic-report-create-form" action="{{ route('dashboard.issues.forensic-reports.store', $issue) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">المحضر <span class="text-danger">*</span></label>
                        <div class="dashboard-rich-editor-wrap @error('report') is-invalid @enderror">
                            <div id="forensic_report_editor"></div>
                        </div>
                        <input type="hidden" name="report" id="forensic_report_input" value="" required />
                        @error('report')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">إضافة المحضر</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    @include('dashboard.partials.rich-text-editor-scripts', [
        'editorId' => 'forensic_report_editor',
        'hiddenInputId' => 'forensic_report_input',
        'formId' => 'forensic-report-create-form',
        'initialHtml' => old('report'),
        'placeholder' => 'نص محضر الطب الشرعي…',
    ])
@endsection
