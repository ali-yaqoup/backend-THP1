<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function register(RegisterRequest $request)
    {
        try {
            // إنشاء المستخدم
            $user = User::create([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
            ]);

            // إرسال رابط التحقق عبر البريد الإلكتروني
            $user->sendEmailVerificationNotification();

            return response()->json([
                'message' => 'User registered successfully! Please check your email to verify your account.',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
