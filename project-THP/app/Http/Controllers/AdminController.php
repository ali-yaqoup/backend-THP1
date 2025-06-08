<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPost;
use App\Models\User;

class AdminController extends Controller
{
    // ✅ Get authenticated user info
    public function getAuthenticatedUserInfo()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'id'         => $user->id,
            'full_name'  => $user->full_name,
            'email'      => $user->email,
            'user_type'  => $user->user_type,
            'status'     => $user->status,
            'created_at' => $user->created_at,
        ]);
    }

    // ✅ Get all form posts with related user
    public function getFormPosts()
    {
        return response()->json(FormPost::with('user')->get());
    }

    // ✅ Count soft deleted form posts
    public function countRemovedPosts()
    {
        return response()->json([
            'count' => FormPost::onlyTrashed()->count()
        ]);
    }

    // ✅ Delete form post by ID
    public function deleteFormPost($id)
    {
        $post = FormPost::find($id);
        if (!$post) {
            return response()->json(['message' => 'Form post not found'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Form post deleted successfully']);
    }

    // ✅ Count all form posts
    public function countFormPosts()
    {
        return response()->json(['count' => FormPost::count()]);
    }

    // ✅ Count users with account_type = 'Artisan'
    public function countArtisans()
    {
        return response()->json([
            'count' => User::where('account_type', 'Artisan')->count()
        ]);
    }

    // ✅ Get all users with specific fields
    public function getAllUsers()
    {
        return response()->json(
            User::select('id', 'full_name', 'user_type', 'created_at', 'status')->get()
        );
    }

    // ✅ Delete user by ID
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    // ✅ Count all users
    public function countUsers()
    {
        return response()->json(['count' => User::count()]);
    }

    // ✅ Get user stats
    public function getUserStats()
    {
        return response()->json([
            'total'     => User::count(),
            'artisans'  => User::where('user_type', 'artisan')->count(),
            'employers' => User::where('user_type', 'job_owner')->count(),
            'approved'  => User::where('status', 'approved')->count(),
            'rejected'  => User::where('status', 'rejected')->count(),
        ]);
    }

    // ✅ Count soft deleted users
    public function countDeletedUsers()
    {
        return response()->json(['count' => User::onlyTrashed()->count()]);
    }

    // ✅ Update user status (approved or rejected)
    public function updateUserStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'User status updated successfully']);
    }

    // ✅ Update user name and type
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'user_type' => 'required|in:artisan,job_owner'
        ]);

        $user = User::findOrFail($id);
        $user->full_name = $request->input('full_name');
        $user->user_type = $request->input('user_type');
        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }
}
