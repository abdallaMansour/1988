@php
    $status = $status ?? 'can_add';
    $friendship = $friendship ?? null;
@endphp

@if ($status === 'friend')
    <span class="badge bg-label-success">صديق</span>
@elseif ($status === 'pending_sent' && $friendship)
    <span class="badge bg-label-warning mb-2">طلب مرسل</span>
    <form action="{{ route('dashboard.user.friendships.cancel', $friendship) }}" method="POST" class="w-100">
        @csrf
        <button type="submit" class="btn btn-sm btn-label-danger w-100">إلغاء</button>
    </form>
@elseif ($status === 'pending_received' && $friendship)
    <form action="{{ route('dashboard.user.friendships.accept', $friendship) }}" method="POST" class="w-100 mb-2">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary w-100">قبول</button>
    </form>
    <form action="{{ route('dashboard.user.friendships.reject', $friendship) }}" method="POST" class="w-100">
        @csrf
        <button type="submit" class="btn btn-sm btn-label-secondary w-100">رفض</button>
    </form>
@else
    <form action="{{ route('dashboard.user.friendships.send', $investigator) }}" method="POST" class="w-100">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary w-100">
            <i class="bx bx-user-plus me-1"></i> إضافة صديق
        </button>
    </form>
@endif
