<?php

use App\Http\Controllers\BidController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormPostController;
use App\Http\Controllers\AdminController;

Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/posts', [FormPostController::class, 'store']);
Route::get('/posts', [FormPostController::class, 'index']);
Route::delete('/posts/{id}', [FormPostController::class, 'destroy']);
Route::put('/posts/{id}', [FormPostController::class, 'update']);
Route::get('/posts/{id}', [FormPostController::class, 'show']);

Route::get('/admin/form-posts', [AdminController::class, 'getFormPosts']);
Route::delete('/admin/form-posts/{id}', [AdminController::class, 'deleteFormPost']);
Route::get('/admin/form-posts-count', [AdminController::class, 'countFormPosts']);
Route::get('/admin/artisan-count', [AdminController::class, 'countArtisans']);

Route::get('/posts/bids/{postId}', [BidController::class, 'getBidsByPost']);
Route::put('/bids/{id}/status', [BidController::class, 'updateStatus']);
