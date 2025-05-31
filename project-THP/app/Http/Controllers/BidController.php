<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{public function index(): \Illuminate\Http\JsonResponse
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

    public function getBidsByPost($postId): \Illuminate\Http\JsonResponse
    {
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
