@extends('dashboard.layouts.master')

@section('content')
    @php
        $admin = auth('admin')->user();
        $canManage = $admin && ($admin->roles()->count() === 0 || $admin->hasRole('super_admin') || $admin->hasPermission('profile-avatars.manage'));
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">صور البروفايل</h4>
            @if ($canManage)
                <a href="{{ route('dashboard.profile-avatars.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة صورة
                </a>
            @endif
        </div>

        @if (session('success'))
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
                            <th width="100">الصورة</th>
                            <th width="50">#</th>
                            <th>المستخدمون</th>
                            @if ($canManage)
                                <th width="120">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($profileAvatars as $profileAvatar)
                            <tr>
                                <td>
                                    @if ($profileAvatar->hasMedia('image'))
                                        <img src="{{ $profileAvatar->getFirstMediaUrl('image') }}" alt="صورة بروفايل" class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                                    @else
                                        <span class="avatar-initial rounded-circle bg-label-secondary d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px;"><i class="bx bx-user"></i></span>
                                    @endif
                                </td>
                                <td>{{ $profileAvatar->id }}</td>
                                <td>{{ $profileAvatar->users_count }}</td>
                                @if ($canManage)
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('dashboard.profile-avatars.edit', $profileAvatar) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> تعديل
                                                </a>
                                                <form action="{{ route('dashboard.profile-avatars.destroy', $profileAvatar) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bx bx-trash me-2"></i> حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canManage ? 4 : 3 }}" class="text-center py-5 text-body-secondary">
                                    لا توجد صور بروفايل بعد.
                                    @if ($canManage)
                                        <a href="{{ route('dashboard.profile-avatars.create') }}">إضافة صورة</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($profileAvatars->hasPages())
                <div class="card-footer">{{ $profileAvatars->links() }}</div>
            @endif
        </div>
    </div>
@endsection
