@extends('layouts.app')

@section('title', $user->name . ' - ' . __('messages.author_articles', ['name' => $user->name]))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        {{-- Author Info Header --}}
        <div class="bg-card border border-border rounded-lg p-6 mb-8 text-center">
            @if($authorImage)
                <img src="{{ $authorImage }}" alt="{{ $authorName }}"
                     class="w-20 h-20 rounded-full object-cover mx-auto mb-3 border-2 border-primary">
            @else
                <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-3 border-2 border-primary">
                    <span class="text-primary text-3xl font-bold">{{ substr($authorName, 0, 1) }}</span>
                </div>
            @endif
            <h1 class="text-2xl font-bold text-foreground">{{ $authorName }}</h1>
            @if($authorBio)
                <p class="text-muted-foreground mt-3 max-w-lg mx-auto">{{ $authorBio }}</p>
            @endif
        </div>

        <h2 class="text-xl font-bold text-foreground mb-6">{{ __('messages.articles') }}</h2>

        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($articles as $index => $article)
                    <x-article-card :article="$article" :index="$index" />
                @endforeach
            </div>
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-muted-foreground mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <p class="text-muted-foreground">{{ __('messages.no_articles') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
