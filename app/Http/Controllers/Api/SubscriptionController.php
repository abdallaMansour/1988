<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use App\Models\Subscription;
use App\Services\ZiinaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        private ZiinaService $ziina
    ) {}

    public function checkout(Package $package, Request $request)
    {
        $request->validate(['period' => ['required', 'in:monthly,yearly']]);

        $period = $request->period;
        $amount = $period === 'monthly' ? (float) $package->monthly_price : (float) $package->yearly_price;

        if ($amount <= 0) {
            return $this->sendError('Invalid package price.');
        }

        $currency = config('ziina.currency', 'AED');
        $expiresAt = $period === 'monthly'
            ? now()->addMonth()
            : now()->addYear();

        $subscription = Subscription::create([
            'user_id' => auth('api')->user()->id,
            'package_id' => $package->id,
            'amount' => $amount,
            'currency' => $currency,
            'period' => $period,
            'expires_at' => $expiresAt,
            'status' => 'pending',
        ]);

        $successUrl = env('APP_URL') . '/api/payments/success?intent_id={PAYMENT_INTENT_ID}';
        $cancelUrl = env('APP_URL') . '/api/payments/cancel';
        // $successUrl = config('ziina.success_url');
        // $cancelUrl = config('ziina.cancel_url');

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

            return $this->sendResponse($intent['redirect_url']);
        } catch (\Exception $e) {
            $subscription->update(['status' => 'cancelled']);

            return $this->sendError($e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $intentId = $request->query('intent_id');

        if (! $intentId) {
            return $this->sendError('Invalid payment confirmation.');
        }

        $subscription = Subscription::where('ziina_payment_intent_id', $intentId)->first();

        if (! $subscription) {
            return $this->sendError('Subscription not found.');
        }

        if ($subscription->status === 'active') {
            return $this->sendSuccess('Subscription is already active.');
        }

        try {
            $intent = $this->ziina->getPaymentIntent($intentId);

            if (($intent['status'] ?? '') === 'completed') {
                $subscription->update(['status' => 'active']);

                return $this->sendSuccess('Payment successful! Your subscription is now active.');
            }

            return $this->sendError('Payment is not completed yet.');
        } catch (\Exception $e) {
            return $this->sendError('Unable to verify payment.');
        }
    }

    public function cancel()
    {
        return $this->sendSuccess('Payment was cancelled.');
    }
}
