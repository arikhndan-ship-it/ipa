@extends('layouts.app')

@section('content')
<article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
            {{-- Breadcrumb --}}
            <nav class="flex items-center text-sm text-gray-500 mb-4 space-x-2 rtl:space-x-reverse">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">{{ __('messages.home') }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('articles.index') }}" class="hover:text-primary transition-colors">{{ __('messages.latest_news') }}</a>
                @if($article->category_name)
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('articles.index') }}?category={{ $article->categories->first()?->slug }}" class="hover:text-primary transition-colors">{{ $article->category_name }}</a>
                @endif
            </nav>

            @if(isset($article))
                {{-- Category Badge --}}
                @if($article->category_name)
                <span class="inline-block bg-primary text-white text-xs font-bold uppercase px-3 py-1 rounded mb-4">
                    {{ $article->category_name }}
                </span>
                @endif

                {{-- Title --}}
                <h1 class="text-3xl md:text-4xl font-bold text-foreground leading-tight mb-4">
                    {{ $article->title }}
                </h1>

                {{-- Author & Date --}}
                <div class="flex flex-wrap items-center text-sm text-gray-500 mb-6 space-x-4 rtl:space-x-reverse">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full overflow-hidden mr-2 rtl:ml-2 rtl:mr-0 flex-shrink-0">
                            @php
                                $authorImg = $journalist?->image_url ?? $article->author_image;
                            @endphp
                            @if($authorImg)
                                <img src="{{ $authorImg }}" alt="{{ $article->author_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-primary/10 flex items-center justify-center">
                                    <span class="text-primary text-xs font-bold">
                                        {{ $article->author_name ? substr($article->author_name, 0, 1) : 'X' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        @if($article->author)
                            <a href="{{ route('author.show', $article->author) }}" class="hover:text-primary transition-colors">
                                {{ $article->author_name }}
                            </a>
                        @else
                            <span>{{ $article->author_name ?? __('messages.site_name') }}</span>
                        @endif
                    </div>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 rtl:ml-1 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('F d, Y') : '' }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 rtl:ml-1 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('messages.min_read', ['min' => max(1, ceil(mb_strlen(strip_tags($article->body)) / 1000))]) }}
                    </span>
                </div>

                {{-- Social Share Buttons --}}
                <div class="flex items-center flex-wrap gap-2 mb-6">
                    <span class="text-sm font-medium text-gray-400 mr-2 rtl:ml-2 rtl:mr-0">{{ __('messages.share') }}:</span>
                    {{-- Native Share (mobile) --}}
                    <button onclick="if(navigator.share){navigator.share({title:'{{ $article->title }}',text:'{{ $article->title }}',url:'{{ request()->url() }}'}).catch(()=>{})}else{window.open('https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}','_blank')}"
                            class="bg-gray-700 hover:bg-gray-600 text-white p-2 rounded transition-colors" aria-label="Share">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                    </button>
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                       target="_blank" rel="noopener"
                       class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded transition-colors" aria-label="Share on Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    {{-- Telegram --}}
                    <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}"
                       target="_blank" rel="noopener"
                       class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded transition-colors" aria-label="Share on Telegram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </a>
                    {{-- Copy Link --}}
                    <button onclick="navigator.clipboard.writeText('{{ request()->url() }}').then(()=>{const btn=this;const orig=btn.innerHTML;btn.innerHTML='<span class=text-xs>✓</span>';setTimeout(()=>btn.innerHTML=orig,2000)}).catch(()=>{})"
                            class="bg-gray-600 hover:bg-gray-500 text-white p-2 rounded transition-colors" aria-label="Copy link">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </button>
                </div>

                {{-- Featured Image --}}
                @if($article->featured_image)
                <div class="mb-6 rounded-lg overflow-hidden">
                    <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-auto object-cover">
                </div>
                @endif

                {{-- Article Body --}}
                <div class="prose prose-lg max-w-none prose-headings:text-foreground prose-a:text-primary prose-a:no-underline hover:prose-a:underline prose-img:rounded-lg prose-blockquote:border-primary prose-blockquote:text-foreground mb-8">
                    {!! $article->body !!}
                </div>

                {{-- Previous/Next Navigation --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    @if(isset($previous))
                    <a href="{{ route('articles.show', $previous) }}"
                       class="group p-4 border border-gray-200 rounded-lg hover:border-primary transition-colors">
                        <span class="text-xs text-gray-500 font-medium uppercase">{{ __('messages.previous') }}</span>
                        <h4 class="text-sm font-medium text-foreground group-hover:text-primary mt-1 line-clamp-2 transition-colors">
                            &larr; {{ $previous->title }}
                        </h4>
                    </a>
                    @else
                    <div></div>
                    @endif

                    @if(isset($next))
                    <a href="{{ route('articles.show', $next) }}"
                       class="group p-4 border border-gray-200 rounded-lg hover:border-primary transition-colors text-right">
                        <span class="text-xs text-gray-500 font-medium uppercase">{{ __('messages.next') }}</span>
                        <h4 class="text-sm font-medium text-foreground group-hover:text-primary mt-1 line-clamp-2 transition-colors">
                            {{ $next->title }} &rarr;
                        </h4>
                    </a>
                    @endif
                </div>

                {{-- Comments Section --}}
                <div class="border-t border-gray-200 pt-8 mb-8" x-data="{ showForm: false }">
                    <h2 class="text-xl font-bold text-foreground mb-6">{{ __('messages.comments') }}</h2>

                    {{-- Comments List --}}
                    @if(isset($comments) && $comments->isNotEmpty())
                        <div class="space-y-4 mb-8">
                            @foreach($comments as $comment)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center mr-2 rtl:ml-2 rtl:mr-0">
                                            <span class="text-primary text-xs font-bold">{{ substr($comment->author_name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-foreground">{{ $comment->author_name }}</span>
                                            <span class="text-xs text-gray-500 ml-2 rtl:mr-2 rtl:ml-0">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $comment->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mb-6">{{ __('messages.no_comments') }}</p>
                    @endif

                    {{-- Leave Comment Button / Form --}}
                    <button @click="showForm = !showForm"
                            class="bg-primary hover:bg-primary-dark text-white text-sm font-medium px-5 py-2.5 rounded transition-colors">
                        {{ __('messages.leave_comment') }}
                    </button>

                    <div x-show="showForm" x-cloak x-transition class="mt-6">
                        <form action="{{ route('comments.store', $article) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="comment-name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.your_name') }}</label>
                                    <input type="text"
                                           name="author_name"
                                           id="comment-name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                                           required>
                                </div>
                                <div>
                                    <label for="comment-email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.your_email') }}</label>
                                    <input type="email"
                                           name="author_email"
                                           id="comment-email"
                                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                                           required>
                                </div>
                            </div>
                            <div>
                                <label for="comment-body" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.your_message') }}</label>
                                <textarea name="body"
                                          id="comment-body"
                                          rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                                          required></textarea>
                            </div>
                            <button type="submit"
                                    class="bg-primary hover:bg-primary-dark text-white text-sm font-medium px-6 py-2.5 rounded transition-colors">
                                {{ __('messages.send') }}
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Article not found --}}
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">{{ __('messages.article_not_found') ?? 'Article not found' }}</h3>
                    <a href="{{ route('articles.index') }}" class="text-primary hover:text-primary-dark text-sm font-medium transition-colors">
                        &larr; {{ __('messages.back_to_articles') }}
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
</article>

{{-- Related Articles (scroll-reveal) --}}
@if(isset($relatedArticles) && $relatedArticles->isNotEmpty())
<section class="bg-gray-50 border-t border-gray-200 mt-8 reveal" x-intersect="$el.classList.add('revealed')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h2 class="text-2xl font-bold text-foreground mb-6">{{ __('messages.related_articles') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedArticles as $related)
                <x-article-card :article="$related" variant="default" />
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
