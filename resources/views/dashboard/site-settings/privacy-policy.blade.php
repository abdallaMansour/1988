@extends('dashboard.layouts.master')

@auth('admin')
@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection
@endauth

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">سياسة الخصوصية</h4>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            @auth('admin')
            <form id="privacy-policy-form" action="{{ route('dashboard.privacy-policy.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">محتوى سياسة الخصوصية</label>
                    <div class="dashboard-rich-editor-wrap @error('privacy_policy') is-invalid @enderror">
                        <div id="privacy_policy_editor"></div>
                    </div>
                    <input type="hidden" name="privacy_policy" id="privacy_policy_input" value="" />
                    @error('privacy_policy')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @else
            <div class="prose">
                @if ($settings->privacy_policy)
                    {!! $settings->privacy_policy !!}
                @else
                    <p class="text-body-secondary mb-0">لم يتم إضافة محتوى سياسة الخصوصية بعد.</p>
                @endif
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@auth('admin')
@section('page-js')
    @include('dashboard.partials.rich-text-editor-scripts', [
        'editorId' => 'privacy_policy_editor',
        'hiddenInputId' => 'privacy_policy_input',
        'formId' => 'privacy-policy-form',
        'initialHtml' => old('privacy_policy', $settings->privacy_policy ?? ''),
        'placeholder' => 'اكتب محتوى سياسة الخصوصية…',
    ])
@endsection
@endauth
