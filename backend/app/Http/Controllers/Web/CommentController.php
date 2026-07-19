<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'body' => 'required|string|min:3',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $article->comments()->create([
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'body' => $validated['body'],
            'parent_id' => $validated['parent_id'] ?? null,
            'is_approved' => false,
        ]);

        return back()->with('success', __('messages.comment_pending'));
    }
}
