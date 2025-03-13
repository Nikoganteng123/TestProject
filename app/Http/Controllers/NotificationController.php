<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $comments = Comment::where('user_id', $userId)
            ->latest('created_at')
            ->get();

        return response()->json([
            'data' => $comments,
            'message' => 'Notifications retrieved successfully',
        ], 200);
    }

    public function markAsRead($id, Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $comment = Comment::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $comment->read_at = now();
        $comment->save();

        return response()->json([
            'data' => $comment,
            'message' => 'Notification marked as read successfully',
        ], 200);
    }
}