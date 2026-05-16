@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">{{ $notification->title }}</h4>
        <a href="{{ route('dashboard.user.notifications') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="text-body-secondary small mb-4">{{ $notification->created_at->format('Y-m-d H:i') }}</p>
            <div class="notification-message-content">
                {!! $notification->message !!}
            </div>
        </div>
    </div>
</div>
@endsection
