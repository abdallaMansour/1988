<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationCodeNotification;
use App\Services\WhatsAppOTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function sendEmailCode()
    {
        $user = Auth::guard('api')->user();
        if ($user->email_verified_at) {
            return $this->sendError('Email already verified.');
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

        return $this->sendSuccess('Email verification code sent successfully.');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::guard('api')->user();
        if ($user->email_verified_at) {
            return $this->sendError('Email already verified.');
        }

        $record = DB::table('verification_codes')
            ->where('user_id', $user->id)
            ->where('type', 'email')
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return $this->sendError('Invalid or expired verification code.');
        }

        $user->update(['email_verified_at' => now()]);
        DB::table('verification_codes')->where('id', $record->id)->delete();

        return $this->sendSuccess('Email verified successfully.');
    }

    public function sendPhoneCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
        ]);

        $user = Auth::guard('api')->user();
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
            return $this->sendError('Failed to send verification code.');
        }

        $user->update(['phone' => $phone]);

        return $this->sendSuccess('Phone verification code sent successfully.');
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::guard('api')->user();
        if ($user->phone_verified_at) {
            return $this->sendError('Phone already verified.');
        }

        if (!$user->phone) {
            return $this->sendError('Please enter the phone number and send the code first.');
        }

        $record = DB::table('verification_codes')
            ->where('user_id', $user->id)
            ->where('type', 'phone')
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return $this->sendError('Invalid or expired verification code.');
        }

        $user->update(['phone_verified_at' => now()]);
        DB::table('verification_codes')->where('id', $record->id)->delete();

        return $this->sendSuccess('Phone verified successfully.');
    }
}
