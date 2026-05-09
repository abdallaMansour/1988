<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\ZiinaService;

class StoreCheckoutController extends Controller
{
    public function __construct(
        private ZiinaService $ziina
    ) {}

    public function productCheckout(Product $product)
    {
        if ($redirect = $this->authorizeProduct($product)) {
            return $redirect;
        }

        return view('website.checkout.product', compact('product'));
    }

    public function productPay(Product $product)
    {
        if ($redirect = $this->authorizeProduct($product)) {
            return $redirect;
        }

        $amount = (float) $product->sale_price_after_discount;
        $currency = config('ziina.currency', 'AED');

        return $this->startZiinaCheckout(
            $product,
            $amount,
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

    public function issuePay(Issue $issue)
    {
        $this->authorizeIssue($issue);

        $amount = (float) $issue->purchase_price_after_discount;
        $currency = config('ziina.currency', 'AED');

        return $this->startZiinaCheckout(
            $issue,
            $amount,
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

    private function startZiinaCheckout($purchasable, float $amount, string $currency, string $message, string $backRoute): \Illuminate\Http\RedirectResponse
    {
        if ($amount <= 0) {
            return redirect()->to($backRoute)->with('error', __('السعر غير صالح.'));
        }

        $purchase = Purchase::create([
            'user_id' => auth()->id(),
            'purchasable_type' => $purchasable->getMorphClass(),
            'purchasable_id' => $purchasable->getKey(),
            'amount' => $amount,
            'currency' => $currency,
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
