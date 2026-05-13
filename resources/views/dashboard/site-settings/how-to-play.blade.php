@extends('dashboard.layouts.master')

@auth('admin')
@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection
@endauth

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">كيف تلعب</h4>
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
            <form id="how-to-play-form" action="{{ route('dashboard.how-to-play.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">محتوى صفحة كيف تلعب</label>
                    <div class="dashboard-rich-editor-wrap @error('how_to_play') is-invalid @enderror">
                        <div id="how_to_play_editor"></div>
                    </div>
                    <input type="hidden" name="how_to_play" id="how_to_play_input" value="" />
                    @error('how_to_play')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </form>
            @else
            <div class="prose">
                @if ($settings->how_to_play)
                    {!! $settings->how_to_play !!}
                @else
                    <p class="text-body-secondary mb-0">لم يتم إضافة محتوى صفحة كيف تلعب بعد.</p>
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
        'editorId' => 'how_to_play_editor',
        'hiddenInputId' => 'how_to_play_input',
        'formId' => 'how-to-play-form',
        'initialHtml' => old('how_to_play', $settings->how_to_play ?? ''),
        'placeholder' => 'اكتب محتوى كيف تلعب…',
    ])
@endsection
@endauth
