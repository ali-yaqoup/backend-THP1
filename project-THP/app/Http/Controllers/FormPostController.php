<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\FormPost;

class FormPostController extends Controller
{
 public function index(Request $request): JsonResponse
{
    // نجيب الـ user_id من المستخدم المسجل دخول
    $userId = $request->user()->id;

    // نسترجع المنشورات الخاصة بهذا المستخدم فقط
    $posts = FormPost::where('user_id', $userId)->get();

    return response()->json($posts);
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

        // 🔒 تحقق من أن المستخدم هو مالك المنشور
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'غير مصرح لك بتعديل هذا المنشور'], 403);
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

        // 🔒 تحقق من أن المستخدم هو مالك المنشور
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'غير مصرح لك بحذف هذا المنشور'], 403);
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

        $validated['user_id'] = Auth::id(); // ✅ تعيين المستخدم الحالي
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
