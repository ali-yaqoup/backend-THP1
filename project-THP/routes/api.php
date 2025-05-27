<?php
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\AuthController;
// تسجيل المستخدم
Route::post('/register', [UserController::class, 'register']);

// استرجاع بيانات المستخدم (يتطلب تسجيل دخول وتحقق من الإيميل)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'المستخدم غير موجود'], 404);
    }

    // تحقق من صحة الرابط (الهاش)
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'رابط التحقق غير صالح'], 403);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'تم التحقق من البريد الإلكتروني سابقًا']);
    }

    $user->markEmailAsVerified();
    $user->update(['status' => 'active']);
    event(new Verified($user));

    return response()->json(['message' => 'تم تفعيل البريد الإلكتروني وتحديث الحالة إلى active']);
})->middleware(['signed'])->name('verification.verify');



Route::post('/login-step1', [AuthController::class, 'loginStep1']); // البريد + كلمة السر
Route::post('/login-step2', [AuthController::class, 'loginStep2']); // التحقق من الرمز
Route::post('/password/send-otp', [AuthController::class, 'sendResetPasswordOtp']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
