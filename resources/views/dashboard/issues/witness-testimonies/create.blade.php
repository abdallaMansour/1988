@extends('dashboard.layouts.master')

@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">إضافة شهادة شاهد — {{ $issue->title }}</h4>
            <a href="{{ route('dashboard.issues.witness-testimonies.index', $issue) }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع لشهادة الشهود
            </a>
        </div>

        @if ($witnesses->isEmpty())
            <div class="alert alert-warning" role="alert">
                يجب إضافة شاهد واحد على الأقل قبل إنشاء شهادة شاهد.
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form id="witness-testimony-create-form" action="{{ route('dashboard.issues.witness-testimonies.store', $issue) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="issue_witness_id" class="form-label">إختيار شاهد <span class="text-danger">*</span></label>
                        <select class="form-select @error('issue_witness_id') is-invalid @enderror" id="issue_witness_id" name="issue_witness_id" required>
                            <option value="">اختر الشاهد</option>
                            @foreach ($witnesses as $witness)
                                <option value="{{ $witness->id }}" @selected((string) old('issue_witness_id') === (string) $witness->id)>
                                    {{ $witness->title ?: 'شاهد #' . $witness->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('issue_witness_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">المحضر <span class="text-danger">*</span></label>
                        <div class="dashboard-rich-editor-wrap @error('report') is-invalid @enderror">
                            <div id="witness_testimony_report_editor"></div>
                        </div>
                        <input type="hidden" name="report" id="witness_testimony_report_input" value="" required />
                        @error('report')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" @disabled($witnesses->isEmpty())>إضافة الشهادة</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    @include('dashboard.partials.rich-text-editor-scripts', [
        'editorId' => 'witness_testimony_report_editor',
        'hiddenInputId' => 'witness_testimony_report_input',
        'formId' => 'witness-testimony-create-form',
        'initialHtml' => old('report'),
        'placeholder' => 'نص شهادة الشاهد…',
    ])
@endsection
