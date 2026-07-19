@props([
    'article' => null,
    'featured' => false,
    'index' => 0,
    'image' => null,
    'category' => null,
    'categoryEn' => null,
    'title' => null,
    'excerpt' => null,
    'date' => null,
    'dateEn' => null,
    'slug' => '#',
    'showImage' => true,
    'showExcerpt' => true,
])

@php
    $articleImage = $image ?? ($article->featured_image ?? ($article->image ?? null));
    $articleCategory = $category ?? ($article->categories->first()->name ?? __('messages.cat_human_rights'));
    $articleTitle = $title ?? ($article->title ?? '');
    $articleExcerpt = $excerpt ?? ($article->excerpt ?? Str::limit(strip_tags($article->body ?? ''), 200));
    $articleExcerpt = strip_tags($articleExcerpt);
    $articleDate = $date ?? ($article->published_at ?? $article->created_at ?? now());
    $articleRoute = $article ? route('articles.show', $article) : $slug;
    $formattedDate = $articleDate instanceof \Carbon\Carbon
        ? $articleDate->format('M d, Y')
        : ($articleDate ?: now()->format('M d, Y'));
    $direction = app()->getLocale() === 'ckb' ? 'rtl' : 'ltr';
    $revealDelay = min($index, 5);
@endphp

<a href="{{ $articleRoute }}" class="block px-3 py-1.5 sm:px-0 sm:py-3">
    <article class="group cursor-pointer bg-card border border-border overflow-hidden flex flex-col {{ $featured ? 'md:flex-row col-span-full' : '' }} hover:-translate-y-0.5 transition-all duration-300 reveal reveal-delay-{{ $revealDelay }}" x-intersect="$el.classList.add('revealed')">
        @if($showImage)
        {{-- Image section (always shown, like app) --}}
        <div class="relative w-full overflow-hidden {{ $featured ? 'md:w-1/2 lg:w-3/5' : '' }}" style="min-height: 180px;">
            {{-- Image or placeholder --}}
            @if($articleImage)
                <img src="{{ $articleImage }}" alt="{{ $articleTitle }}"
                     class="w-full h-[180px] object-cover">
            @else
                <div class="w-full h-[180px] bg-muted flex items-center justify-center">
                    <svg class="w-12 h-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif

            {{-- Dark overlay (matching app gradient) --}}
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/20 pointer-events-none"></div>

            {{-- Category badge (top-left, matching app) --}}
            <div class="absolute top-3 @if($direction === 'rtl') right-3 @else left-3 @endif z-10">
                <span class="bg-[#8B0000] text-white text-[10px] font-bold px-2 py-0.5 uppercase tracking-wider">
                    {{ $articleCategory }}
                </span>
            </div>
        </div>
        @endif

        {{-- Content section (matching app: padding 16px) --}}
        <div class="p-4 flex flex-col justify-start {{ $featured ? 'md:w-1/2 lg:w-2/5 md:p-6' : '' }}">
            {{-- Date row: // + date (matching app) --}}
            <div class="flex items-center gap-2 mb-2.5">
                <span class="font-bold text-[#8B0000] text-xs font-mono">//</span>
                <span class="text-[11px] text-muted-foreground font-mono">{{ $formattedDate }}</span>
            </div>

            {{-- Title: 18px bold serif, max 2 lines --}}
            <h3 class="font-serif font-bold leading-snug group-hover:text-[#8B0000] transition-colors {{ $featured ? 'text-2xl md:text-3xl lg:text-4xl' : 'text-lg' }} line-clamp-2 article-headline-clamp {{ $featured ? 'text-white' : 'text-card-foreground' }}">
                {{ $articleTitle }}
            </h3>

            {{-- Excerpt: 13px muted, max 2 lines, spacing 8px top (matching app) --}}
            @if($showExcerpt && $articleExcerpt)
            <p class="text-muted-foreground text-xs leading-relaxed line-clamp-2 mt-2">
                {{ $articleExcerpt }}
            </p>
            @endif

            {{-- Read More: 11px bold, with arrow (matching app) --}}
            <div class="mt-3 flex items-center gap-1.5">
                @if($direction === 'rtl')
                    <span class="text-[11px] font-bold text-[#8B0000] uppercase tracking-wider">{{ __('messages.article_read_more') }}</span>
                    <span class="text-[#8B0000] text-xs group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                @else
                    <span class="text-[#8B0000] text-xs group-hover:translate-x-1 transition-transform duration-200">&rarr;</span>
                    <span class="text-[11px] font-bold text-[#8B0000] uppercase tracking-wider">{{ __('messages.article_read_more') }}</span>
                @endif
            </div>
        </div>
    </article>
</a>
