<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ZiinaService
{
    public function createPaymentIntent(float $amount, string $currency, string $message, string $successUrl, string $cancelUrl, ?string $failureUrl = null): array
    {
        $amountInFils = (int) round($amount * 100);

        if ($amountInFils < 200) {
            throw new \InvalidArgumentException('Minimum amount is 2 AED (200 fils)');
        }

        $operationId = Str::uuid()->toString();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('ziina.api_key'),
            'Content-Type' => 'application/json',
        ])->post(config('ziina.api_base') . '/payment_intent', [
            'amount' => $amountInFils,
            'currency_code' => $currency,
            'message' => $message,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'failure_url' => $failureUrl ?? $cancelUrl,
            'test' => config('ziina.sandbox'),
            'allow_tips' => false,
        ]);

        if ($response->failed()) {
            throw new \Exception('Ziina API error: ' . $response->body());
        }

        $data = $response->json();
        $data['operation_id'] = $operationId;

        return $data;
    }

    public function getPaymentIntent(string $intentId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('ziina.api_key'),
        ])->get(config('ziina.api_base') . '/payment_intent/' . $intentId);

        if ($response->failed()) {
            throw new \Exception('Ziina API error: ' . $response->body());
        }

        return $response->json();
    }
}
