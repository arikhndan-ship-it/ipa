<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Journalist;

class AuthorController extends Controller
{
    public function show(User $user)
    {
        $articles = $user->articles()
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Load the journalist profile linked to this user
        $journalist = Journalist::where('user_id', $user->id)->first();

        // Determine name and bio - use journalist translation or fall back to user fields
        $locale = app()->getLocale();
        $authorName = $journalist?->name ?? $user->name;
        $authorBio = $journalist?->bio ?? $user->bio;
        
        // For journalist with translations, get the localized version
        if ($journalist && $journalist->relationLoaded('translations')) {
            $trans = $journalist->translations->firstWhere('locale', $locale);
            if ($trans) {
                $authorName = $trans->name ?: $authorName;
                $authorBio = $trans->bio ?: $authorBio;
            }
        }

        // Determine image URL
        $authorImage = $journalist?->image_url;
        if (!$authorImage && $user->avatar) {
            if (str_starts_with($user->avatar, 'http://') || str_starts_with($user->avatar, 'https://')) {
                $authorImage = $user->avatar;
            } else {
                $authorImage = url($user->avatar);
            }
        }

        return view('pages.author', compact(
            'user', 'articles', 'journalist',
            'authorName', 'authorBio', 'authorImage'
        ));
    }
}
