@if ($dashboardShowUserNotifications ?? false)
<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <span class="position-relative">
            <i class="icon-base bx bx-bell icon-md"></i>
            @if (($dashboardUnreadNotificationsCount ?? 0) > 0)
                <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
            @endif
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end p-0">
        <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
                <h6 class="mb-0 me-auto">الإشعارات</h6>
                <div class="d-flex align-items-center h6 mb-0">
                    @if (($dashboardUnreadNotificationsCount ?? 0) > 0)
                        <span class="badge bg-label-primary me-2">{{ $dashboardUnreadNotificationsCount }} جديد</span>
                        <form action="{{ route('dashboard.user.notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-notifications-all p-2 border-0 bg-transparent" data-bs-toggle="tooltip" data-bs-placement="top" title="تعليم الكل كمقروء">
                                <i class="icon-base bx bx-envelope-open text-heading"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container">
            <ul class="list-group list-group-flush">
                @forelse ($dashboardNavbarNotifications ?? [] as $notification)
                    <li class="list-group-item list-group-item-action dropdown-notifications-item {{ $notification->read_by_user ? 'marked-as-read' : '' }}">
                        <a href="{{ route('dashboard.user.notifications.show', $notification) }}" class="d-flex text-body stretched-link">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="icon-base bx bx-bell"></i></span>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="small mb-0 text-truncate">{{ $notification->title }}</h6>
                                <small class="mb-1 d-block text-body text-truncate">{{ Str::limit(strip_tags($notification->message), 80) }}</small>
                                <small class="text-body-secondary">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="list-group-item text-center py-4 text-body-secondary small">
                        لا توجد إشعارات
                    </li>
                @endforelse
            </ul>
        </li>
        <li class="border-top">
            <div class="d-grid p-4">
                <a class="btn btn-primary btn-sm d-flex justify-content-center" href="{{ route('dashboard.user.notifications') }}">
                    <small class="align-middle">عرض كل الإشعارات</small>
                </a>
            </div>
        </li>
    </ul>
</li>
@endif
