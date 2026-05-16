@extends('dashboard.layouts.master')

@section('content')
    @php
        $admin = auth('admin')->user();
        $canManage = $admin && ($admin->roles()->count() === 0 || $admin->hasRole('super_admin') || $admin->hasPermission('notifications.manage'));
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="mb-1">الإشعارات</h4>
                <p class="text-body-secondary mb-0">إرسال وإدارة إشعارات المستخدمين</p>
            </div>
            @if ($canManage)
                <a href="{{ route('dashboard.user-notifications.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إرسال إشعار
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>العنوان</th>
                            <th width="140">المستلمون</th>
                            <th width="160">تاريخ الإرسال</th>
                            <th width="120">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notifications as $notification)
                            <tr>
                                <td>{{ $notification->id }}</td>
                                <td>
                                    <a href="{{ route('dashboard.user-notifications.show', $notification) }}" class="fw-medium">{{ $notification->title }}</a>
                                </td>
                                <td>
                                    @if ($notification->send_to_all)
                                        <span class="badge bg-label-primary">جميع المستخدمين</span>
                                    @else
                                        <span class="badge bg-label-info">{{ $notification->recipients_count }} مستخدم</span>
                                    @endif
                                </td>
                                <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('dashboard.user-notifications.show', $notification) }}" class="btn btn-sm btn-label-primary">عرض</a>
                                        @if ($canManage)
                                            <form action="{{ route('dashboard.user-notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-label-danger">حذف</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-body-secondary">
                                    لم يُرسل أي إشعار بعد.
                                    @if ($canManage)
                                        <a href="{{ route('dashboard.user-notifications.create') }}" class="d-block mt-2">إرسال إشعار</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($notifications->hasPages())
                <div class="card-footer">{{ $notifications->links() }}</div>
            @endif
        </div>
    </div>
@endsection
