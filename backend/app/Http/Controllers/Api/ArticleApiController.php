<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Notification;
use Illuminate\Http\Request;

class ArticleApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['author', 'categories'])
            ->published();

        // Filter by category
        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Filter by author
        if ($request->has('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Featured filter
        if ($request->has('featured') && $request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Breaking filter
        if ($request->has('breaking') && $request->boolean('breaking')) {
            $query->where('is_breaking', true);
        }

        $articles = $query->orderBy('published_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($articles);
    }

    public function show(Article $article)
    {
        if ($article->status !== 'published') {
            return response()->json(['message' => 'Not found'], 404);
        }

        $article->load(['author', 'categories', 'approvedComments']);

        return response()->json($article);
    }

    public function incrementView($id)
    {
        Article::where('id', $id)->increment('view_count');
        return response()->json(['success' => true]);
    }
}
