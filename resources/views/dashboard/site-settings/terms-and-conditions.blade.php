@extends('dashboard.layouts.master')

@auth('admin')
@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection
@endauth

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">الشروط والأحكام</h4>
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
            <form id="terms-conditions-form" action="{{ route('dashboard.terms-and-conditions.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">محتوى الشروط والأحكام</label>
                    <div class="dashboard-rich-editor-wrap @error('terms_and_conditions') is-invalid @enderror">
                        <div id="terms_and_conditions_editor"></div>
                    </div>
                    <input type="hidden" name="terms_and_conditions" id="terms_and_conditions_input" value="" />
                    @error('terms_and_conditions')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @else
            <div class="prose">
                @if ($settings->terms_and_conditions)
                    {!! $settings->terms_and_conditions !!}
                @else
                    <p class="text-body-secondary mb-0">لم يتم إضافة محتوى الشروط والأحكام بعد.</p>
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
        'editorId' => 'terms_and_conditions_editor',
        'hiddenInputId' => 'terms_and_conditions_input',
        'formId' => 'terms-conditions-form',
        'initialHtml' => old('terms_and_conditions', $settings->terms_and_conditions ?? ''),
        'placeholder' => 'اكتب محتوى الشروط والأحكام…',
    ])
@endsection
@endauth
