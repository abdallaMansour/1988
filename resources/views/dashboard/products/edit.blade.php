@extends('dashboard.layouts.master')

@section('page-css')
    @include('dashboard.partials.rich-text-editor-head')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">تعديل منتج</h4>
            <a href="{{ route('dashboard.products.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="product-edit-form" action="{{ route('dashboard.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">الإسم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label for="purchase_price" class="form-label">سعر الشراء <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                            @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="sale_price_before_discount" class="form-label">سعر البيع قبل الخصم <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('sale_price_before_discount') is-invalid @enderror" id="sale_price_before_discount" name="sale_price_before_discount" value="{{ old('sale_price_before_discount', $product->sale_price_before_discount) }}" required>
                            @error('sale_price_before_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="sale_price_after_discount" class="form-label">سعر البيع بعد الخصم <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('sale_price_after_discount') is-invalid @enderror" id="sale_price_after_discount" name="sale_price_after_discount" value="{{ old('sale_price_after_discount', $product->sale_price_after_discount) }}" required>
                            @error('sale_price_after_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="quantity" class="form-label">الكميه <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="is_active" class="form-label">الحاله <span class="text-danger">*</span></label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                <option value="1" {{ (string) old('is_active', (int) $product->is_active) === '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ (string) old('is_active', (int) $product->is_active) === '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if ($product->hasMedia('images'))
                        <div class="mb-3">
                            <label class="form-label d-block">الصور الحالية</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($product->getMedia('images') as $image)
                                    <img src="{{ $image->getUrl() }}" alt="Product Image" class="rounded border" style="width: 90px; height: 90px; object-fit: cover;">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-2 form-check">
                        <input type="checkbox" class="form-check-input" id="clear_images" name="clear_images" value="1" {{ old('clear_images') ? 'checked' : '' }}>
                        <label class="form-check-label" for="clear_images">حذف جميع الصور الحالية</label>
                    </div>

                    <div class="mb-4">
                        <label for="images" class="form-label">إضافة صور جديدة</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">التفاصيل</label>
                        <div class="dashboard-rich-editor-wrap @error('details') is-invalid @enderror">
                            <div id="product_details_editor"></div>
                        </div>
                        <input type="hidden" name="details" id="product_details_input" value="" />
                        @error('details')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    @include('dashboard.partials.rich-text-editor-scripts', [
        'editorId' => 'product_details_editor',
        'hiddenInputId' => 'product_details_input',
        'formId' => 'product-edit-form',
        'initialHtml' => old('details', $product->details ?? ''),
        'placeholder' => 'تفاصيل المنتج…',
    ])
@endsection
