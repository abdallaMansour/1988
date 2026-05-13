@extends('dashboard.layouts.master')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @include('dashboard.partials.rich-text-editor-head')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">إضافة جريمة</h4>
            <a href="{{ route('dashboard.issues.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="issue-create-form" action="{{ route('dashboard.issues.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="form-label">عنوان الجريمة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="main_image" class="form-label">الصورة الرئيسية</label>
                        <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image" accept="image/*">
                        @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="purchase_price_before_discount" class="form-label">سعر الشراء قبل الخصم <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('purchase_price_before_discount') is-invalid @enderror" id="purchase_price_before_discount" name="purchase_price_before_discount" value="{{ old('purchase_price_before_discount', 0) }}" required>
                            @error('purchase_price_before_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="purchase_price_after_discount" class="form-label">سعر الشراء بعد الخصم <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('purchase_price_after_discount') is-invalid @enderror" id="purchase_price_after_discount" name="purchase_price_after_discount" value="{{ old('purchase_price_after_discount', 0) }}" required>
                            @error('purchase_price_after_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="is_linked_to_novel" class="form-label">الجريمة مرتبطة بالرواية؟ <span class="text-danger">*</span></label>
                            <select class="form-select @error('is_linked_to_novel') is-invalid @enderror" id="is_linked_to_novel" name="is_linked_to_novel" required>
                                <option value="1" {{ old('is_linked_to_novel', '0') === '1' ? 'selected' : '' }}>نعم</option>
                                <option value="0" {{ old('is_linked_to_novel', '0') === '0' ? 'selected' : '' }}>لا</option>
                            </select>
                            @error('is_linked_to_novel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="is_active" class="form-label">الحالة <span class="text-danger">*</span></label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>غير فعال</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="languages" class="form-label">لغة الجريمة <span class="text-danger">*</span></label>
                        <select class="form-select @error('languages') is-invalid @enderror @error('languages.*') is-invalid @enderror" id="languages" name="languages[]" multiple required>
                            @foreach ($languagesOptions as $language)
                                <option value="{{ $language->code }}" {{ in_array($language->code, old('languages', []), true) ? 'selected' : '' }}>
                                    {{ $language->name }} ({{ strtoupper($language->code) }}) — {{ $language->english_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('languages')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('languages.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="is_related_to_another_issue" class="form-label">مرتبطة بجريمة ثانية؟ <span class="text-danger">*</span></label>
                            <select class="form-select @error('is_related_to_another_issue') is-invalid @enderror" id="is_related_to_another_issue" name="is_related_to_another_issue" required>
                                <option value="0" {{ old('is_related_to_another_issue', '0') === '0' ? 'selected' : '' }}>لا</option>
                                <option value="1" {{ old('is_related_to_another_issue') === '1' ? 'selected' : '' }}>نعم</option>
                            </select>
                            @error('is_related_to_another_issue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4" id="related-issue-wrapper" style="display: none;">
                            <label for="related_issue_id" class="form-label">اختر الجريمة المرتبطة <span class="text-danger">*</span></label>
                            <select class="form-select @error('related_issue_id') is-invalid @enderror" id="related_issue_id" name="related_issue_id">
                                <option value="">اختر جريمة</option>
                                @foreach ($relatedIssues as $relatedIssue)
                                    <option value="{{ $relatedIssue->id }}" {{ (string) old('related_issue_id') === (string) $relatedIssue->id ? 'selected' : '' }}>{{ $relatedIssue->title }}</option>
                                @endforeach
                            </select>
                            @error('related_issue_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">التفاصيل</label>
                        <div class="dashboard-rich-editor-wrap @error('details') is-invalid @enderror">
                            <div id="issue_details_editor"></div>
                        </div>
                        <input type="hidden" name="details" id="issue_details_input" value="" />
                        @error('details')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">حفظ الجريمة</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function ($) {
            $(function () {
                var $languages = $('#languages');
                var $toggle = $('#is_related_to_another_issue');
                var $wrapper = $('#related-issue-wrapper');
                var $relatedSelect = $('#related_issue_id');

                if ($languages.length && typeof $.fn.select2 === 'function') {
                    $languages.select2({
                        placeholder: 'اختر اللغات',
                        dir: 'rtl',
                        width: '100%'
                    });
                }

                function updateRelatedVisibility() {
                    var show = $toggle.val() === '1';
                    $wrapper.toggle(show);
                    $relatedSelect.prop('required', show);
                    if (!show) {
                        $relatedSelect.val('');
                    }
                }

                updateRelatedVisibility();
                $toggle.on('change', updateRelatedVisibility);
            });
        })(window.jQuery);
    </script>
    @include('dashboard.partials.rich-text-editor-scripts', [
        'editorId' => 'issue_details_editor',
        'hiddenInputId' => 'issue_details_input',
        'formId' => 'issue-create-form',
        'initialHtml' => old('details'),
        'placeholder' => 'تفاصيل الجريمة…',
    ])
@endsection
