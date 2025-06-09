<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPost;
use App\Models\User;

class AdminController extends Controller
{
   
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

  
    public function getFormPosts()
    {
        return response()->json(FormPost::with('user')->get());
    }

    public function countRemovedPosts()
    {
        return response()->json([
            'count' => FormPost::onlyTrashed()->count()
        ]);
    }

    public function deleteFormPost($id)
    {
        $post = FormPost::find($id);
        if (!$post) {
            return response()->json(['message' => 'Form post not found'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Form post deleted successfully']);
    }

    public function countFormPosts()
    {
        return response()->json(['count' => FormPost::count()]);
    }

    public function countArtisans()
    {
        return response()->json([
            'count' => User::where('account_type', 'Artisan')->count()
        ]);
    }

    public function getAllUsers()
    {
        return response()->json(
            User::select('id', 'full_name', 'user_type', 'created_at', 'status')->get()
        );
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function countUsers()
    {
        return response()->json(['count' => User::count()]);
    }

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

    public function countDeletedUsers()
    {
        return response()->json(['count' => User::onlyTrashed()->count()]);
    }

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
