@php
    $countryValue = $countryValue ?? '';
@endphp
<div class="mb-4">
    <label for="country_select2" class="form-label">الدولة <span class="text-danger">*</span></label>
    <select id="country_select2" name="country" class="form-select @error('country') is-invalid @enderror" required>
        <option value="" disabled selected>جاري تحميل الدول...</option>
    </select>
    @error('country')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
