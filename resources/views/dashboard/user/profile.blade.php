@extends('dashboard.layouts.master')

@php
    $selectedAvatarId = old('profile_avatar_id', $user->profile_avatar_id);
    $selectedAvatar = $profileAvatars->firstWhere('id', (int) $selectedAvatarId) ?? $user->profileAvatar;
    $currentAvatarUrl = $selectedAvatar?->getFirstMediaUrl('image');
    $countryValue = old('country', $user->country);
    $accountType = old('account_type', $user->account_type ?? 'public');
@endphp

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    #country_select2 + .select2-container { width: 100% !important; }
    .profile-avatar-trigger {
        border-radius: 50%;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .profile-avatar-trigger:hover,
    .profile-avatar-trigger:focus {
        box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.2);
        transform: scale(1.02);
    }
    .profile-avatar-trigger__img {
        width: 96px;
        height: 96px;
        object-fit: cover;
    }
    .profile-avatar-trigger__placeholder {
        width: 96px;
        height: 96px;
    }
    .profile-avatar-trigger__badge {
        position: absolute;
        bottom: 0;
        inset-inline: 0;
        padding: 0.35rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.65));
        border-radius: 0 0 50% 50%;
    }
    .profile-avatar-picker {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(88px, 1fr));
        gap: 1rem;
        max-height: 360px;
        overflow-y: auto;
        padding: 0.25rem;
    }
    .profile-avatar-picker__item {
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 50%;
        padding: 3px;
        transition: border-color 0.15s ease, transform 0.15s ease;
        background: none;
    }
    .profile-avatar-picker__item:hover {
        border-color: rgba(var(--bs-primary-rgb), 0.45);
        transform: scale(1.05);
    }
    .profile-avatar-picker__item.is-selected {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.25);
    }
    .profile-avatar-picker__item img,
    .profile-avatar-picker__item .avatar-initial {
        width: 80px;
        height: 80px;
    }
</style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">الملف الشخصي</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label d-block mb-2">صورة البروفايل</label>
                        @if ($profileAvatars->isEmpty())
                            <p class="text-body-secondary mb-0">لا توجد صور متاحة حالياً. يرجى التواصل مع الإدارة.</p>
                        @else
                            <input type="hidden" name="profile_avatar_id" id="profile_avatar_id" value="{{ $selectedAvatarId }}">

                            <button type="button"
                                class="profile-avatar-trigger btn p-0 bg-transparent position-relative"
                                data-bs-toggle="modal"
                                data-bs-target="#avatarPickerModal"
                                aria-label="تغيير صورة البروفايل">
                                <span id="profileAvatarPreview" class="d-inline-block rounded-circle overflow-hidden">
                                    @if ($currentAvatarUrl)
                                        <img src="{{ $currentAvatarUrl }}" alt="صورتك الحالية" class="profile-avatar-trigger__img rounded-circle" id="profileAvatarPreviewImg">
                                    @else
                                        <span class="avatar-initial rounded-circle bg-label-secondary d-inline-flex align-items-center justify-content-center profile-avatar-trigger__placeholder" id="profileAvatarPreviewPlaceholder">
                                            <i class="bx bx-user bx-lg"></i>
                                        </span>
                                    @endif
                                </span>
                                <span class="profile-avatar-trigger__badge">تغيير الصورة</span>
                            </button>
                            <p class="form-text mt-2 mb-0">اضغط على صورتك لاختيار صورة جديدة من المعرض.</p>

                            <div class="modal fade" id="avatarPickerModal" tabindex="-1" aria-labelledby="avatarPickerModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="avatarPickerModalLabel">اختر صورة البروفايل</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="profile-avatar-picker" role="listbox" aria-label="صور البروفايل المتاحة">
                                                @foreach ($profileAvatars as $avatar)
                                                    @php $avatarUrl = $avatar->getFirstMediaUrl('image'); @endphp
                                                    <button type="button"
                                                        class="profile-avatar-picker__item {{ (int) $selectedAvatarId === $avatar->id ? 'is-selected' : '' }}"
                                                        data-avatar-id="{{ $avatar->id }}"
                                                        data-avatar-url="{{ $avatarUrl }}"
                                                        role="option"
                                                        aria-selected="{{ (int) $selectedAvatarId === $avatar->id ? 'true' : 'false' }}">
                                                        @if ($avatarUrl)
                                                            <img src="{{ $avatarUrl }}" alt="صورة {{ $avatar->id }}" class="rounded-circle">
                                                        @else
                                                            <span class="avatar-initial rounded-circle bg-label-secondary d-inline-flex align-items-center justify-content-center">
                                                                <i class="bx bx-user"></i>
                                                            </span>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @error('profile_avatar_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block mb-2">نوع الحساب</label>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="account_type_public" name="account_type" value="public" @checked($accountType === 'public') required>
                                <label class="form-check-label" for="account_type_public">عام</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="account_type_private" name="account_type" value="private" @checked($accountType === 'private') required>
                                <label class="form-check-label" for="account_type_private">خاص</label>
                            </div>
                        </div>
                        <p class="form-text mb-0">الحساب الخاص يُخفِي اسم المحقق عن غير الأصدقاء في نقابة المحققين.</p>
                        @error('account_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    @include('dashboard.availability-places.partials.country-select', ['countryValue' => $countryValue])

                    <div class="mb-4">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="investigator_name" class="form-label">اسم المحقق</label>
                        <input type="text" class="form-control @error('investigator_name') is-invalid @enderror" id="investigator_name" name="investigator_name" value="{{ old('investigator_name', $user->investigator_name) }}" required maxlength="255">
                        @error('investigator_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                        <div class="form-text">اترك الحقل فارغاً إن لم ترغب بتغيير كلمة المرور.</div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" @disabled($profileAvatars->isEmpty())>حفظ التغييرات</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
<script>
(function () {
    const hiddenInput = document.getElementById('profile_avatar_id');
    const preview = document.getElementById('profileAvatarPreview');
    const modalEl = document.getElementById('avatarPickerModal');
    if (!hiddenInput || !preview || !modalEl) return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const pickerItems = modalEl.querySelectorAll('.profile-avatar-picker__item');

    function updatePreview(url) {
        preview.innerHTML = '';
        if (url) {
            const img = document.createElement('img');
            img.src = url;
            img.alt = 'صورتك الحالية';
            img.className = 'profile-avatar-trigger__img rounded-circle';
            img.id = 'profileAvatarPreviewImg';
            preview.appendChild(img);
        } else {
            const span = document.createElement('span');
            span.className = 'avatar-initial rounded-circle bg-label-secondary d-inline-flex align-items-center justify-content-center profile-avatar-trigger__placeholder';
            span.id = 'profileAvatarPreviewPlaceholder';
            span.innerHTML = '<i class="bx bx-user bx-lg"></i>';
            preview.appendChild(span);
        }
    }

    pickerItems.forEach(function (item) {
        item.addEventListener('click', function () {
            const id = item.dataset.avatarId;
            const url = item.dataset.avatarUrl || '';

            hiddenInput.value = id;

            pickerItems.forEach(function (el) {
                el.classList.remove('is-selected');
                el.setAttribute('aria-selected', 'false');
            });
            item.classList.add('is-selected');
            item.setAttribute('aria-selected', 'true');

            updatePreview(url);
            modal.hide();
        });
    });
})();
</script>
@include('dashboard.availability-places.partials.country-select-init', ['countryValue' => $countryValue])
@endsection
