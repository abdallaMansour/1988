@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">عرض الرسالة</h4>
            <a href="{{ route('dashboard.technical-support.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $contactMessage->user?->name ?? ($contactMessage->name ?? 'مستخدم') }}</h5>
                            <small class="text-body-secondary">{{ $contactMessage->user?->email ?? ($contactMessage->email ?? '-') }}</small>
                        </div>
                        <div>
                            <span class="badge @if ($contactMessage->status === 'new') bg-label-warning @elseif($contactMessage->status === 'read') bg-label-info @else bg-label-success @endif">
                                @if ($contactMessage->status === 'new')
                                    جديد
                                @elseif ($contactMessage->status === 'read')
                                    مقروء
                                @else
                                    تم الرد
                                @endif
                            </span>
                            <small class="text-body-secondary ms-2">{{ $contactMessage->created_at->format('Y-m-d H:i') }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="white-space: pre-line;">{{ $contactMessage->message }}</p>
                    </div>
                </div>

                @if ($contactMessage->admin_reply)
                    <div class="card mb-4 border-start border-primary border-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bx bx-reply me-2"></i> رد الإدارة
                                @if ($contactMessage->repliedBy)
                                    <small class="text-body-secondary">- {{ $contactMessage->repliedBy->name }}</small>
                                @endif
                                <small class="text-body-secondary">({{ $contactMessage->replied_at?->format('Y-m-d H:i') }})</small>
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0" style="white-space: pre-line;">{{ $contactMessage->admin_reply }}</p>
                        </div>
                    </div>
                @endif

                @if (auth('admin')->check() && auth('admin')->user()->hasPermission('technical-support.manage'))
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $contactMessage->admin_reply ? 'تعديل الرد' : 'الرد على الرسالة' }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.technical-support.reply', $contactMessage) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="admin_reply" class="form-label">الرد</label>
                                    <textarea class="form-control @error('admin_reply') is-invalid @enderror" id="admin_reply" name="admin_reply" rows="6" required>{{ old('admin_reply', $contactMessage->admin_reply) }}</textarea>
                                    @error('admin_reply')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-send me-1"></i> إرسال الرد
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
