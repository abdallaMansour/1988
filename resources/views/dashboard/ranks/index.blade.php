@extends('dashboard.layouts.master')

@section('content')
    @php
        $admin = auth('admin')->user();
        $showActions = $admin && ($admin->roles()->count() === 0 || $admin->hasRole('super_admin') || $admin->hasPermission('ranks.manage'));
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">الرانكات</h4>
            @if ($showActions)
                <a href="{{ route('dashboard.ranks.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة رانك
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
                            <th width="80">الصورة</th>
                            <th width="50">#</th>
                            <th>الاسم</th>
                            <th>القضايا المحلولة</th>
                            <th width="100">الحالة</th>
                            @if ($showActions)
                                <th width="120">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ranks as $rank)
                            <tr>
                                <td>
                                    @if ($rank->hasMedia('image'))
                                        <img src="{{ $rank->getFirstMediaUrl('image') }}" alt="{{ $rank->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: contain;">
                                    @else
                                        <span class="avatar-initial rounded bg-label-secondary d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="bx bx-image"></i></span>
                                    @endif
                                </td>
                                <td>{{ $rank->id }}</td>
                                <td><strong>{{ $rank->name }}</strong></td>
                                <td>من {{ $rank->solved_issues_from }} إلى {{ $rank->solved_issues_to }}</td>
                                <td>
                                    @if ($rank->is_active)
                                        <span class="badge bg-label-success">نشط</span>
                                    @else
                                        <span class="badge bg-label-secondary">غير نشط</span>
                                    @endif
                                </td>
                                @if ($showActions)
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('dashboard.ranks.edit', $rank) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> تعديل
                                                </a>
                                                <form action="{{ route('dashboard.ranks.destroy', $rank) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟');">
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
                                <td colspan="{{ $showActions ? '6' : '5' }}" class="text-center py-5 text-body-secondary">
                                    لا توجد رانكات بعد.
                                    @if ($showActions)
                                        <a href="{{ route('dashboard.ranks.create') }}">إضافة رانك</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($ranks->hasPages())
                <div class="card-footer">
                    {{ $ranks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
