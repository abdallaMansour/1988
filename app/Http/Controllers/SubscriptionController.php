<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscription;
use App\Services\ZiinaService;
use Illuminate\Http\Request;

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

        $subscription = Subscription::where('ziina_payment_intent_id', $intentId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $subscription) {
            return redirect()->route('website.landing-page')->with('error', __('Subscription not found.'));
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

    public function cancel()
    {
        return redirect()->route('website.landing-page')->with('info', __('Payment was cancelled.'));
    }
}
