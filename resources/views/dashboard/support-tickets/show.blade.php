@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h4 class="mb-0">التذكرة #{{ $supportTicket->id }}</h4>
            <div class="d-flex align-items-center gap-2">
                @if (auth('admin')->check() && auth('admin')->user()->hasPermission('support-tickets.manage'))
                    <form action="{{ route('dashboard.support-tickets.status', $supportTicket) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <div class="input-group input-group-sm" style="width: auto;">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach (\App\Models\SupportTicket::statuses() as $value => $label)
                                    <option value="{{ $value }}" {{ $supportTicket->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                @endif
                <a href="{{ route('dashboard.support-tickets.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> رجوع
                </a>
            </div>
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
                            <h5 class="mb-1">{{ $supportTicket->subject }}</h5>
                            <small class="text-body-secondary">
                                {{ $supportTicket->user->name }} - {{ $supportTicket->created_at->format('Y-m-d H:i') }}
                            </small>
                        </div>
                        <span
                            class="badge @switch($supportTicket->status)
                        @case('open') bg-label-warning @break
                        @case('in_progress') bg-label-info @break
                        @case('resolved') bg-label-success @break
                        @case('closed') bg-label-secondary @break
                        @default bg-label-primary
                    @endswitch">
                            {{ \App\Models\SupportTicket::statuses()[$supportTicket->status] ?? $supportTicket->status }}
                        </span>
                    </div>
                    <div class="card-body">
                        @foreach ($supportTicket->replies as $reply)
                            <div class="mb-4 {{ !$loop->first ? 'pt-4 border-top' : '' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>
                                            @if ($reply->isFromAdmin())
                                                <i class="bx bx-shield-quarter text-primary me-1"></i> {{ $reply->admin->name }} (الإدارة)
                                            @else
                                                <i class="bx bx-user me-1"></i> {{ $reply->user->name }}
                                            @endif
                                        </strong>
                                        <small class="text-body-secondary ms-2">{{ $reply->created_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                </div>
                                <p class="mb-0" style="white-space: pre-line;">{{ $reply->message }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if (!in_array($supportTicket->status, ['closed']) && auth('admin')->check() && auth('admin')->user()->hasPermission('support-tickets.manage'))
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">إضافة رد</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.support-tickets.reply', $supportTicket) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="message" class="form-label">الرد</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                                    @error('message')
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

            @auth('admin')
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">معلومات التذكرة</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>المستخدم:</strong> {{ $supportTicket->user->name }}</p>
                            <p class="mb-2"><strong>البريد:</strong> {{ $supportTicket->user->email }}</p>
                            <p class="mb-0"><strong>تاريخ الإنشاء:</strong> {{ $supportTicket->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
@endsection
