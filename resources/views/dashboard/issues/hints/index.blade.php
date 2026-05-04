@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">التلميحات — {{ $issue->title }}</h4>
            <a href="{{ route('dashboard.issues.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع للقضايا
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header"><strong>إضافة تلميح جديد</strong></div>
            <div class="card-body">
                <form action="{{ route('dashboard.issues.hints.store', $issue) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end g-3">
                        <div class="col-md-8">
                            <label for="image" class="form-label">صورة التلميح <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">إضافة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>قائمة التلميحات</strong></div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="100">الصورة</th>
                            <th width="80">#</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hints as $hint)
                            <tr>
                                <td>
                                    @if ($hint->hasMedia('image'))
                                        <img src="{{ $hint->getFirstMediaUrl('image') }}" alt="" class="rounded" style="width: 72px; height: 72px; object-fit: cover;">
                                    @else
                                        <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-image"></i></span>
                                    @endif
                                </td>
                                <td>{{ $hint->id }}</td>
                                <td>
                                    <form action="{{ route('dashboard.issues.hints.destroy', [$issue, $hint]) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذا التلميح؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-body-secondary">لا توجد تلميحات بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
