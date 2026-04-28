<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationCodeNotification;
use App\Services\WhatsAppOTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isFullyVerified()) {
            return redirect()->route('dashboard.index');
        }

        return view('dashboard.verification.index');
    }

    public function sendEmailCode()
    {
        $user = auth()->user();
        if ($user->email_verified_at) {
            return back()->with('info', __('البريد الإلكتروني مؤكد مسبقاً.'));
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15);

        DB::table('verification_codes')
            ->where('user_id', $user->id)
            ->where('type', 'email')
            ->delete();

        DB::table('verification_codes')->insert([
            'user_id' => $user->id,
            'type' => 'email',
            'target' => $user->email,
            'code' => $code,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->notify(new EmailVerificationCodeNotification($code));

        return back()->with('success', __('تم إرسال رمز التحقق إلى بريدك الإلكتروني.'));
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();
        if ($user->email_verified_at) {
            return redirect()->route('dashboard.verification.index')->with('info', __('البريد الإلكتروني مؤكد مسبقاً.'));
        }

        $record = DB::table('verification_codes')
            ->where('user_id', $user->id)
            ->where('type', 'email')
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['code' => __('رمز التحقق غير صحيح أو منتهي الصلاحية.')]);
        }

        $user->update(['email_verified_at' => now()]);
        DB::table('verification_codes')->where('id', $record->id)->delete();

        return redirect()->route('dashboard.verification.index')->with('success', __('تم تأكيد البريد الإلكتروني بنجاح.'));
    }

    public function sendPhoneCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
        ]);

        $user = auth()->user();
        $phone = $request->phone;

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15);

        DB::table('verification_codes')
            ->where('user_id', $user->id)
            ->where('type', 'phone')
            ->delete();

        DB::table('verification_codes')->insert([
            'user_id' => $user->id,
            'type' => 'phone',
            'target' => $phone,
            'code' => $code,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $whatsapp = new WhatsAppOTPService();
        $result = $whatsapp->sendOTP($phone, $code, false);

        if (!($result['success'] ?? false)) {
            return back()->withErrors(['phone' => __('فشل إرسال الرمز. يرجى المحاولة لاحقاً.')]);
        }

        $user->update(['phone' => $phone]);

        return back()->with('success', __('تم إرسال رمز التحقق إلى واتساب.'));
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();
        if ($user->phone_verified_at) {
            return redirect()->route('dashboard.verification.index')->with('info', __('رقم الهاتف مؤكد مسبقاً.'));
        }

        if (!$user->phone) {
            return back()->withErrors(['code' => __('يرجى إدخال رقم الهاتف وإرسال الرمز أولاً.')]);
        }

        $record = DB::table('verification_codes')
            ->where('user_id', $user->id)
            ->where('type', 'phone')
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['code' => __('رمز التحقق غير صحيح أو منتهي الصلاحية.')]);
        }

        $user->update(['phone_verified_at' => now()]);
        DB::table('verification_codes')->where('id', $record->id)->delete();

        return redirect()->route('dashboard.verification.index')->with('success', __('تم تأكيد رقم الهاتف بنجاح.'));
    }
}
