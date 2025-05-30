<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< Updated upstream



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\AdminController;

Route::get('/admin/form-posts', [AdminController::class, 'getFormPosts']);
Route::delete('/admin/form-posts/{id}', [AdminController::class, 'deleteFormPost']);
Route::get('/admin/form-posts-count', [AdminController::class, 'countFormPosts']);
Route::get('/admin/artisan-count', [AdminController::class, 'countArtisans']);
=======
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\AuthController;

Route::post('/register', [UserController::class, 'register']);


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



Route::post('/login-step1', [AuthController::class, 'loginStep1']); 
Route::post('/login-step2', [AuthController::class, 'loginStep2']);  
Route::post('/password/send-otp', [AuthController::class, 'sendResetPasswordOtp']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
>>>>>>> Stashed changes
