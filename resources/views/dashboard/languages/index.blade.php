@extends('dashboard.layouts.master')

@section('content')
    @php
        $admin = auth('admin')->user();
        $showActions = $admin && ($admin->roles()->count() === 0 || $admin->hasRole('super_admin') || $admin->hasPermission('languages.manage'));
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">اللغات</h4>
            @if ($showActions)
                <a href="{{ route('dashboard.languages.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة لغة
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->has('language'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ $errors->first('language') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>إسم اللغه</th>
                            <th>الرمز</th>
                            <th>إسم اللغه بالإنجليزيه</th>
                            @if ($showActions)
                                <th width="120">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($languages as $language)
                            <tr>
                                <td>{{ $language->id }}</td>
                                <td><strong>{{ $language->name }}</strong></td>
                                <td><span class="badge bg-label-primary">{{ $language->code }}</span></td>
                                <td>{{ $language->english_name }}</td>
                                @if ($showActions)
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('dashboard.languages.edit', $language) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> تعديل
                                                </a>
                                                <form action="{{ route('dashboard.languages.destroy', $language) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟');">
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
                                <td colspan="{{ $showActions ? '5' : '4' }}" class="text-center py-5 text-body-secondary">
                                    لا توجد لغات بعد.
                                    @if ($showActions)
                                        <a href="{{ route('dashboard.languages.create') }}">إضافة لغة</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($languages->hasPages())
                <div class="card-footer">
                    {{ $languages->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
