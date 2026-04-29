@extends('dashboard.layouts.master')

@section('content')
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
                            <th>الاسم</th>
                            <th>البريد</th>
                            <th>الوصف</th>
                            <th>التقييم</th>
                            <th>التاريخ</th>
                            @if(auth('admin')->check() && auth('admin')->user()->hasPermission('ratings.delete'))
                                <th width="100">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ratings as $rating)
                            <tr>
                                <td>{{ $rating->id }}</td>
                                <td><strong>{{ $rating->name }}</strong></td>
                                <td>{{ $rating->email }}</td>
                                <td>{{ Str::limit($rating->description, 80) }}</td>
                                <td>
                                    <span class="badge bg-label-warning">
                                        {{ $rating->rating }} / 5
                                    </span>
                                </td>
                                <td>{{ $rating->created_at->format('Y-m-d H:i') }}</td>
                                @if(auth('admin')->check() && auth('admin')->user()->hasPermission('ratings.delete'))
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
                                <td colspan="{{ auth('admin')->check() && auth('admin')->user()->hasPermission('ratings.delete') ? '7' : '6' }}" class="text-center py-5 text-body-secondary">
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
