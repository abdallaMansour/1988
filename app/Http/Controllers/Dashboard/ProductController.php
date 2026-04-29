<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        if (!auth('admin')->check() || !auth('admin')->user()->hasPermission('products.view')) {
            return abort(403, 'ليس لديك صلاحية لعرض المنتجات');
        }

        $products = Product::query()->latest('id')->paginate(15);

        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        return view('dashboard.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price_before_discount' => ['required', 'numeric', 'min:0'],
            'sale_price_after_discount' => ['required', 'numeric', 'min:0', 'lte:sale_price_before_discount'],
            'quantity' => ['required', 'integer', 'min:0'],
            'details' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
        ]);

        $product = Product::create([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'purchase_price' => $validated['purchase_price'],
            'sale_price_before_discount' => $validated['sale_price_before_discount'],
            'sale_price_after_discount' => $validated['sale_price_after_discount'],
            'quantity' => $validated['quantity'],
            'details' => $validated['details'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('dashboard.products.index')->with('success', 'تم إنشاء المنتج بنجاح.');
    }

    public function edit(Product $product)
    {
        return view('dashboard.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'name' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price_before_discount' => ['required', 'numeric', 'min:0'],
            'sale_price_after_discount' => ['required', 'numeric', 'min:0', 'lte:sale_price_before_discount'],
            'quantity' => ['required', 'integer', 'min:0'],
            'details' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
            'clear_images' => ['nullable', 'boolean'],
        ]);

        $product->update([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'purchase_price' => $validated['purchase_price'],
            'sale_price_before_discount' => $validated['sale_price_before_discount'],
            'sale_price_after_discount' => $validated['sale_price_after_discount'],
            'quantity' => $validated['quantity'],
            'details' => $validated['details'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ]);

        if (!empty($validated['clear_images'])) {
            $product->clearMediaCollection('images');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('dashboard.products.index')->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Product $product)
    {
        Product::destroy($product->id);

        return redirect()->route('dashboard.products.index')->with('success', 'تم حذف المنتج بنجاح.');
    }
}
