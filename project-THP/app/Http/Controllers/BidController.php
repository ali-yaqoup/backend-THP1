<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\FormPost;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Bid::with(['formPost', 'user'])->get());
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $bid = Bid::with(['formPost', 'user'])->find($id);

        if (!$bid) {
            return response()->json(['message' => 'Bid not found'], 404);
        }

        return response()->json($bid);
    }

    /**
     * Get all bids submitted on a post created by the current authenticated user (the post owner).
     */
    public function getBidsByPost($postId): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // تحقق أن المستخدم الحالي هو مالك المنشور
        $post = FormPost::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->user_id !== $user->id) {
            return response()->json(['message' => 'You are not the owner of this post'], 403);
        }

        // جلب كل العروض التي تم تقديمها على هذا المنشور
        $bids = Bid::with(['formPost', 'user'])
            ->where('post_id', $postId)
            ->get();

        return response()->json($bids);
    }

    public function updateStatus(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $bid = Bid::find($id);

        if (!$bid) {
            return response()->json(['message' => 'Bid not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $bid->status = $request->status;
        $bid->processed = true;
        $bid->save();

        return response()->json(['message' => 'Bid updated successfully', 'bid' => $bid]);
    }
}
