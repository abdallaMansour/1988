@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4 class="mb-1">الأدوار</h4>
            <p class="text-body-secondary mb-0">إدارة أدوار الأدمن والصلاحيات المرتبطة بها</p>
        </div>
        @if(auth('admin')->user()->hasPermission('roles.create') || auth('admin')->user()->hasRole('super_admin'))
        <a href="{{ route('dashboard.roles.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> إضافة دور
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
                        <th>المعرّف</th>
                        <th>عدد الأدمن</th>
                        <th>عدد الصلاحيات</th>
                        <th width="120">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td><strong>{{ $role->name }}</strong></td>
                        <td><code>{{ $role->slug }}</code></td>
                        <td>{{ $role->admins_count }}</td>
                        <td>{{ $role->permissions_count }}</td>
                        <td>
                            @if(auth('admin')->user()->hasPermission('roles.edit') || auth('admin')->user()->hasRole('super_admin'))
                            @if($role->slug !== 'super_admin')
                            <a href="{{ route('dashboard.roles.edit', $role) }}" class="btn btn-sm btn-icon btn-label-primary" title="تعديل">
                                <i class="bx bx-edit"></i>
                            </a>
                            @else
                            <span class="badge bg-label-info">مدير النظام</span>
                            @endif
                            @endif
                            @if((auth('admin')->user()->hasPermission('roles.delete') || auth('admin')->user()->hasRole('super_admin')) && $role->slug !== 'super_admin')
                            <form action="{{ route('dashboard.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
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
                            لا توجد أدوار
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($roles->hasPages())
        <div class="card-footer">
            {{ $roles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
