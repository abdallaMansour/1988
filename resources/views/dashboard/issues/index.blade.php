@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">القضايا</h4>
            @if (auth('admin')->check() && auth('admin')->user()->hasPermission('issues.manage'))
                <a href="{{ route('dashboard.issues.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة قضية
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
                            <th width="70">الصورة</th>
                            <th>العنوان</th>
                            <th>قبل الخصم</th>
                            <th>بعد الخصم</th>
                            <th>مرتبطة بالرواية</th>
                            <th>الحالة</th>
                            <th>اللغات</th>
                            <th>مرتبطة بقضية</th>
                            @if (auth('admin')->check() && auth('admin')->user()->hasPermission('issues.manage'))
                                <th width="120">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($issues as $issue)
                            <tr>
                                <td>
                                    @if ($issue->hasMedia('main_image'))
                                        <img src="{{ $issue->getFirstMediaUrl('main_image') }}" alt="{{ $issue->title }}" class="rounded" style="width: 44px; height: 44px; object-fit: cover;">
                                    @else
                                        <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-image"></i></span>
                                    @endif
                                </td>
                                <td><strong>{{ $issue->title }}</strong></td>
                                <td>{{ number_format((float) $issue->purchase_price_before_discount, 2) }}</td>
                                <td>{{ number_format((float) $issue->purchase_price_after_discount, 2) }}</td>
                                <td>
                                    @if ($issue->is_linked_to_novel)
                                        <span class="badge bg-label-success">نعم</span>
                                    @else
                                        <span class="badge bg-label-secondary">لا</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($issue->is_active)
                                        <span class="badge bg-label-success">فعال</span>
                                    @else
                                        <span class="badge bg-label-danger">غير فعال</span>
                                    @endif
                                </td>
                                <td>{{ collect($issue->languages ?? [])->implode(', ') }}</td>
                                <td>{{ $issue->is_related_to_another_issue && $issue->relatedIssue ? $issue->relatedIssue->title : '—' }}</td>
                                @if (auth('admin')->check() && auth('admin')->user()->hasPermission('issues.manage'))
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.videos.edit', $issue) }}">
                                                    <i class="bx bx-video me-2"></i> قسم الفيديوهات
                                                </a>
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.hints.index', $issue) }}">
                                                    <i class="bx bx-image me-2"></i> المتهمين
                                                </a>
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.witnesses.index', $issue) }}">
                                                    <i class="bx bx-user-voice me-2"></i> الشهود
                                                </a>
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.evidences.index', $issue) }}">
                                                    <i class="bx bx-file me-2"></i> الأدله
                                                </a>
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.rounds.edit', $issue) }}">
                                                    <i class="bx bx-layer me-2"></i> الجولات
                                                </a>
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.investigation-reports.index', $issue) }}">
                                                    <i class="bx bx-notepad me-2"></i> محضر التحقيق
                                                </a>
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.forensic-reports.index', $issue) }}">
                                                    <i class="bx bx-plus-medical me-2"></i> الطب الشرعي
                                                </a>
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.witness-testimonies.index', $issue) }}">
                                                    <i class="bx bx-message-square-detail me-2"></i> شهادة الشهود
                                                </a>
                                                <a class="dropdown-item" href="{{ route('dashboard.issues.edit', $issue) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> تعديل
                                                </a>
                                                <form action="{{ route('dashboard.issues.destroy', $issue) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟');">
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
                                <td colspan="{{ auth('admin')->check() && auth('admin')->user()->hasPermission('issues.manage') ? '9' : '8' }}" class="text-center py-5 text-body-secondary">
                                    لا توجد قضايا بعد.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($issues->hasPages())
                <div class="card-footer">
                    {{ $issues->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
