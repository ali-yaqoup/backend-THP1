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
        // تحقق من صحة OTP وصلاحيته
        $otpRecord = LoginOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'رمز التحقق غير صحيح أو منتهي'], 401);
        }

        // جلب المستخدم حسب البريد الإلكتروني
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود'], 404);
        }

        // إنشاء توكن جديد
        $tokenResult = $user->createToken('auth_token');
        $token = $tokenResult->plainTextToken;

        // حذف سجل OTP بعد التأكد من صحته
        $otpRecord->delete();

        // التحقق من وجود التوكن في قاعدة البيانات
        $tokenExists = \DB::table('personal_access_tokens')
            ->where('tokenable_id', $user->id)
            ->where('name', 'auth_token')
            ->exists();

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'token_stored_in_db' => $tokenExists,
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

