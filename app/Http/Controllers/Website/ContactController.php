<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'message' => ['required', 'string', 'max:5000'],
        ];

        if ($request->user()) {
            // مسجل دخول - لا حاجة للاسم والبريد
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'email'];
        }

        if ($this->turnstileConfigured()) {
            $rules['cf-turnstile-response'] = ['required', 'string'];
        }

        $validated = $request->validate($rules);

        if ($this->turnstileConfigured()) {
            $verify = Http::timeout(10)->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('services.turnstile.secret_key'),
                'response' => $validated['cf-turnstile-response'],
                'remoteip' => $request->ip(),
            ])->json();

            if (! ($verify['success'] ?? false)) {
                return redirect()->route('website.landing-page')
                    ->withErrors(['cf-turnstile-response' => 'فشل التحقق الأمني. حاول مرة أخرى.'])
                    ->withInput()
                    ->withFragment('landingContact');
            }
        }

        ContactMessage::create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'] ?? $request->user()?->name,
            'email' => $validated['email'] ?? $request->user()?->email,
            'message' => $validated['message'],
        ]);

        return redirect()->route('website.landing-page')->with('success', __('تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.'))->withFragment('landingContact');
    }

    private function turnstileConfigured(): bool
    {
        $site = config('services.turnstile.site_key');
        $secret = config('services.turnstile.secret_key');

        return is_string($site) && $site !== '' && is_string($secret) && $secret !== '';
    }
}
