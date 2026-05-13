/**
 * Shared Quill setup for dashboard site settings and similar forms.
 */
'use strict';

(function () {
    const defaultToolbar = [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ indent: '-1' }, { indent: '+1' }],
        [{ align: [] }, { direction: 'rtl' }],
        ['link', 'blockquote', 'clean'],
    ];

    /**
     * @param {{ editorId: string, hiddenInputId: string, formId: string, initialHtml?: string, placeholder?: string }} options
     */
    function initDashboardRichTextEditor(options) {
        if (typeof Quill === 'undefined') {
            return;
        }
        const editorId = options.editorId;
        const hiddenInputId = options.hiddenInputId;
        const formId = options.formId;
        const initialHtml = options.initialHtml || '';
        const placeholder = options.placeholder || '';

        const selector = '#' + editorId;
        const el = document.querySelector(selector);
        if (!el) {
            return;
        }

        const quill = new Quill(selector, {
            theme: 'snow',
            modules: { toolbar: defaultToolbar },
            placeholder: placeholder,
        });

        if (initialHtml) {
            const delta = quill.clipboard.convert({ html: initialHtml });
            quill.setContents(delta, 'silent');
        }

        const form = document.getElementById(formId);
        const input = document.getElementById(hiddenInputId);
        if (form && input) {
            function syncHidden() {
                input.value = quill.root.innerHTML;
            }
            syncHidden();
            quill.on('text-change', syncHidden);
            form.addEventListener('submit', syncHidden);
        }
    }

    window.initDashboardRichTextEditor = initDashboardRichTextEditor;
})();
