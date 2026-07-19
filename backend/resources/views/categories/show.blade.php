@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(isset($category))
        {{-- Category Header --}}
        <div class="mb-8 animate-fade-in-up">
            <div class="flex items-center space-x-2 rtl:space-x-reverse text-sm text-muted-foreground mb-2">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">{{ __('messages.home') }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-foreground font-medium">{{ $category->name }}</span>
            </div>

            <div class="bg-card border border-border p-6">
                <div class="flex items-start space-x-4 rtl:space-x-reverse">
                    <div class="w-12 h-12 bg-[#8B0000] flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-lg font-bold">{{ substr($category->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-foreground">{{ $category->name }}</h1>
                        @if($category->description)
                            <p class="text-muted-foreground mt-2">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
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
                    {{-- Empty State --}}
                    <div class="text-center py-16 bg-card border border-border">
                        <svg class="w-16 h-16 text-muted-foreground mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-muted-foreground mb-2">{{ __('messages.no_articles_in_category') }}</h3>
                        <p class="text-muted-foreground/60 text-sm mb-4">{{ __('messages.no_articles_in_category_desc') }}</p>
                        <a href="{{ route('articles.index') }}"
                           class="inline-block bg-[#8B0000] hover:bg-[#8B0000]/80 text-white text-sm font-medium px-5 py-2.5 transition-colors">
                            {{ __('messages.view_all') }}
                        </a>
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
    @else
        {{-- Category not found --}}
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-muted-foreground mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-muted-foreground mb-2">{{ __('messages.category_not_found') }}</h3>
            <a href="{{ route('articles.index') }}" class="text-primary hover:text-primary-dark text-sm font-medium transition-colors">
                &larr; {{ __('messages.back_to_articles') }}
            </a>
        </div>
    @endif
</div>
@endsection
