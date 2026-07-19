@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header (fade-in-up) --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-bold text-foreground">{{ __('messages.latest_news') }}</h1>
        <p class="text-gray-600 mt-2">{{ __('messages.browse_all_articles') }}</p>
    </div>

    {{-- Category Filter Tabs (from database) --}}
    <div class="mb-8 overflow-x-auto animate-fade-in-up delay-200">
        <div class="flex flex-wrap gap-2 pb-2">
            <a href="{{ route('articles.index') }}"
               class="px-4 py-2 text-sm font-bold uppercase tracking-wider transition-colors whitespace-nowrap
                      {{ !request('category') ? 'bg-[#8B0000] text-white' : 'bg-[#8B0000]/20 text-white hover:bg-[#8B0000]/40' }}">
                {{ __('messages.cat_all') }}
            </a>
            @if(isset($categories) && $categories->isNotEmpty())
                @foreach($categories as $cat)
                    <a href="{{ route('articles.index') }}?category={{ $cat->slug }}"
                       class="px-4 py-2 text-sm font-bold uppercase tracking-wider transition-colors whitespace-nowrap
                              {{ request('category') === $cat->slug ? 'bg-[#8B0000] text-white' : 'bg-[#8B0000]/20 text-white hover:bg-[#8B0000]/40' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Articles Grid --}}
        <div class="lg:col-span-2">
            @if(isset($articles) && $articles->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($articles as $article)
                        <x-article-card :article="$article" variant="default" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @else
                {{-- No Articles State --}}
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">{{ __('messages.no_articles') }}</h3>
                    <p class="text-gray-400 text-sm">{{ __('messages.no_articles_desc') }}</p>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <x-sidebar
                :recentArticles="$recentArticles ?? collect()"
                :popularArticles="$popularArticles ?? collect()"
                :categories="$categories ?? collect()"
            />
        </div>
    </div>
</div>
@endsection
