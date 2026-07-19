@extends('layouts.app')

@section('content')
<div class="bg-background overflow-hidden">
    {{-- Hero Section (matching reference with entrance animations) --}}
    <section class="relative min-h-[80vh] flex flex-col items-center justify-center overflow-hidden bg-[#0A0A0A] dark-hero">
        {{-- Radial background glow -- crimson bloom at center --}}
        <div class="absolute inset-0 pointer-events-none animate-fade-in"
             style="background: radial-gradient(ellipse 70% 60% at 50% 45%, rgba(100,0,0,0.28) 0%, rgba(10,10,10,0) 75%);">
        </div>

        {{-- Subtle grid texture --}}
        <div class="absolute inset-0 opacity-[0.07] pointer-events-none animate-fade-in"
             style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 48px 48px; mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 30%, transparent 100%);">
        </div>

        {{-- Horizontal divider lines --}}
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#8B0000]/40 to-transparent animate-fade-in delay-500"></div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#8B0000]/40 to-transparent animate-fade-in delay-500"></div>

        <div class="relative z-10 flex flex-col items-center text-center px-4">

            {{-- Logo -- animated fade-in-scale matching reference framer-motion --}}
            <div class="relative mb-0 animate-fade-in-scale"
                 style="width: min(420px, 80vw); height: min(260px, 48vw);">
                {{-- Ambient red glow behind logo --}}
                <div class="absolute pointer-events-none"
                     style="inset: -30%; background: radial-gradient(circle, rgba(139,0,0,0.28) 0%, transparent 60%); filter: blur(32px);">
                </div>

                {{-- The logo image --}}
                @if(app()->getLocale() === 'ckb')
                    <img src="{{ asset('images/logo-ckb.png') }}" alt="{{ __('messages.site_name') }}"
                         class="relative w-full h-full object-contain"
                         style="mask-image: radial-gradient(ellipse 72% 68% at 50% 48%, black 0%, black 38%, rgba(0,0,0,0.7) 55%, transparent 72%); -webkit-mask-image: radial-gradient(ellipse 72% 68% at 50% 48%, black 0%, black 38%, rgba(0,0,0,0.7) 55%, transparent 72%); filter: drop-shadow(0 0 28px rgba(139,0,0,0.55)) drop-shadow(0 0 6px rgba(139,0,0,0.25));">
                @else
                    <img src="{{ asset('images/logo-en.png') }}" alt="{{ setting('site_name_en', 'Khandantelegraph') }}"
                         class="relative w-full h-full object-contain"
                         style="mask-image: radial-gradient(ellipse 72% 68% at 50% 48%, black 0%, black 38%, rgba(0,0,0,0.7) 55%, transparent 72%); -webkit-mask-image: radial-gradient(ellipse 72% 68% at 50% 48%, black 0%, black 38%, rgba(0,0,0,0.7) 55%, transparent 72%); filter: drop-shadow(0 0 28px rgba(139,0,0,0.55)) drop-shadow(0 0 6px rgba(139,0,0,0.25));">
                @endif
            </div>

            {{-- Site Name Title -- animate fade-in-up with 0.3s delay --}}
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif font-bold text-white tracking-tighter mb-3 animate-fade-in-up delay-300">
                @if(app()->getLocale() === 'ckb')
                    {{ __('messages.site_name') }}
                @else
                    {{ setting('site_name_en', 'Khandantelegraph') }}
                @endif
            </h1>

            {{-- Tagline -- animate fade-in-up with 0.5s delay --}}
            <p class="text-sm md:text-base font-bold tracking-[0.25em] uppercase animate-fade-in-up delay-500"
               style="color: #8B0000; letter-spacing: 0.22em;">
                {{ __('messages.nav_tagline') }}
            </p>

            {{-- Thin crimson rule below tagline -- animate scale-x-in with 0.7s delay --}}
            <div class="mt-5 h-px w-48 mx-auto animate-scale-x-in delay-700"
                 style="background: linear-gradient(to right, transparent, #8B0000, transparent);">
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        {{-- Featured / Main Reports Section (scroll reveal) --}}
        <section class="mb-24 reveal" x-intersect="$el.classList.add('revealed')">
            <div class="flex items-center gap-4 mb-12 @if(app()->getLocale() === 'en') flex-row-reverse @endif">
                <h2 class="text-3xl font-serif font-bold text-foreground">{{ __('messages.home_featured') }}</h2>
                <div class="h-px bg-border flex-1"></div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                @if(isset($featuredArticles) && $featuredArticles->isNotEmpty())
                    @foreach($featuredArticles as $index => $article)
                        <x-article-card :article="$article" :featured="$index === 0" :index="$index" />
                    @endforeach
                @else
                    {{-- Placeholder featured --}}
                    @for($i = 1; $i <= 3; $i++)
                        <x-article-card :article="null" :featured="$i === 1" :index="$i" />
                    @endfor
                @endif
            </div>
        </section>

        {{-- Latest News Grid (scroll reveal stagger) --}}
        <section class="reveal reveal-delay-2" x-intersect="$el.classList.add('revealed')">
            <div class="flex items-center justify-between mb-12 @if(app()->getLocale() === 'en') flex-row-reverse @endif">
                <div class="flex items-center gap-4 flex-1 @if(app()->getLocale() === 'en') flex-row-reverse @endif">
                    <h2 class="text-3xl font-serif font-bold text-foreground">{{ __('messages.home_latest') }}</h2>
                    <div class="h-px bg-border flex-1 mr-8"></div>
                </div>
                <a href="{{ route('articles.index') }}"
                   class="text-sm font-bold text-[#8B0000] hover:text-[#8B0000]/80 transition-colors cursor-pointer hidden md:block whitespace-nowrap @if(app()->getLocale() === 'ckb') mr-8 @else ml-8 @endif">
                    {{ __('messages.home_view_all') }} @if(app()->getLocale() === 'ckb') &larr; @else &rarr; @endif
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @if(isset($latestArticles) && $latestArticles->isNotEmpty())
                    @foreach($latestArticles->take(6) as $index => $article)
                        <x-article-card :article="$article" :index="$index + 3" />
                    @endforeach
                @else
                    @for($i = 1; $i <= 6; $i++)
                        <x-article-card :article="null" :index="$i + 3" />
                    @endfor
                @endif
            </div>

            <div class="mt-12 text-center md:hidden">
                <a href="{{ route('articles.index') }}">
                    <button class="bg-transparent border-2 border-[#8B0000] text-[#8B0000] px-8 py-3 font-bold uppercase tracking-wider hover:bg-[#8B0000] hover:text-white transition-colors">
                        {{ __('messages.home_view_all') }}
                    </button>
                </a>
            </div>
        </section>
    </div>
</div>
@endsection
