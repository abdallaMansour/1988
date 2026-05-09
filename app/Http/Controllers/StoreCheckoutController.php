<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\CheckoutCouponService;
use App\Services\ZiinaService;
use Illuminate\Http\Request;

class StoreCheckoutController extends Controller
{
    public function __construct(
        private ZiinaService $ziina,
        private CheckoutCouponService $checkoutCoupon,
    ) {}

    public function productCheckout(Product $product)
    {
        if ($redirect = $this->authorizeProduct($product)) {
            return $redirect;
        }

        return view('website.checkout.product', compact('product'));
    }

    public function productPay(Product $product, Request $request)
    {
        if ($redirect = $this->authorizeProduct($product)) {
            return $redirect;
        }

        $currency = config('ziina.currency', 'AED');

        $pricing = $this->checkoutCoupon->apply($request->input('coupon_code'), $product, auth()->id());
        if (! $pricing['ok']) {
            return redirect()->route('website.checkout.product', $product)->withErrors(['coupon_code' => $pricing['message']])->withInput();
        }

        return $this->startZiinaCheckout(
            $product,
            $pricing,
            $currency,
            "منتج: {$product->name}",
            route('website.products.show', $product)
        );
    }

    public function issueCheckout(Issue $issue)
    {
        $this->authorizeIssue($issue);

        return view('website.checkout.issue', compact('issue'));
    }

    public function issuePay(Issue $issue, Request $request)
    {
        $this->authorizeIssue($issue);

        $currency = config('ziina.currency', 'AED');

        $pricing = $this->checkoutCoupon->apply($request->input('coupon_code'), $issue, auth()->id());
        if (! $pricing['ok']) {
            return redirect()->route('website.checkout.issue', $issue)->withErrors(['coupon_code' => $pricing['message']])->withInput();
        }

        return $this->startZiinaCheckout(
            $issue,
            $pricing,
            $currency,
            "قضية: {$issue->title}",
            route('website.issues.show', $issue)
        );
    }

    private function authorizeProduct(Product $product): ?\Illuminate\Http\RedirectResponse
    {
        abort_unless($product->is_active, 404);
        if ($product->quantity < 1) {
            return redirect()->route('website.products.show', $product)->with('error', 'المنتج غير متوفر حالياً.');
        }

        return null;
    }

    private function authorizeIssue(Issue $issue): void
    {
        abort_unless($issue->is_active, 404);
    }

    /**
     * @param  array{ok: true, coupon: ?\App\Models\Coupon, subtotal: float, discount_amount: float, final_amount: float}  $pricing
     */
    private function startZiinaCheckout($purchasable, array $pricing, string $currency, string $message, string $backRoute): \Illuminate\Http\RedirectResponse
    {
        $amount = $pricing['final_amount'];

        if ($amount <= 0) {
            return redirect()->to($backRoute)->with('error', __('السعر غير صالح.'));
        }

        $purchase = Purchase::create([
            'user_id' => auth()->id(),
            'coupon_id' => $pricing['coupon']?->id,
            'purchasable_type' => $purchasable->getMorphClass(),
            'purchasable_id' => $purchasable->getKey(),
            'amount' => $amount,
            'currency' => $currency,
            'subtotal' => $pricing['subtotal'],
            'discount_amount' => $pricing['discount_amount'],
            'status' => 'pending',
        ]);

        $successUrl = config('ziina.success_url');
        $cancelUrl = config('ziina.cancel_url');
        $failureUrl = config('ziina.failure_url');

        try {
            $intent = $this->ziina->createPaymentIntent(
                $amount,
                $currency,
                $message,
                $successUrl,
                $cancelUrl,
                $failureUrl
            );

            $purchase->update([
                'ziina_payment_intent_id' => $intent['id'],
                'ziina_operation_id' => $intent['operation_id'] ?? null,
            ]);

            return redirect($intent['redirect_url']);
        } catch (\InvalidArgumentException $e) {
            $purchase->update(['status' => 'cancelled']);

            return redirect()->to($backRoute)->with('error', 'المبلغ أقل من الحد الأدنى لمنصة زينه (يساوي عملتين من '.$currency.').');
        } catch (\Exception $e) {
            $purchase->update(['status' => 'cancelled']);

            return redirect()->to($backRoute)->withErrors(['payment' => $e->getMessage()]);
        }
    }
}
