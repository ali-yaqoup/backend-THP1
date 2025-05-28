<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPost;

class FormPostController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $this->validatePost($request);

        if ($request->hasFile('attachment')) {
            $validated['attachments'] = $this->handleAttachment($request);
        }

        $validated['user_id'] = 1;
        $validated['status'] = 'active';

        $post = FormPost::create($validated);

        return response()->json($post, 201);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(FormPost::all());
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $post = FormPost::find($id);
        return $post
            ? response()->json($post)
            : response()->json(['message' => 'Post not found'], 404);
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
        $post = FormPost::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validated = $this->validatePost($request);

        if ($request->hasFile('attachment')) {
            $validated['attachments'] = $this->handleAttachment($request);
        }

        $post->update($validated);

        return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'minimum_budget' => 'required|numeric',
            'maximum_budget' => 'required|numeric',
            'deadline' => 'required|date',
            'category' => 'required|string',
            'location' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    private function handleAttachment(Request $request): string
    {
        $filename = time() . '.' . $request->file('attachment')->extension();
        $request->file('attachment')->move(public_path('assets'), $filename);
        return '/assets/' . $filename;
    }
}