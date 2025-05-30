<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controllerjobs
{
    public function login(Request $request)
    {



        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        if ($user->status !== 'approved') {
            return response()->json([
                'message' => 'Your account is not approved or has been rejected.',
                'status' => $user->status
            ], 403);
        }


        return response()->json([
            'message' => 'Login successful.',
            'user' => $user
        ], 200);
    }
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
