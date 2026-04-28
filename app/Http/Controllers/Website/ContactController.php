<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

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

        $validated = $request->validate($rules);

        ContactMessage::create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'] ?? $request->user()?->name,
            'email' => $validated['email'] ?? $request->user()?->email,
            'message' => $validated['message'],
        ]);

        return redirect()->route('website.landing-page')->with('success', __('تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.'))->withFragment('landingContact');
    }
}
