@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">قسم الفيديوهات — {{ $issue->title }}</h4>
            <a href="{{ route('dashboard.issues.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع للجرائم
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('dashboard.issues.videos.update', $issue) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header"><strong>القصة (فيديو)</strong></div>
                <div class="card-body">
                    @if ($issue->hasMedia('story_video'))
                        <div class="mb-3">
                            <video src="{{ $issue->getFirstMediaUrl('story_video') }}" controls class="w-100 rounded" style="max-height: 280px;"></video>
                        </div>
                    @endif
                    <label for="story_video" class="form-label">رفع أو استبدال الفيديو</label>
                    <input type="file" class="form-control @error('story_video') is-invalid @enderror" id="story_video" name="story_video" accept="video/*">
                    @error('story_video')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><strong>الأدلة (صور أو فيديوهات — يمكن إضافة عدة ملفات)</strong></div>
                <div class="card-body">
                    @php
                        $evidenceMedia = $issue->getMedia('evidence');
                    @endphp
                    @if ($evidenceMedia->isNotEmpty())
                        <div class="row g-3 mb-4">
                            @foreach ($evidenceMedia as $media)
                                <div class="col-md-4 col-lg-3">
                                    <div class="border rounded p-2 h-100">
                                        @if (str_starts_with((string) $media->mime_type, 'video'))
                                            <video src="{{ $media->getUrl() }}" controls class="w-100 rounded" style="max-height: 140px;"></video>
                                        @else
                                            <img src="{{ $media->getUrl() }}" alt="" class="w-100 rounded object-fit-cover" style="max-height: 140px;">
                                        @endif
                                        <form action="{{ route('dashboard.issues.videos.evidence.destroy', [$issue, $media->id]) }}" method="POST" class="mt-2" onsubmit="return confirm('حذف هذا الملف؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">حذف</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <label for="evidence" class="form-label">إضافة ملفات للأدلة</label>
                    <input type="file" class="form-control @error('evidence') is-invalid @enderror @error('evidence.*') is-invalid @enderror" id="evidence" name="evidence[]" accept="image/*,video/*" multiple>
                    @error('evidence')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('evidence.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><strong>النهاية — الحل (فيديو)</strong></div>
                <div class="card-body">
                    @if ($issue->hasMedia('ending_video'))
                        <div class="mb-3">
                            <video src="{{ $issue->getFirstMediaUrl('ending_video') }}" controls class="w-100 rounded" style="max-height: 280px;"></video>
                        </div>
                    @endif
                    <label for="ending_video" class="form-label">رفع أو استبدال الفيديو</label>
                    <input type="file" class="form-control @error('ending_video') is-invalid @enderror" id="ending_video" name="ending_video" accept="video/*">
                    @error('ending_video')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
        </form>
    </div>
@endsection
