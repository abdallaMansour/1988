@extends('dashboard.layouts.master')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @include('dashboard.partials.rich-text-editor-head')
    <style>
        #user_ids_select + .select2-container { width: 100% !important; }
        #recipients-wrap { display: none; }
        #recipients-wrap.is-visible { display: block; }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">إرسال إشعار</h4>
            <a href="{{ route('dashboard.user-notifications.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="notification-form" action="{{ route('dashboard.user-notifications.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block mb-2">المستلمون <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-4 mb-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="audience_all" name="audience" value="all" @checked(old('audience', 'all') === 'all') required>
                                <label class="form-check-label" for="audience_all">جميع المستخدمين</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="audience_selected" name="audience" value="selected" @checked(old('audience') === 'selected') required>
                                <label class="form-check-label" for="audience_selected">مستخدمون محددون</label>
                            </div>
                        </div>
                        @error('audience')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4" id="recipients-wrap">
                        <label for="user_ids_select" class="form-label">اختر المستخدمين <span class="text-danger">*</span></label>
                        <select id="user_ids_select" name="user_ids[]" class="form-select @error('user_ids') is-invalid @enderror" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(collect(old('user_ids', []))->contains($user->id))>
                                    {{ $user->name }} — {{ $user->investigator_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('user_ids.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">الرسالة <span class="text-danger">*</span></label>
                        <div id="notification_message_editor" class="dashboard-rich-text-editor @error('message') is-invalid @enderror"></div>
                        <input type="hidden" name="message" id="notification_message_input" value="{{ old('message') }}">
                        @error('message')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">إرسال الإشعار</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function ($) {
            'use strict';
            $(function () {
                var $wrap = $('#recipients-wrap');
                var $select = $('#user_ids_select');

                function toggleRecipients() {
                    var selected = $('input[name="audience"]:checked').val() === 'selected';
                    $wrap.toggleClass('is-visible', selected);
                    if (selected) {
                        $select.prop('required', true);
                    } else {
                        $select.prop('required', false).val(null).trigger('change');
                    }
                }

                $select.select2({
                    placeholder: 'اختر مستخدمًا أو أكثر',
                    allowClear: true,
                    dir: 'rtl',
                    width: '100%'
                });

                $('input[name="audience"]').on('change', toggleRecipients);
                toggleRecipients();
            });
        })(window.jQuery);
    </script>
    @include('dashboard.partials.rich-text-editor-scripts', [
        'editorId' => 'notification_message_editor',
        'hiddenInputId' => 'notification_message_input',
        'formId' => 'notification-form',
        'initialHtml' => old('message'),
        'placeholder' => 'اكتب نص الإشعار…',
    ])
@endsection
