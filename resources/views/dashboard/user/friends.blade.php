@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">اصدقائي</h4>
            <p class="text-body-secondary mb-0">المحققون الذين قبلتم صداقتهم</p>
        </div>
        <a href="{{ route('dashboard.user.detectives-guild') }}" class="btn btn-label-primary">
            <i class="bx bx-id-card me-1"></i> نقابة المحققين
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

    @if ($friends->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bx bx-group bx-lg text-body-secondary mb-3 d-block"></i>
            <p class="text-body-secondary mb-3">لا يوجد أصدقاء بعد.</p>
            <a href="{{ route('dashboard.user.detectives-guild') }}" class="btn btn-primary">تصفح نقابة المحققين</a>
        </div>
    </div>
    @else
    <div class="row g-4">
        @foreach ($friends as $friend)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column align-items-center">
                    @if ($friend->avatarUrl())
                        <img src="{{ $friend->avatarUrl() }}" alt="{{ $friend->investigator_name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <span class="avatar-initial rounded-circle bg-label-secondary d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bx bx-user bx-lg"></i>
                        </span>
                    @endif
                    <h6 class="mb-3">{{ $friend->investigator_name }}</h6>
                    <form action="{{ route('dashboard.user.friendships.unfriend', $friend) }}" method="POST" class="mt-auto" onsubmit="return confirm('هل تريد إزالة هذا الصديق؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-label-danger w-100">
                            <i class="bx bx-user-minus me-1"></i> إزالة صديق
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if ($friends->hasPages())
    <div class="mt-4">{{ $friends->links() }}</div>
    @endif
    @endif
</div>
@endsection
