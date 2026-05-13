@extends('dashboard.layouts.master')

@auth('admin')
@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection
@endauth

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">عن المؤلف</h4>
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
            <form id="about-us-form" action="{{ route('dashboard.about-us.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">محتوى عن المؤلف</label>
                    <div class="dashboard-rich-editor-wrap @error('about_us') is-invalid @enderror">
                        <div id="about_us_editor"></div>
                    </div>
                    <input type="hidden" name="about_us" id="about_us_input" value="" />
                    @error('about_us')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @else
            <div class="prose">
                @if ($settings->about_us)
                    {!! $settings->about_us !!}
                @else
                    <p class="text-body-secondary mb-0">لم يتم إضافة محتوى عن المؤلف بعد.</p>
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
        'editorId' => 'about_us_editor',
        'hiddenInputId' => 'about_us_input',
        'formId' => 'about-us-form',
        'initialHtml' => old('about_us', $settings->about_us ?? ''),
        'placeholder' => 'اكتب محتوى عن المؤلف…',
    ])
@endsection
@endauth
