<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPost;
use Illuminate\Http\JsonResponse;

class FormPostController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(FormPost::all());
    }

    public function show($id): JsonResponse
    {
        $post = FormPost::find($id);
        return $post
            ? response()->json($post)
            : response()->json(['message' => 'Post not found'], 404);
    }

    public function store(Request $request): JsonResponse
    {
        $post = $this->savePost($request);
        return response()->json($post, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $post = FormPost::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $this->savePost($request, $post);

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }

    public function destroy($id): JsonResponse
    {
        $post = FormPost::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    private function savePost(Request $request, FormPost $post = null)
    {
        $validated = $this->validatePost($request);

        if ($request->hasFile('attachment')) {
            $validated['attachments'] = $this->handleAttachment($request);
        }

        $validated['user_id'] = 1; // يمكن تغييره لاحقاً حسب المستخدم الحالي
        $validated['status'] = $post ? $post->status : 'active';

        return $post
            ? tap($post)->update($validated)
            : FormPost::create($validated);
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
