@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Search Header (fade-in-up) --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-bold text-foreground mb-4">
            @if(request('q'))
                {{ __('messages.search_results') }}: "{{ request('q') }}"
            @else
                {{ __('messages.search_results') }}
            @endif
        </h1>

        {{-- Search Form --}}
        <form action="{{ route('search') }}" method="GET" class="max-w-2xl">
            <div class="flex">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="{{ __('messages.search') }}..."
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                       autofocus>
                <button type="submit"
                        class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-r-lg font-medium transition-colors flex items-center space-x-2 rtl:space-x-reverse">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">{{ __('messages.search') }}</span>
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Search Results --}}
        <div class="lg:col-span-2">
            @if(request()->has('q') && trim(request('q')) !== '')
                {{-- Results Count --}}
                <div class="mb-6">
                    <p class="text-sm text-gray-600">
                        @if(isset($articles) && $articles->isNotEmpty())
                            {{ __('messages.search_found', ['count' => $articles->total(), 'query' => request('q')]) }}
                        @else
                            {{ __('messages.search_found_none', ['query' => request('q')]) }}
                        @endif
                    </p>
                </div>

                @if(isset($articles) && $articles->isNotEmpty())
                    {{-- Results List --}}
                    <div class="space-y-4">
                        @foreach($articles as $article)
                            <x-article-card :article="$article" variant="horizontal" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $articles->appends(['q' => request('q')])->links() }}
                    </div>
                @else
                    {{-- No Results --}}
                    <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
                        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-600 mb-2">{{ __('messages.no_results') }}</h3>
                        <p class="text-gray-400 text-sm max-w-md mx-auto mb-6">
                            {{ __('messages.no_results_desc', ['query' => request('q')]) }}
                        </p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <span class="text-sm text-gray-500">{{ __('messages.suggestions') }}:</span>
                            <ul class="text-sm text-gray-500 list-disc list-inside text-left">
                                <li>{{ __('messages.suggestion_check_spelling') }}</li>
                                <li>{{ __('messages.suggestion_use_general') }}</li>
                                <li>{{ __('messages.suggestion_fewer_keywords') }}</li>
                            </ul>
                        </div>
                    </div>
                @endif
            @else
                {{-- Initial state - no search query --}}
                <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">{{ __('messages.search') }}</h3>
                    <p class="text-gray-400 text-sm">{{ __('messages.search_placeholder_desc') }}</p>
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
