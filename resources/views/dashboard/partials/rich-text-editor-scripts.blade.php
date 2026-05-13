@php
    $richEditorConfig = [
        'editorId' => $editorId,
        'hiddenInputId' => $hiddenInputId,
        'formId' => $formId,
        'initialHtml' => $initialHtml ?? '',
        'placeholder' => $placeholder ?? '',
    ];
@endphp
<script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
<script src="{{ asset('assets/js/dashboard-rich-text-editor.js') }}"></script>
<script>
    'use strict';
    initDashboardRichTextEditor(@json($richEditorConfig));
</script>
