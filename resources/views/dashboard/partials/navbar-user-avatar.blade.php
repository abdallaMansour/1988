@php
    $avatarUrl = $dashboardAvatarUrl ?? asset('assets/img/avatars/1.png');
    $imgClass = $class ?? 'rounded-circle';
@endphp

<img src="{{ $avatarUrl }}" alt="{{ auth('admin')->user()?->name ?? auth('web')->user()?->name ?? 'مستخدم' }}" class="{{ $imgClass }}" style="width: 100%; height: 100%; object-fit: cover;" />
