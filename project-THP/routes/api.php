<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormPostController;

// تسجيل مستخدم جديد
Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// إدارة المنشورات
Route::post('/posts', [FormPostController::class, 'store']);
Route::get('/posts', [FormPostController::class, 'index']);
Route::delete('/posts/{id}', [FormPostController::class, 'destroy']);
Route::put('/posts/{id}', [FormPostController::class, 'update']);
Route::get('/posts/{id}', [FormPostController::class, 'show']);
