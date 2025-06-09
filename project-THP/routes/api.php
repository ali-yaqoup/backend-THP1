<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Http\Middleware\IsEmployer;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormPostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidController;

// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login-step1', [AuthController::class, 'loginStep1']);
Route::post('/login-step2', [AuthController::class, 'loginStep2']);
Route::post('/password/send-otp', [AuthController::class, 'sendResetPasswordOtp']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

// Authenticated & Verified Users
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Employer Routes
Route::middleware(['auth:sanctum','employer'])->group(function () {
    Route::post('/posts', [FormPostController::class, 'store']);
    Route::put('/posts/{id}', [FormPostController::class, 'update']);
    Route::delete('/posts/{id}', [FormPostController::class, 'destroy']);
    Route::get('/posts/bids/{postId}', [BidController::class, 'getBidsByPost']);
    Route::put('/bids/{id}/status', [BidController::class, 'updateStatus']);
    Route::get('/posts', [FormPostController::class, 'index']);
    Route::get('/posts/{id}', [FormPostController::class, 'show']);
});

// Admin Routes
Route::middleware(['auth:sanctum', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Admin - Users & Form Posts Management
    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::patch('/users/{id}/status', [AdminController::class, 'updateUserStatus']);

    Route::get('/admin/form-posts', [AdminController::class, 'getFormPosts']);
    Route::delete('/admin/form-posts/{id}', [AdminController::class, 'deleteFormPost']);

    // Stats
    Route::get('/admin/form-posts-count', [AdminController::class, 'countFormPosts']);
    Route::get('/admin/form-posts-deleted-count', [AdminController::class, 'countRemovedPosts']);
    Route::get('/admin/artisan-count', [AdminController::class, 'countArtisans']);
    Route::get('/admin/user-count', [AdminController::class, 'countUsers']);
    Route::get('/admin/users-deleted-count', [AdminController::class, 'countDeletedUsers']);
    Route::get('/admin/user-stats', [AdminController::class, 'getUserStats']);
});

// Email Verification
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'المستخدم غير موجود'], 404);
    }

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'رابط التحقق غير صالح'], 403);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'تم التحقق من البريد الإلكتروني سابقًا']);
    }

    $user->markEmailAsVerified();
    $user->update(['status' => 'pending']);
    event(new Verified($user));

    return response()->json(['message' => 'تم تفعيل البريد الإلكتروني وتحديث الحالة إلى active']);
})->middleware(['signed'])->name('verification.verify');

// Duplicate password reset routes (already declared above)
Route::post('/password/send-otp', [AuthController::class, 'sendResetPasswordOtp']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
