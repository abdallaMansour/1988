@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">نقابة المحققين</h4>
            <p class="text-body-secondary mb-0">تعرّف على المحققين وأرسل طلبات الصداقة</p>
        </div>
        <a href="{{ route('dashboard.user.friends') }}" class="btn btn-label-primary">
            <i class="bx bx-group me-1"></i> اصدقائي
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($errors->has('friendship'))
    <div class="alert alert-danger alert-dismissible mb-4" role="alert">
        {{ $errors->first('friendship') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($incomingRequests->isNotEmpty())
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">طلبات صداقة واردة</h5>
            <span class="badge bg-label-primary">{{ $incomingRequests->count() }}</span>
        </div>
        <div class="row g-4">
            @foreach ($incomingRequests as $request)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 border border-primary border-2">
                    <div class="card-body text-center d-flex flex-column align-items-center">
                        @if ($request->requester->avatarUrl())
                            <img src="{{ $request->requester->avatarUrl() }}" alt="{{ $request->requester->investigator_name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <span class="avatar-initial rounded-circle bg-label-secondary d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bx bx-user bx-lg"></i>
                            </span>
                        @endif
                        <div class="mb-3">
                            @php $displayName = $request->requester->displayInvestigatorNameFor(auth()->user()); @endphp
                            <h6 class="mb-1">{{ $displayName }}</h6>
                            @if ($request->requester->isPrivateTo(auth()->user()))
                                <span class="badge bg-label-secondary">حساب خاص</span>
                            @endif
                        </div>
                        <form action="{{ route('dashboard.user.friendships.accept', $request) }}" method="POST" class="w-100 mb-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary w-100">قبول</button>
                        </form>
                        <form action="{{ route('dashboard.user.friendships.reject', $request) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-label-secondary w-100">رفض</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">جميع المحققين</h5>
    </div>

    @if ($investigators->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5 text-body-secondary">
            لا يوجد محققون آخرون حالياً.
        </div>
    </div>
    @else
    <div class="row g-4">
        @foreach ($investigators as $investigator)
        @php
            $relation = $friendshipMap[$investigator->id] ?? ['status' => 'can_add', 'friendship' => null];
            $status = $relation['status'];
            $friendship = $relation['friendship'];
        @endphp
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column align-items-center">
                    @if ($investigator->avatarUrl())
                        <img src="{{ $investigator->avatarUrl() }}" alt="{{ $investigator->investigator_name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <span class="avatar-initial rounded-circle bg-label-secondary d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bx bx-user bx-lg"></i>
                        </span>
                    @endif
                    <div class="mb-3">
                        @php $displayName = $investigator->displayInvestigatorNameFor(auth()->user()); @endphp
                        <h6 class="mb-1">{{ $displayName }}</h6>
                        @if ($investigator->isPrivateTo(auth()->user()))
                            <span class="badge bg-label-secondary">حساب خاص</span>
                        @endif
                    </div>
                    <div class="mt-auto w-100 d-flex flex-column align-items-center">
                        @include('dashboard.user.partials.investigator-friendship-actions', compact('investigator', 'status', 'friendship'))
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if ($investigators->hasPages())
    <div class="mt-4">{{ $investigators->links() }}</div>
    @endif
    @endif
</div>
@endsection
