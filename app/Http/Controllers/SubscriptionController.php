<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Issue;
use App\Models\Package;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Subscription;
use App\Services\ZiinaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function __construct(
        private ZiinaService $ziina
    ) {}

    public function checkoutPage(Package $package)
    {
        return view('subscription.checkout', compact('package'));
    }

    public function checkout(Package $package, Request $request)
    {
        $request->validate(['period' => ['required', 'in:monthly,yearly']]);

        $period = $request->period;
        $amount = $period === 'monthly' ? (float) $package->monthly_price : (float) $package->yearly_price;

        if ($amount <= 0) {
            return back()->withErrors(['amount' => __('Invalid package price.')]);
        }

        $currency = config('ziina.currency', 'AED');
        $expiresAt = $period === 'monthly'
            ? now()->addMonth()
            : now()->addYear();

        $subscription = Subscription::create([
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'amount' => $amount,
            'currency' => $currency,
            'period' => $period,
            'expires_at' => $expiresAt,
            'status' => 'pending',
        ]);

        $successUrl = config('ziina.success_url');
        $cancelUrl = config('ziina.cancel_url');

        try {
            $intent = $this->ziina->createPaymentIntent(
                $amount,
                $currency,
                "Subscription: {$package->title} ({$period})",
                $successUrl,
                $cancelUrl
            );

            $subscription->update([
                'ziina_payment_intent_id' => $intent['id'],
                'ziina_operation_id' => $intent['operation_id'] ?? null,
            ]);

            return redirect($intent['redirect_url']);
        } catch (\Exception $e) {
            $subscription->update(['status' => 'cancelled']);

            return back()->withErrors(['payment' => $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        $intentId = $request->query('intent_id');

        if (! $intentId) {
            return redirect()->route('website.landing-page')->with('error', __('Invalid payment confirmation.'));
        }

        $userId = auth()->id();

        $purchase = Purchase::query()
            ->where('ziina_payment_intent_id', $intentId)
            ->where('user_id', $userId)
            ->first();

        if ($purchase) {
            return $this->finishPurchase($purchase);
        }

        $subscription = Subscription::where('ziina_payment_intent_id', $intentId)
            ->where('user_id', $userId)
            ->first();

        if (! $subscription) {
            return redirect()->route('website.landing-page')->with('error', __('Payment record not found.'));
        }

        if ($subscription->status === 'active') {
            return redirect()->route('website.landing-page')->with('success', __('Subscription is already active.'));
        }

        try {
            $intent = $this->ziina->getPaymentIntent($intentId);

            if (($intent['status'] ?? '') === 'completed') {
                $subscription->update(['status' => 'active']);

                return redirect()->route('website.landing-page')->with('success', __('Payment successful! Your subscription is now active.'));
            }

            return redirect()->route('website.landing-page')->with('error', __('Payment is not completed yet.'));
        } catch (\Exception $e) {
            return redirect()->route('website.landing-page')->with('error', __('Unable to verify payment.'));
        }
    }

    private function finishPurchase(Purchase $purchase): RedirectResponse
    {
        if ($purchase->status === 'paid') {
            return $this->redirectAfterPurchase($purchase)->with('success', 'تم تأكيد الشراء مسبقاً.');
        }

        try {
            $intent = $this->ziina->getPaymentIntent($purchase->ziina_payment_intent_id);

            if (($intent['status'] ?? '') !== 'completed') {
                return redirect()->route('website.landing-page')->with('error', __('Payment is not completed yet.'));
            }

            DB::transaction(function () use ($purchase) {
                $purchase->load('purchasable');
                $item = $purchase->purchasable;

                if ($purchase->coupon_id) {
                    $coupon = Coupon::query()->whereKey($purchase->coupon_id)->lockForUpdate()->first();
                    if (! $coupon || ! $coupon->isCurrentlyActive() || ! $coupon->appliesToPurchasable($item)) {
                        throw new \RuntimeException('لم يعد الكوبون صالحاً لهذا الشراء.');
                    }
                    if (! $coupon->hasRemainingGlobalUses()) {
                        throw new \RuntimeException('لم يعد بالإمكان استخدام هذا الكوبون (تم استنفاد الاستخدامات).');
                    }
                    if (! $coupon->hasRemainingUsesForUser((int) $purchase->user_id)) {
                        throw new \RuntimeException('لم يعد بالإمكان استخدام هذا الكوبون لحسابك.');
                    }
                }

                if ($item instanceof Product) {
                    $product = Product::query()->whereKey($item->getKey())->lockForUpdate()->first();
                    if (! $product || ! $product->is_active) {
                        throw new \RuntimeException('المنتج لم يعد متاحاً.');
                    }
                    if ($product->quantity < 1) {
                        throw new \RuntimeException('نفد مخزون هذا المنتج.');
                    }
                    $product->decrement('quantity');
                }

                $purchase->update(['status' => 'paid']);
            });

            return $this->redirectAfterPurchase($purchase)->with('success', 'تم الشراء بنجاح.');
        } catch (\RuntimeException $e) {
            return redirect()->route('website.landing-page')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('website.landing-page')->with('error', __('Unable to verify payment.'));
        }
    }

    private function redirectAfterPurchase(Purchase $purchase): RedirectResponse
    {
        $purchase->loadMissing('purchasable');

        return $purchase->purchasable instanceof Issue
            ? redirect()->route('website.purchased-issues')
            : redirect()->route('website.products');
    }

    public function cancel()
    {
        return redirect()->route('website.landing-page')->with('info', __('Payment was cancelled.'));
    }
}
