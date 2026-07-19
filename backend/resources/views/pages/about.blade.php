@extends('layouts.app')

@section('content')
    <div class="bg-background pb-20 overflow-hidden">
        {{-- Header (matching reference dark header) --}}
    <div class="bg-[#0A0A0A] text-white py-20 border-b border-border relative overflow-hidden dark-hero">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none mix-blend-overlay"
             style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
        </div>
        {{-- Subtle radial glow --}}
        <div class="absolute inset-0 pointer-events-none"
             style="background: radial-gradient(ellipse 60% 55% at 50% 30%, rgba(139,0,0,0.18) 0%, transparent 70%);">
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center flex flex-col items-center">
                    {{-- Profile photo --}}
                    <div class="relative mb-7 animate-fade-in-scale">
                        {{-- Glow halo --}}
                        <div class="absolute -inset-3 rounded-full bg-[#8B0000]/25 blur-xl pointer-events-none"></div>
                        {{-- Photo ring with actual image --}}
                        <div class="relative w-44 h-44 rounded-full border-4 border-[#8B0000] overflow-hidden shadow-2xl shadow-[#8B0000]/40">
                            <img src="{{ asset('images/ari_author.jpg') }}"
                                 alt="{{ __('messages.about_founder_name') }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.parentElement.innerHTML='<svg class=\'w-20 h-20 text-gray-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'></path></svg>'">
                        </div>
                    {{-- Corner accent brackets --}}
                    <div class="absolute -top-3 -left-3 w-5 h-5 border-t-2 border-l-2 border-[#8B0000]"></div>
                    <div class="absolute -top-3 -right-3 w-5 h-5 border-t-2 border-r-2 border-[#8B0000]"></div>
                    <div class="absolute -bottom-3 -left-3 w-5 h-5 border-b-2 border-l-2 border-[#8B0000]"></div>
                    <div class="absolute -bottom-3 -right-3 w-5 h-5 border-b-2 border-r-2 border-[#8B0000]"></div>
                </div>

                {{-- Role label (fade-in-up, delay 200) --}}
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#8B0000] mb-3 animate-fade-in-up delay-200">
                    {{ __('messages.about_founder_role') }}
                </p>

                {{-- Founder name (fade-in-up, delay 300) --}}
                <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-5 animate-fade-in-up delay-300">
                    {{ __('messages.about_founder_name') }}
                </h1>

                {{-- Thin crimson rule (scale-x-in, delay 500) --}}
                <div class="mb-8 h-px w-28 bg-gradient-to-r from-transparent via-[#8B0000] to-transparent animate-scale-x-in delay-500"></div>

                {{-- About title & mission (fade-in-up, delay 600) --}}
                <h2 class="text-lg md:text-xl font-serif font-semibold text-gray-400 mb-4 animate-fade-in-up delay-600">
                    {{ __('messages.about_title') }}
                </h2>
                <p class="text-sm md:text-base text-gray-400 leading-relaxed max-w-2xl animate-fade-in-up delay-700">
                    {{ __('messages.about_subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-20">
        {{-- Founder Bio + Quote (scroll-reveal) --}}
        <div class="max-w-3xl mx-auto bg-card border border-border overflow-hidden reveal mb-32" x-intersect="$el.classList.add('revealed')">
            {{-- Top crimson accent --}}
            <div class="h-1 w-full bg-[#8B0000]"></div>

            <div class="p-10 lg:p-14 @if(app()->getLocale() === 'en') text-left @else text-right @endif">
                <div class="space-y-5 text-muted-foreground leading-relaxed mb-10">
                    <p class="text-base">{{ __('messages.about_founder_p1') }}</p>
                    <p class="text-base">{{ __('messages.about_founder_p2') }}</p>
                    <p class="text-base">{{ __('messages.about_founder_p3') }}</p>
                </div>

                {{-- Pull Quote --}}
                <blockquote class="relative @if(app()->getLocale() === 'ckb') border-r-4 pr-6 @else border-l-4 pl-6 @endif border-[#8B0000] bg-muted/30 py-4">
                    <svg class="w-5 h-5 text-[#8B0000] mb-2 @if(app()->getLocale() === 'en') @else transform scale-x-[-1] @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 15.75l7.5-7.5 7.5 7.5"></path>
                    </svg>
                    <p class="text-foreground font-bold text-base leading-relaxed italic">
                        {{ __('messages.about_founder_quote') }}
                    </p>
                </blockquote>
            </div>
        </div>

        {{-- The Three Pillars (scroll-reveal) --}}
        <div class="mb-32 reveal" x-intersect="$el.classList.add('revealed')">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-serif font-bold text-foreground inline-block relative">
                    {{ __('messages.about_pillars') }}
                    <span class="absolute -bottom-4 left-1/4 right-1/4 h-1 bg-[#8B0000]"></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-6xl mx-auto">
                {{-- Pillar 1: Exposing Crimes --}}
                <div class="bg-card border border-border p-8 text-center group hover:border-[#8B0000] transition-colors reveal reveal-delay-1" x-intersect="$el.classList.add('revealed')">
                    <div class="w-16 h-16 bg-muted text-foreground flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold font-serif mb-4 text-[#8B0000]">{{ __('messages.about_pillar_1_title') }}</h3>
                    <p class="text-muted-foreground leading-relaxed">{{ __('messages.about_pillar_1_text') }}</p>
                </div>

                {{-- Pillar 2: Against Censorship --}}
                <div class="bg-card border border-border p-8 text-center group hover:border-[#8B0000] transition-colors reveal reveal-delay-2" x-intersect="$el.classList.add('revealed')">
                    <div class="w-16 h-16 bg-muted text-foreground flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold font-serif mb-4 text-[#8B0000]">{{ __('messages.about_pillar_2_title') }}</h3>
                    <p class="text-muted-foreground leading-relaxed">{{ __('messages.about_pillar_2_text') }}</p>
                </div>

                {{-- Pillar 3: Voice of Kurdistan --}}
                <div class="bg-card border border-border p-8 text-center group hover:border-[#8B0000] transition-colors reveal reveal-delay-3" x-intersect="$el.classList.add('revealed')">
                    <div class="w-16 h-16 bg-muted text-foreground flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold font-serif mb-4 text-[#8B0000]">{{ __('messages.about_pillar_3_title') }}</h3>
                    <p class="text-muted-foreground leading-relaxed">{{ __('messages.about_pillar_3_text') }}</p>
                </div>
            </div>
        </div>

        {{-- Social Media Links --}}
        <div class="mb-32 reveal" x-intersect="$el.classList.add('revealed')">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-serif font-bold text-foreground inline-block relative">
                    {{ __('messages.follow_us') }}
                    <span class="absolute -bottom-4 left-1/4 right-1/4 h-1 bg-[#8B0000]"></span>
                </h2>
            </div>
            <div class="flex justify-center gap-8">
                {{-- Telegram --}}
                <a href="{{ setting('telegram_url', 'https://t.me/khandantelegraph') }}" target="_blank" rel="noopener noreferrer"
                   class="w-16 h-16 rounded-full bg-[#0088cc] flex items-center justify-center
                          hover:scale-110 hover:shadow-lg hover:shadow-[#0088cc]/40
                          transition-all duration-300 group">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                    </svg>
                </a>

                {{-- Facebook --}}
                <a href="{{ setting('facebook_url', 'https://www.facebook.com/share/194x5ECuH1/') }}" target="_blank" rel="noopener noreferrer"
                   class="w-16 h-16 rounded-full bg-[#1877F2] flex items-center justify-center
                          hover:scale-110 hover:shadow-lg hover:shadow-[#1877F2]/40
                          transition-all duration-300 group">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Our Journalists --}}
        <div class="mb-32 reveal" x-intersect="$el.classList.add('revealed')">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-serif font-bold text-foreground inline-block relative">
                    {{ __('messages.about_journalists') }}
                    <span class="absolute -bottom-4 left-1/4 right-1/4 h-1 bg-[#8B0000]"></span>
                </h2>
            </div>

            @if(isset($journalists) && $journalists->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach($journalists as $index => $journalist)
                        @php
                            $authorRoute = $journalist->user ? route('author.show', $journalist->user) : null;
                        @endphp
                        <div class="bg-card border border-border p-8 text-center group hover:border-[#8B0000] transition-colors reveal {{ 'reveal-delay-' . (($index % 5) + 1) }}" x-intersect="$el.classList.add('revealed')"
                             @if($authorRoute) onclick="window.location='{{ $authorRoute }}'" style="cursor:pointer;" @endif>
                            {{-- Photo --}}
                            <div class="w-24 h-24 mx-auto mb-6 rounded-full overflow-hidden border-2 border-[#8B0000] bg-gray-100">
                                @if($journalist->image)
                                    <img src="{{ asset('storage/' . $journalist->image) }}" alt="{{ $journalist->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            {{-- Name --}}
                            <h3 class="text-xl font-bold font-serif mb-3 text-foreground group-hover:text-[#8B0000] transition-colors">{{ $journalist->name }}</h3>
                            {{-- Bio --}}
                            @if($journalist->bio)
                                <p class="text-muted-foreground text-sm leading-relaxed">{!! $journalist->bio !!}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted-foreground">{{ __('messages.about_no_journalists') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
