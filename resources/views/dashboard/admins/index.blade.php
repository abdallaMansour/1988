@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4 class="mb-1">إدارة الأدمن</h4>
            <p class="text-body-secondary mb-0">إدارة حسابات الأدمن والأدوار والصلاحيات</p>
        </div>
        @if(auth('admin')->user()->hasPermission('admins.create') || auth('admin')->user()->hasRole('super_admin'))
        <a href="{{ route('dashboard.admins.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> إضافة أدمن
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
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
                        <th>الأدوار</th>
                        <th>تاريخ الإنشاء</th>
                        <th width="120">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                    <tr>
                        <td>{{ $admin->id }}</td>
                        <td><strong>{{ $admin->name }}</strong></td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @foreach($admin->roles as $role)
                            <span class="badge bg-label-primary me-1">{{ $role->name }}</span>
                            @endforeach
                            @if($admin->roles->isEmpty())
                            <span class="text-body-secondary">-</span>
                            @endif
                        </td>
                        <td>{{ $admin->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if(auth('admin')->user()->hasPermission('admins.edit') || auth('admin')->user()->hasRole('super_admin'))
                            <a href="{{ route('dashboard.admins.edit', $admin) }}" class="btn btn-sm btn-icon btn-label-primary" title="تعديل">
                                <i class="bx bx-edit"></i>
                            </a>
                            @endif
                            @if((auth('admin')->user()->hasPermission('admins.delete') || auth('admin')->user()->hasRole('super_admin')) && $admin->id !== auth('admin')->id())
                            <form action="{{ route('dashboard.admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الأدمن؟')">
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
                        <td colspan="6" class="text-center py-5 text-body-secondary">
                            لا يوجد أدمن
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($admins->hasPages())
        <div class="card-footer">
            {{ $admins->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
