@extends('dashboard.layouts.master')

@section('content')
    @php
        $admin = auth('admin')->user();
        $canManage = $admin && ($admin->roles()->count() === 0 || $admin->hasRole('super_admin') || $admin->hasPermission('notifications.manage'));
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="mb-1">{{ $notification->title }}</h4>
                <p class="text-body-secondary mb-0">
                    {{ $notification->created_at->format('Y-m-d H:i') }}
                    @if ($notification->admin)
                        — {{ $notification->admin->name }}
                    @endif
                </p>
            </div>
            <a href="{{ route('dashboard.user-notifications.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <p class="mb-2">
                    <span class="text-body-secondary">المستلمون:</span>
                    @if ($notification->send_to_all)
                        <span class="badge bg-label-primary">جميع المستخدمين</span>
                    @else
                        <span class="badge bg-label-info">{{ $notification->recipients->count() }} مستخدم</span>
                    @endif
                </p>
                <hr>
                <div class="notification-message-content">
                    {!! $notification->message !!}
                </div>
            </div>
        </div>

        @if (! $notification->send_to_all && $notification->recipients->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">المستخدمون المحددون</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>اسم المحقق</th>
                            <th>البريد الإلكتروني</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notification->recipients as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->investigator_name }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if ($canManage)
        <form action="{{ route('dashboard.user-notifications.destroy', $notification) }}" method="POST" class="mt-4" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-label-danger">حذف الإشعار</button>
        </form>
        @endif
    </div>
@endsection
