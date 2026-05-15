<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\CartService;
use App\Services\CheckoutCouponService;
use App\Services\ZiinaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutCouponService $checkoutCoupon,
        private ZiinaService $ziina,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user('web');
        $lines = $this->cart->getItems($user);
        $pricing = $this->resolveCartPricing($lines, $user?->id);

        if ($request->expectsJson()) {
            return response()->json($this->cartPayload($lines, $pricing));
        }

        return view('website.cart.index', [
            'lines' => $lines,
            'pricing' => $pricing,
            'couponCode' => $this->cart->getCouponCode(),
            'currency' => config('ziina.currency', 'AED'),
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:product,issue',
            'id' => 'required|integer|min:1',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $user = $request->user('web');

        try {
            if ($validated['type'] === 'product') {
                $product = Product::query()->whereKey($validated['id'])->where('is_active', true)->firstOrFail();
                $this->cart->addProduct($product, (int) ($validated['quantity'] ?? 1));
            } else {
                $issue = Issue::query()->whereKey($validated['id'])->where('is_active', true)->firstOrFail();
                $this->cart->addIssue($issue, $user);
            }
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تمت الإضافة إلى السلة.',
                'cart_count' => $this->cart->count(),
            ]);
        }

        return redirect()->route('website.cart.index')->with('success', 'تمت الإضافة إلى السلة.');
    }

    public function update(Request $request, string $key): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cart->updateQuantity($key, (int) $validated['quantity']);

        if ($request->expectsJson()) {
            $user = $request->user('web');
            $lines = $this->cart->getItems($user);
            $pricing = $this->resolveCartPricing($lines, $user?->id);

            return response()->json($this->cartPayload($lines, $pricing));
        }

        return redirect()->route('website.cart.index');
    }

    public function destroy(Request $request, string $key): RedirectResponse|JsonResponse
    {
        $this->cart->remove($key);

        if ($request->expectsJson()) {
            $user = $request->user('web');
            $lines = $this->cart->getItems($user);
            $pricing = $this->resolveCartPricing($lines, $user?->id);

            return response()->json($this->cartPayload($lines, $pricing));
        }

        return redirect()->route('website.cart.index')->with('success', 'تم حذف العنصر من السلة.');
    }

    public function applyCoupon(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'coupon_code' => 'nullable|string|max:191',
        ]);

        $code = trim((string) ($validated['coupon_code'] ?? ''));
        $this->cart->setCouponCode($code !== '' ? $code : null);

        $user = $request->user('web');
        $lines = $this->cart->getItems($user);
        $pricing = $this->resolveCartPricing($lines, $user?->id);

        if ($code !== '' && ! $pricing['ok']) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $pricing['message']], 422);
            }

            return redirect()->route('website.cart.index')->withErrors(['coupon_code' => $pricing['message']]);
        }

        if ($request->expectsJson()) {
            return response()->json($this->cartPayload($lines, $pricing));
        }

        return redirect()->route('website.cart.index')->with('success', $code !== '' ? 'تم تطبيق الكوبون.' : 'تم إزالة الكوبون.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $user = $request->user('web');
        abort_unless($user, 403);

        $lines = $this->cart->getItems($user);

        if ($lines->isEmpty()) {
            return redirect()->route('website.cart.index')->with('error', 'السلة فارغة.');
        }

        foreach ($lines as $line) {
            if ($line['type'] === 'issue' && $user->hasPaidPurchaseFor($line['model'])) {
                $this->cart->remove($line['key']);

                return redirect()->route('website.cart.index')->with('error', 'إحدى الجرائم في السلة مملوكة لك مسبقاً وتمت إزالتها.');
            }
            if ($line['type'] === 'product') {
                $product = $line['model'];
                if ($product->quantity < $line['quantity']) {
                    return redirect()->route('website.cart.index')->with('error', 'الكمية المطلوبة لـ «'.$product->name.'» غير متوفرة.');
                }
            }
        }

        $lines = $this->cart->getItems($user);
        $couponCode = $this->cart->getCouponCode();
        $pricing = $this->checkoutCoupon->applyToCart($couponCode, $lines, $user->id);

        if (! $pricing['ok']) {
            return redirect()->route('website.cart.index')->withErrors(['coupon_code' => $pricing['message']]);
        }

        $currency = config('ziina.currency', 'AED');
        $batchId = (string) Str::uuid();
        $purchases = [];

        foreach ($lines as $line) {
            $allocation = $pricing['line_allocations'][$line['key']];
            $purchases[] = Purchase::create([
                'user_id' => $user->id,
                'checkout_batch_id' => $batchId,
                'coupon_id' => $pricing['coupon']?->id,
                'purchasable_type' => $line['model']->getMorphClass(),
                'purchasable_id' => $line['model']->getKey(),
                'quantity' => $line['quantity'],
                'amount' => $allocation['final_amount'],
                'currency' => $currency,
                'subtotal' => $allocation['line_subtotal'],
                'discount_amount' => $allocation['discount_amount'],
                'status' => 'pending',
            ]);
        }

        $totalAmount = $pricing['final_amount'];
        $message = 'سلة مشتريات — '.$lines->count().' عنصر';

        try {
            $intent = $this->ziina->createPaymentIntent(
                $totalAmount,
                $currency,
                $message,
                config('ziina.success_url'),
                config('ziina.cancel_url'),
                config('ziina.failure_url'),
            );

            Purchase::query()
                ->where('checkout_batch_id', $batchId)
                ->update([
                    'ziina_payment_intent_id' => $intent['id'],
                    'ziina_operation_id' => $intent['operation_id'] ?? null,
                ]);

            return redirect($intent['redirect_url']);
        } catch (\InvalidArgumentException $e) {
            Purchase::query()->where('checkout_batch_id', $batchId)->update(['status' => 'cancelled']);

            return redirect()->route('website.cart.index')->with('error', 'المبلغ أقل من الحد الأدنى لمنصة زينه.');
        } catch (\Exception $e) {
            Purchase::query()->where('checkout_batch_id', $batchId)->update(['status' => 'cancelled']);

            return redirect()->route('website.cart.index')->withErrors(['payment' => $e->getMessage()]);
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>>  $lines
     * @return array<string, mixed>
     */
    private function resolveCartPricing($lines, ?int $userId): array
    {
        $couponCode = $this->cart->getCouponCode();

        if ($lines->isEmpty()) {
            return [
                'ok' => true,
                'coupon' => null,
                'subtotal' => 0.0,
                'eligible_subtotal' => 0.0,
                'discount_amount' => 0.0,
                'final_amount' => 0.0,
                'line_allocations' => [],
                'message' => null,
            ];
        }

        $result = $this->checkoutCoupon->applyToCart($couponCode, $lines, $userId);

        if (! $result['ok']) {
            return array_merge($result, [
                'subtotal' => round((float) $lines->sum('line_subtotal'), 2),
                'eligible_subtotal' => 0.0,
                'discount_amount' => 0.0,
                'final_amount' => round((float) $lines->sum('line_subtotal'), 2),
                'line_allocations' => $lines->mapWithKeys(fn ($line) => [
                    $line['key'] => [
                        'line_subtotal' => $line['line_subtotal'],
                        'discount_amount' => 0.0,
                        'final_amount' => $line['line_subtotal'],
                    ],
                ])->all(),
            ]);
        }

        return $result;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>>  $lines
     * @param  array<string, mixed>  $pricing
     * @return array<string, mixed>
     */
    private function cartPayload($lines, array $pricing): array
    {
        $currency = config('ziina.currency', 'AED');

        return [
            'cart_count' => $this->cart->count(),
            'lines' => $lines->map(fn ($line) => [
                'key' => $line['key'],
                'type' => $line['type'],
                'name' => $line['name'],
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'line_subtotal' => $line['line_subtotal'],
                'allocation' => $pricing['line_allocations'][$line['key']] ?? [
                    'line_subtotal' => $line['line_subtotal'],
                    'discount_amount' => 0.0,
                    'final_amount' => $line['line_subtotal'],
                ],
            ])->values(),
            'summary' => [
                'subtotal' => $pricing['subtotal'] ?? 0,
                'discount_amount' => $pricing['discount_amount'] ?? 0,
                'final_amount' => $pricing['final_amount'] ?? 0,
                'currency' => $currency,
            ],
            'coupon_ok' => $pricing['ok'] ?? true,
            'coupon_message' => $pricing['message'] ?? null,
        ];
    }
}
