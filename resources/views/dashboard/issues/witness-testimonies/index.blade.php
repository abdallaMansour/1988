@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">شهادة الشهود — {{ $issue->title }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.issues.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> رجوع للقضايا
                </a>
                <a href="{{ route('dashboard.issues.witness-testimonies.create', $issue) }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة شهادة
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="80">#</th>
                            <th>العنوان</th>
                            <th>الشاهد</th>
                            <th>المحضر</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($witnessTestimonies as $testimony)
                            <tr>
                                <td>{{ $testimony->id }}</td>
                                <td><strong>{{ $testimony->title }}</strong></td>
                                <td>{{ $testimony->witness?->title ?: '—' }}</td>
                                <td>{{ Str::limit(strip_tags($testimony->report), 80) }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('dashboard.issues.witness-testimonies.edit', [$issue, $testimony]) }}">
                                                <i class="bx bx-edit-alt me-2"></i> تعديل
                                            </a>
                                            <form action="{{ route('dashboard.issues.witness-testimonies.destroy', [$issue, $testimony]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف شهادة الشاهد؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-2"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-body-secondary">
                                    لا توجد شهادات شهود بعد.
                                    <a href="{{ route('dashboard.issues.witness-testimonies.create', $issue) }}">إضافة شهادة</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
