<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPost;

class FormPostController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'minimum_budget' => 'required|numeric',
            'maximum_budget' => 'required|numeric',
            'deadline' => 'required|date',
            'category' => 'required|string',
            'location' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $postData = $request->only([
            'title', 'description', 'minimum_budget', 'maximum_budget',
            'deadline', 'category', 'location'
        ]);

        if ($request->hasFile('attachment')) {
            $filename = time() . '.' . $request->file('attachment')->extension();
            $request->file('attachment')->move(public_path('assets'), $filename);
            $postData['attachments'] = '/assets/' . $filename;
        }

        $postData['user_id'] = 1;
        $postData['status'] = 'active';

        $post = FormPost::create($postData);

        return response()->json($post, 201);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {

        $posts = FormPost::with('user')->get();
        return response()->json($posts);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $post = FormPost::with('user')->find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        return response()->json($post);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $post = FormPost::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'minimum_budget' => 'required|numeric',
            'maximum_budget' => 'required|numeric',
            'deadline' => 'required|date',
            'category' => 'required|string',
            'location' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = FormPost::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->minimum_budget = $request->input('minimum_budget');
        $post->maximum_budget = $request->input('maximum_budget');
        $post->deadline = $request->input('deadline');
        $post->category = $request->input('category');
        $post->location = $request->input('location');

        if ($request->hasFile('attachment')) {
            $filename = time() . '.' . $request->file("attachment")->extension();
            $request->file("attachment")->move(public_path("assets"), $filename);
            $post->attachments = '/assets/' . $filename;
        }

        $post->save();

        return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);
    }
}
