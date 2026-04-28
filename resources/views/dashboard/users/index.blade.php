@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">المستخدمين</h4>
        <p class="text-body-secondary mb-0">المستخدمون الذين أكملوا التحقق من البريد الإلكتروني والهاتف</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>تاريخ انتهاء الباقة</th>
                        <th>الحالة</th>
                        <th>تاريخ التسجيل</th>
                        <th width="140">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr class="{{ $user->isBanned() ? 'table-secondary' : '' }}">
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @php $activeSub = $user->subscriptions->first(); @endphp
                            @if($activeSub)
                                <span class="text-success">{{ $activeSub->expires_at->format('Y-m-d') }}</span>
                            @else
                                <span class="text-body-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            @if($user->isBanned())
                            <span class="badge bg-danger">محظور</span>
                            @else
                            <span class="badge bg-success">نشط</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if(auth('admin')->user()->hasPermission('users.edit') || auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty())
                            <a href="{{ route('dashboard.users.edit', $user) }}" class="btn btn-sm btn-icon btn-label-primary" title="تعديل">
                                <i class="bx bx-edit"></i>
                            </a>
                            @endif
                            @if(auth('admin')->user()->hasPermission('users.ban') || auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty())
                            @if($user->isBanned())
                            <form action="{{ route('dashboard.users.unban', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-icon btn-label-success" title="إلغاء الحظر">
                                    <i class="bx bx-check-circle"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('dashboard.users.ban', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حظر هذا المستخدم؟')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-icon btn-label-warning" title="حظر">
                                    <i class="bx bx-block"></i>
                                </button>
                            </form>
                            @endif
                            @endif
                            @if(auth('admin')->user()->hasPermission('users.delete') || auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty())
                            <form action="{{ route('dashboard.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-label-danger" title="حذف">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-body-secondary">
                            لا يوجد مستخدمون أكملوا التحقق بعد.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
