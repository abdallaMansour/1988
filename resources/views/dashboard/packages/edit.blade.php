@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">تعديل الباقة</h4>
            <a href="{{ route('dashboard.packages.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="icon" class="form-label">الأيقونة</label>
                        @if ($package->hasMedia('icon'))
                            <div class="mb-2">
                                <img src="{{ $package->getFirstMediaUrl('icon') }}" alt="{{ $package->title }}" class="rounded" style="max-height: 60px;">
                                <small class="d-block text-body-secondary">الأيقونة الحالية. ارفع صورة جديدة للاستبدال.</small>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/*">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $package->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="monthly_price" class="form-label">السعر الشهري <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('monthly_price') is-invalid @enderror" id="monthly_price" name="monthly_price"
                                value="{{ old('monthly_price', $package->monthly_price) }}" required>
                            @error('monthly_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="yearly_price" class="form-label">السعر السنوي <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('yearly_price') is-invalid @enderror" id="yearly_price" name="yearly_price"
                                value="{{ old('yearly_price', $package->yearly_price) }}" required>
                            @error('yearly_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="cars_count" class="form-label">عدد السيارات <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('cars_count') is-invalid @enderror" id="cars_count" name="cars_count" value="{{ old('cars_count', $package->cars_count) }}"
                                required>
                            @error('cars_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="addons_count" class="form-label">عدد الإضافات <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('addons_count') is-invalid @enderror" id="addons_count" name="addons_count"
                                value="{{ old('addons_count', $package->addons_count) }}" required>
                            @error('addons_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">المميزات</label>
                        <div id="features-container">
                            @if ($package->features && count($package->features) > 0)
                                @foreach ($package->features as $feature)
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="features[]" value="{{ $feature }}" placeholder="الميزة">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()"><i class="bx bx-trash"></i></button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="features[]" placeholder="الميزة 1">
                                    <button type="button" class="btn btn-outline-secondary" onclick="addFeature()"><i class="bx bx-plus"></i></button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addFeature()"><i class="bx bx-plus me-1"></i> إضافة ميزة</button>
                        <small class="d-block text-body-secondary mt-1">انقر لإضافة المزيد من المميزات</small>
                    </div>

                    <button type="submit" class="btn btn-primary">تحديث الباقة</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addFeature() {
            const container = document.getElementById('features-container');
            const count = container.querySelectorAll('.input-group').length + 1;
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="الميزة ${count}">
        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()"><i class="bx bx-trash"></i></button>
    `;
            container.appendChild(div);
        }
    </script>
@endsection
