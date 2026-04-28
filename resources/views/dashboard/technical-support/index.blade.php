@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">البريد - رسائل تواصل معنا</h4>
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
                        <th width="50">#</th>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الرسالة</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th width="100">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                    <tr>
                        <td>{{ $message->id }}</td>
                        <td><strong>{{ $message->user?->name ?? $message->name ?? '-' }}</strong></td>
                        <td>{{ $message->user?->email ?? $message->email ?? '-' }}</td>
                        <td>{{ Str::limit($message->message, 50) }}</td>
                        <td>
                            @if ($message->status === 'new')
                            <span class="badge bg-label-warning">جديد</span>
                            @elseif ($message->status === 'read')
                            <span class="badge bg-label-info">مقروء</span>
                            @else
                            <span class="badge bg-label-success">تم الرد</span>
                            @endif
                        </td>
                        <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('dashboard.technical-support.show', $message) }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-show me-1"></i> عرض
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-body-secondary">
                            لا توجد رسائل بعد.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($messages->hasPages())
        <div class="card-footer">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
