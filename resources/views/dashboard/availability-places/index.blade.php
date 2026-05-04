@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">أماكن التوفر</h4>
            @if(auth('admin')->check() && auth('admin')->user()->hasPermission('availability-places.manage'))
                <a href="{{ route('dashboard.availability-places.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة مكان
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
                            <th width="80">الصورة</th>
                            <th width="50">#</th>
                            <th>العنوان</th>
                            <th>الدولة</th>
                            <th>الرابط</th>
                            <th>الوصف</th>
                            @if(auth('admin')->check() && auth('admin')->user()->hasPermission('availability-places.manage'))
                                <th width="120">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($availabilityPlaces as $availabilityPlace)
                            <tr>
                                <td>
                                    @if ($availabilityPlace->hasMedia('image'))
                                        <img src="{{ $availabilityPlace->getFirstMediaUrl('image') }}" alt="{{ $availabilityPlace->title }}" class="rounded" style="width: 50px; height: 50px; object-fit: contain;">
                                    @else
                                        <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-image"></i></span>
                                    @endif
                                </td>
                                <td>{{ $availabilityPlace->id }}</td>
                                <td><strong>{{ $availabilityPlace->title }}</strong></td>
                                <td>{{ $availabilityPlace->country ? strtoupper($availabilityPlace->country) : '—' }}</td>
                                <td>
                                    <a href="{{ $availabilityPlace->url }}" target="_blank" rel="noopener noreferrer" class="text-truncate d-inline-block" style="max-width: 180px;">{{ $availabilityPlace->url }}</a>
                                </td>
                                <td>{{ Str::limit(strip_tags($availabilityPlace->description), 60) }}</td>
                                @if(auth('admin')->check() && auth('admin')->user()->hasPermission('availability-places.manage'))
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('dashboard.availability-places.edit', $availabilityPlace) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> تعديل
                                                </a>
                                                <form action="{{ route('dashboard.availability-places.destroy', $availabilityPlace) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟');">
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
                                <td colspan="{{ auth('admin')->check() && auth('admin')->user()->hasPermission('availability-places.manage') ? '7' : '6' }}" class="text-center py-5 text-body-secondary">
                                    لا توجد أماكن توفر بعد.
                                    @if(auth('admin')->check() && auth('admin')->user()->hasPermission('availability-places.manage'))
                                        <a href="{{ route('dashboard.availability-places.create') }}">إضافة مكان</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($availabilityPlaces->hasPages())
                <div class="card-footer">
                    {{ $availabilityPlaces->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
