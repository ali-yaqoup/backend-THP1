<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;

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
            ]);

            return response()->json([
                'message' => 'User registered successfully!',
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
