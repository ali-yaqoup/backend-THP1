<?php

use App\Http\Controllers\BidController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormPostController;
use App\Http\Controllers\AdminController;

// User Registration & Auth
Route::post('/register', [UserController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Form Posts (Public/User)
Route::get('/form-posts', [FormPostController::class, 'index']);
Route::get('/form-posts/{id}', [FormPostController::class, 'show']);
Route::post('/form-posts', [FormPostController::class, 'store']);
Route::put('/form-posts/{id}', [FormPostController::class, 'update']);
Route::delete('/form-posts/{id}', [FormPostController::class, 'destroy']);
Route::get('/form-posts-deleted-count', [FormPostController::class, 'countDeleted']);


Route::post('/posts', [FormPostController::class, 'store']);
Route::get('/posts', [FormPostController::class, 'index']);
Route::delete('/posts/{id}', [FormPostController::class, 'destroy']);
Route::put('/posts/{id}', [FormPostController::class, 'update']);
Route::get('/posts/{id}', [FormPostController::class, 'show']);

// Bids
Route::get('/posts/bids/{postId}', [BidController::class, 'getBidsByPost']);
Route::put('/bids/{id}/status', [BidController::class, 'updateStatus']);

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
Route::get('/admin/user-registrations-per-month', [AdminController::class, 'getUserRegistrationsPerMonth']);
Route::get('/admin/user-stats', [AdminController::class, 'getUserStats']);
