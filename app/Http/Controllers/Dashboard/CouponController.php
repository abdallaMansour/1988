<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Issue;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            if (! $admin->hasPermission('coupons.view') && ! $admin->hasPermission('coupons.manage')) {
                abort(403, 'ليس لديك صلاحية لعرض الكوبونات');
            }
        }

        $coupons = Coupon::query()
            ->withCount(['products', 'issues'])
            ->latest('id')
            ->paginate(15);

        return view('dashboard.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $this->ensureManage();

        $products = Product::query()->orderBy('name')->get();
        $issues = Issue::query()->orderBy('title')->get();

        return view('dashboard.coupons.create', compact('products', 'issues'));
    }

    public function store(Request $request)
    {
        $this->ensureManage();

        $validated = $this->validateCoupon($request);

        $coupon = Coupon::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'total_usage_limit' => $validated['total_usage_limit'],
            'per_user_usage_limit' => $validated['per_user_usage_limit'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'applies_to' => $validated['applies_to'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncRelations($coupon, $validated);

        return redirect()->route('dashboard.coupons.index')->with('success', 'تم إنشاء الكوبون بنجاح.');
    }

    public function edit(Coupon $coupon)
    {
        $this->ensureManage();

        $coupon->load(['products', 'issues']);
        $products = Product::query()->orderBy('name')->get();
        $issues = Issue::query()->orderBy('title')->get();

        return view('dashboard.coupons.edit', compact('coupon', 'products', 'issues'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $this->ensureManage();

        $validated = $this->validateCoupon($request, $coupon);

        $coupon->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'total_usage_limit' => $validated['total_usage_limit'],
            'per_user_usage_limit' => $validated['per_user_usage_limit'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'applies_to' => $validated['applies_to'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncRelations($coupon, $validated);

        return redirect()->route('dashboard.coupons.index')->with('success', 'تم تحديث الكوبون بنجاح.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->ensureManage();

        $coupon->delete();

        return redirect()->route('dashboard.coupons.index')->with('success', 'تم حذف الكوبون بنجاح.');
    }

    private function validateCoupon(Request $request, ?Coupon $coupon = null): array
    {
        $request->merge([
            'code' => trim((string) $request->input('code')),
            'total_usage_limit' => $request->filled('total_usage_limit') ? $request->input('total_usage_limit') : null,
            'per_user_usage_limit' => $request->filled('per_user_usage_limit') ? $request->input('per_user_usage_limit') : null,
        ]);

        $appliesValues = [
            Coupon::APPLIES_ALL,
            Coupon::APPLIES_PRODUCTS,
            Coupon::APPLIES_ISSUES,
            Coupon::APPLIES_SPECIFIC_PRODUCTS,
            Coupon::APPLIES_SPECIFIC_ISSUES,
        ];

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('coupons', 'code')->ignore($coupon?->id),
            ],
            'discount_type' => ['required', Rule::in([Coupon::DISCOUNT_PERCENT, Coupon::DISCOUNT_FIXED])],
            'discount_value' => array_merge(
                ['required', 'numeric', 'min:0'],
                $request->input('discount_type') === Coupon::DISCOUNT_PERCENT ? ['max:100'] : []
            ),
            'total_usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'applies_to' => ['required', Rule::in($appliesValues)],
            'product_ids' => ['sometimes', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'issue_ids' => ['sometimes', 'array'],
            'issue_ids.*' => ['integer', 'exists:issues,id'],
        ];

        $validated = $request->validate($rules);

        if ($validated['applies_to'] === Coupon::APPLIES_SPECIFIC_PRODUCTS) {
            $productIds = array_values(array_unique($validated['product_ids'] ?? []));
            if ($productIds === []) {
                throw ValidationException::withMessages([
                    'product_ids' => 'اختر منتجاً واحداً على الأقل.',
                ]);
            }
            $validated['product_ids'] = $productIds;
        } else {
            $validated['product_ids'] = [];
        }

        if ($validated['applies_to'] === Coupon::APPLIES_SPECIFIC_ISSUES) {
            $issueIds = array_values(array_unique($validated['issue_ids'] ?? []));
            if ($issueIds === []) {
                throw ValidationException::withMessages([
                    'issue_ids' => 'اختر قضية واحدة على الأقل.',
                ]);
            }
            $validated['issue_ids'] = $issueIds;
        } else {
            $validated['issue_ids'] = [];
        }

        return $validated;
    }

    private function syncRelations(Coupon $coupon, array $validated): void
    {
        $coupon->products()->detach();
        $coupon->issues()->detach();

        if ($validated['applies_to'] === Coupon::APPLIES_SPECIFIC_PRODUCTS) {
            $coupon->products()->sync($validated['product_ids']);
        }

        if ($validated['applies_to'] === Coupon::APPLIES_SPECIFIC_ISSUES) {
            $coupon->issues()->sync($validated['issue_ids']);
        }
    }

    private function ensureManage(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            abort_unless($admin->hasPermission('coupons.manage'), 403, 'ليس لديك صلاحية لإدارة الكوبونات');
        }
    }
}
