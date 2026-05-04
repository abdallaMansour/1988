@php
    $countryValue = isset($countryValue) && $countryValue !== null && $countryValue !== ''
        ? strtoupper((string) $countryValue)
        : '';
@endphp
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
(function ($) {
    'use strict';
    if (!$ || typeof $.fn.select2 !== 'function') {
        console.error('Select2 غير متاح. تأكد من تحميل jQuery ثم select2.min.js');
        return;
    }
    $(function () {
        var selected = @json($countryValue);
        var countryDataUrl = 'https://raw.githubusercontent.com/mledoze/countries/master/countries.json';
        var $el = $('#country_select2');

        if (!$el.length) {
            return;
        }

        fetch(countryDataUrl)
            .then(function (response) { return response.json(); })
            .then(function (data) {
                $el.empty();
                $el.append(new Option('اختر الدولة', '', true, !selected));

                data.forEach(function (country) {
                    var ara = country.translations && country.translations.ara && country.translations.ara.common;
                    var countryName = ara || (country.name && country.name.common) || '';
                    var countryCode = (country.cca2 || '').toUpperCase();
                    if (!countryCode) {
                        return;
                    }
                    var opt = new Option(countryName, countryCode, false, countryCode === String(selected).toUpperCase());
                    $el.append(opt);
                });

                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.select2('destroy');
                }

                $el.select2({
                    placeholder: 'اختر الدولة',
                    allowClear: true,
                    dir: 'rtl',
                    width: '100%'
                });
            })
            .catch(function (err) {
                console.error('حدث خطأ أثناء تحميل قائمة الدول:', err);
                $el.empty().append(new Option('تعذر تحميل الدول', '', true, true)).prop('disabled', true);
            });
    });
})(window.jQuery);
</script>
