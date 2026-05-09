@extends('dashboard.layouts.master')

@section('content')
    @php
        $showDelete = auth('admin')->check() && auth('admin')->user()->hasPermission('ratings.delete');
        $colCount = $showDelete ? 6 : 5;
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">التقييمات</h4>
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
                            <th width="200">المقيّم</th>
                            <th>التعليق</th>
                            <th width="100">التقييم</th>
                            <th width="160">تاريخ الإنشاء</th>
                            @if ($showDelete)
                                <th width="100">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ratings as $rating)
                            <tr>
                                <td>{{ $rating->id }}</td>
                                <td>
                                    <strong class="d-block">{{ $rating->name }}</strong>
                                    <span class="small text-body-secondary">{{ $rating->email }}</span>
                                </td>
                                <td class="text-wrap align-top" style="max-width: 360px; white-space: normal;">{{ $rating->description }}</td>
                                <td>
                                    <span class="badge bg-label-warning">
                                        {{ $rating->rating }} / 5
                                    </span>
                                </td>
                                <td>{{ $rating->created_at->format('Y-m-d H:i') }}</td>
                                @if ($showDelete)
                                    <td>
                                        <form action="{{ route('dashboard.ratings.destroy', $rating) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $colCount }}" class="text-center py-5 text-body-secondary">
                                    لا توجد تقييمات بعد.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($ratings->hasPages())
                <div class="card-footer">
                    {{ $ratings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
