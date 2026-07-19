@props([
    'recentArticles' => collect(),
    'popularArticles' => collect(),
    'categories' => collect(),
])

<div class="space-y-8">
    {{-- Popular Articles --}}
    @if($popularArticles->isNotEmpty())
    <div class="bg-card border border-border overflow-hidden">
        <div class="px-5 pt-5 pb-0">
            <h3 class="text-base font-bold text-foreground inline-block relative pb-3">
                {{ __('messages.popular') }}
                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#8B0000]"></span>
            </h3>
        </div>
        <div class="p-5 pt-3">
            <div class="space-y-0">
                @foreach($popularArticles as $index => $article)
                <div class="flex items-start gap-3 py-3 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <span class="w-[26px] h-[26px] flex items-center justify-center rounded-full bg-[#8B0000] text-white text-xs font-bold flex-shrink-0">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-foreground leading-snug">
                            <a href="{{ route('articles.show', $article) }}" class="hover:text-[#8B0000] transition-colors line-clamp-2">
                                {{ $article->title }}
                            </a>
                        </h4>
                        <span class="text-xs text-muted-foreground mt-1 block">
                            {{ $article->published_at ? $article->published_at->format('M d, Y') : $article->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Posts --}}
    @if($recentArticles->isNotEmpty())
    <div class="bg-card border border-border overflow-hidden">
        <div class="px-5 pt-5 pb-0">
            <h3 class="text-base font-bold text-foreground inline-block relative pb-3">
                {{ __('messages.recent') }}
                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#8B0000]"></span>
            </h3>
        </div>
        <div class="p-5 pt-3">
            @foreach($recentArticles->take(5) as $article)
                <div class="flex items-start gap-3 py-3 border-b border-border last:border-b-0">
                    @if($article->featured_image)
                    <div class="w-16 h-16 flex-shrink-0">
                        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}"
                             class="w-full h-full object-cover rounded">
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-foreground leading-snug">
                            <a href="{{ route('articles.show', $article) }}" class="hover:text-[#8B0000] transition-colors line-clamp-2">
                                {{ $article->title }}
                            </a>
                        </h4>
                        <span class="text-xs text-muted-foreground mt-1 block">
                            {{ $article->published_at ? $article->published_at->format('M d, Y') : $article->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Categories --}}
    @if($categories->isNotEmpty())
    <div class="bg-card border border-border overflow-hidden">
        <div class="px-5 pt-5 pb-0">
            <h3 class="text-base font-bold text-foreground inline-block relative pb-3">
                {{ __('messages.categories') }}
                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#8B0000]"></span>
            </h3>
        </div>
        <div class="p-5 pt-3">
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $cat)
                <a href="{{ route('articles.index', ['category' => $cat->slug]) }}"
                   class="inline-block bg-[#8B0000] hover:bg-[#8B0000]/80 text-white text-xs font-medium px-3 py-1.5 transition-colors">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- About Card --}}
    <div class="bg-[#8B0000] p-5 text-white @if(app()->getLocale() === 'ckb') border-r-4 @else border-l-4 @endif border-black">
        <h3 class="text-base font-bold mb-2 text-white">{{ __('messages.site_name') }}</h3>
        <p class="text-sm text-red-100 leading-relaxed mb-4">
            {{ __('messages.site_description') }}
        </p>
        <a href="{{ route('about') }}"
           class="inline-block bg-white text-[#8B0000] text-xs font-bold px-4 py-2 transition-colors hover:bg-gray-100">
            {{ __('messages.about_us') }}
        </a>
    </div>
</div>
