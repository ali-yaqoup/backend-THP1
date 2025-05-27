<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginOtp;
use App\Notifications\SendLoginOtp;

class AuthController extends Controller
{
    public function loginStep1(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'بيانات غير صحيحة'], 401);
        }

        $otp = rand(100000, 999999);

        LoginOtp::updateOrCreate(
            ['email' => $user->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10)
            ]
        );

        $user->notify(new SendLoginOtp($otp));

        return response()->json(['message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني']);
    }

    public function loginStep2(Request $request)
    {
        $otpRecord = LoginOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'رمز التحقق غير صحيح أو منتهي'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        $otpRecord->delete();

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'user' => $user
        ]);
    }

    // ✅ إرسال رمز التحقق لإعادة تعيين كلمة المرور
    public function sendResetPasswordOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $otp = rand(100000, 999999);

        LoginOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10)
            ]
        );

        $user = User::where('email', $request->email)->first();
        $user->notify(new SendLoginOtp($otp));

        return response()->json(['message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني']);
    }

    // ✅ إعادة تعيين كلمة المرور باستخدام الرمز
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otpRecord = LoginOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'رمز التحقق غير صحيح أو منتهي'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $otpRecord->delete();

        return response()->json(['message' => 'تم إعادة تعيين كلمة المرور بنجاح']);
    }
}
