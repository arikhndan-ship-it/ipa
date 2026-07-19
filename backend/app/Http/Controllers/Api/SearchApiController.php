<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json(['data' => []]);
        }

        $articles = Article::with(['author', 'categories'])
            ->published()
            ->where(function ($q) use ($query) {
                $q->whereHas('translations', function ($t) use ($query) {
                    $t->where('title', 'like', "%{$query}%")
                      ->orWhere('body', 'like', "%{$query}%");
                });
            })
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json($articles);
    }
}
