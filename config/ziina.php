<?php

return [
    'api_base' => env('ZIINA_API_BASE', 'https://api-v2.ziina.com/api'),
    'api_key' => env('ZIINA_API_KEY'),
    'success_url' => env('APP_URL') . '/payments/success?intent_id={PAYMENT_INTENT_ID}',
    'cancel_url' => env('APP_URL') . '/payments/cancel',
    'failure_url' => env('ZIINA_FAILURE_URL', env('ZIINA_CANCEL_URL', env('APP_URL') . '/payments/cancel')),
    'currency' => env('ZIINA_CURRENCY', 'AED'),
    'sandbox' => env('ZIINA_MODE', 'sandbox') === 'sandbox',
];
