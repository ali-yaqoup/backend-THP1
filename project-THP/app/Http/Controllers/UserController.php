<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'full_name' => $validated['full_name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => $validated['user_type'],
                'status' => 'pending',
                
            ]);

            if (method_exists($user, 'sendEmailVerificationNotification')) {
                $user->sendEmailVerificationNotification();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'تم تسجيل المستخدم بنجاح! الرجاء التحقق من بريدك الإلكتروني.',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'فشل تسجيل المستخدم!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
