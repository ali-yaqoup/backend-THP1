<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPost;
use App\Models\User;

class AdminController extends Controller
{
    // Get all form posts
    public function getFormPosts()
    {
        return response()->json(FormPost::all());
    }

    // Delete a form post by ID
    public function deleteFormPost($id)
    {
        $post = FormPost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Form post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Form post deleted successfully'], 200);
    }

    // Get count of all form posts
    public function countFormPosts()
    {
        return response()->json(['count' => FormPost::count()]);
    }

    // Get count of users with account_type = 'Artisan'
    public function countArtisans()
    {
        return response()->json([
            'count' => User::where('account_type', 'Artisan')->count()
        ]);
    }
}
