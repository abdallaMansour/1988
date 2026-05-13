@extends('dashboard.layouts.master')

@auth('admin')
@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection
@endauth

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">سياسة الاستبدال والإرجاع</h4>
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
            <form id="return-replacement-policy-form" action="{{ route('dashboard.return-replacement-policy.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">محتوى سياسة الاستبدال والإرجاع</label>
                    <div class="dashboard-rich-editor-wrap @error('return_replacement_policy') is-invalid @enderror">
                        <div id="return_replacement_policy_editor"></div>
                    </div>
                    <input type="hidden" name="return_replacement_policy" id="return_replacement_policy_input" value="" />
                    @error('return_replacement_policy')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @else
            <div class="prose">
                @if ($settings->return_replacement_policy)
                    {!! $settings->return_replacement_policy !!}
                @else
                    <p class="text-body-secondary mb-0">لم يتم إضافة محتوى سياسة الاستبدال والإرجاع بعد.</p>
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
        'editorId' => 'return_replacement_policy_editor',
        'hiddenInputId' => 'return_replacement_policy_input',
        'formId' => 'return-replacement-policy-form',
        'initialHtml' => old('return_replacement_policy', $settings->return_replacement_policy ?? ''),
        'placeholder' => 'اكتب محتوى سياسة الاستبدال والإرجاع…',
    ])
@endsection
@endauth
