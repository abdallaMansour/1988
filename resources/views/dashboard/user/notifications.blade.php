@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">الإشعارات</h4>
            <p class="text-body-secondary mb-0">
                @if ($unreadCount > 0)
                    لديك {{ $unreadCount }} إشعار{{ $unreadCount > 1 ? 'ات' : '' }} غير مقروء{{ $unreadCount > 1 ? 'ة' : '' }}
                @else
                    جميع الإشعارات مقروءة
                @endif
            </p>
        </div>
        @if ($unreadCount > 0)
        <form action="{{ route('dashboard.user.notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-label-primary">تعليم الكل كمقروء</button>
        </form>
        @endif
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="list-group list-group-flush">
            @forelse ($notifications as $notification)
            <a href="{{ route('dashboard.user.notifications.show', $notification) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-start gap-3 py-3 {{ $notification->read_by_user ? '' : 'bg-label-primary border-start border-primary border-3' }}">
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="mb-0 text-truncate">{{ $notification->title }}</h6>
                        @unless ($notification->read_by_user)
                            <span class="badge bg-primary">جديد</span>
                        @endunless
                    </div>
                    <p class="text-body-secondary small mb-0">{{ $notification->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <i class="bx bx-chevron-left text-body-secondary flex-shrink-0"></i>
            </a>
            @empty
            <div class="card-body text-center py-5 text-body-secondary">
                <i class="bx bx-bell-off bx-lg mb-3 d-block"></i>
                لا توجد إشعارات حالياً.
            </div>
            @endforelse
        </div>
        @if ($notifications->hasPages())
        <div class="card-footer">{{ $notifications->links() }}</div>
        @endif
    </div>
</div>
@endsection
