<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Journalist;
use Illuminate\Http\JsonResponse;

class AuthorApiController extends Controller
{
    public function show(int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);
        $journalist = Journalist::where('user_id', $userId)->first();

        $locale = app()->getLocale();
        $name = $journalist?->name ?? $user->name;
        $bio = $journalist?->bio ?? $user->bio;

        // For journalist with translations, get localized version
        if ($journalist && $journalist->relationLoaded('translations')) {
            $trans = $journalist->translations->firstWhere('locale', $locale);
            if ($trans) {
                $name = $trans->name ?: $name;
                $bio = $trans->bio ?: $bio;
            }
        }

        // Image: journalist image or fall back to user avatar
        $image = $journalist?->image_url;
        if (!$image && $user->avatar) {
            if (str_starts_with($user->avatar, 'http://') || str_starts_with($user->avatar, 'https://')) {
                $image = $user->avatar;
            } else {
                $image = url($user->avatar);
            }
        }

        return response()->json([
            'id' => $user->id,
            'name' => $name,
            'bio' => $bio,
            'image' => $image,
        ]);
    }
}
