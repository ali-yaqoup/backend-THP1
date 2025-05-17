<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\AdminController;

Route::get('/admin/form-posts', [AdminController::class, 'getFormPosts']);
Route::delete('/admin/form-posts/{id}', [AdminController::class, 'deleteFormPost']);
Route::get('/admin/form-posts-count', [AdminController::class, 'countFormPosts']);
Route::get('/admin/artisan-count', [AdminController::class, 'countArtisans']);
