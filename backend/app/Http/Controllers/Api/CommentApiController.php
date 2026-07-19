<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    public function index(Article $article)
    {
        $comments = $article->approvedComments()
            ->with('user')
            ->latest()
            ->get();

        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'body' => 'required|string|min:3',
        ]);

        $comment = Comment::create([
            'article_id' => $validated['article_id'],
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'body' => $validated['body'],
            'is_approved' => false,
        ]);

        return response()->json($comment, 201);
    }
}
