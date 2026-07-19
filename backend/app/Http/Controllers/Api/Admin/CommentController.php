<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['article', 'user']);
        
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }
        
        $comments = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 20));
        
        return response()->json($comments);
    }
    
    public function show(Comment $comment)
    {
        $comment->load(['article', 'user', 'replies']);
        return response()->json($comment);
    }
    
    public function approve(Comment $comment)
    {
        $comment->is_approved = true;
        $comment->save();
        return response()->json(['message' => 'Comment approved', 'comment' => $comment]);
    }
    
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}
