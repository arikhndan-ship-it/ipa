@extends('layouts.app')

@section('content')
<div class="bg-background pb-24 overflow-hidden">
    {{-- Header --}}
    <div class="bg-[#0A0A0A] text-white py-16 border-b-4 border-[#8B0000] relative overflow-hidden dark-hero">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none mix-blend-overlay animate-fade-in"
             style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
        </div>
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <h1 class="text-4xl md:text-5xl font-serif font-bold mb-6 animate-fade-in-up">{{ __('messages.settings') }}</h1>
            <p class="text-gray-400 max-w-2xl text-lg animate-fade-in-up delay-200">{{ __('messages.settings_subtitle') }}</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 mt-12 space-y-8">

        {{-- Language Section --}}
        <div class="bg-card border border-border p-8 reveal" x-intersect="$el.classList.add('revealed')">
            <div class="flex items-center gap-3 mb-6">
                <img src="{{ asset('images/logo-ckb.png') }}" alt="Khandan" class="h-8 w-8 rounded-sm object-cover">
                <h2 class="text-xl font-serif font-bold text-foreground">{{ __('messages.language') }}</h2>
            </div>
            <div class="space-y-3">
                <a href="{{ route('language.switch', 'en') }}"
                   class="flex items-center justify-between p-4 border transition-colors cursor-pointer {{ app()->getLocale() === 'en' ? 'border-[#8B0000] bg-[#8B0000]/10' : 'border-border hover:border-gray-400' }}">
                    <div>
                        <p class="font-bold text-foreground">English</p>
                        <p class="text-sm text-muted-foreground">{{ __('messages.english') }}</p>
                    </div>
                    @if(app()->getLocale() === 'en')
                        <svg class="w-6 h-6 text-[#8B0000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </a>
                <a href="{{ route('language.switch', 'ckb') }}"
                   class="flex items-center justify-between p-4 border transition-colors cursor-pointer {{ app()->getLocale() === 'ckb' ? 'border-[#8B0000] bg-[#8B0000]/10' : 'border-border hover:border-gray-400' }}">
                    <div>
                        <p class="font-bold text-foreground">کوردی</p>
                        <p class="text-sm text-muted-foreground">{{ __('messages.kurdish') }}</p>
                    </div>
                    @if(app()->getLocale() === 'ckb')
                        <svg class="w-6 h-6 text-[#8B0000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </a>
            </div>
        </div>

        {{-- About & App Info --}}
        <div class="bg-card border border-border reveal reveal-delay-1" x-intersect="$el.classList.add('revealed')">
            <div class="flex items-center p-6">
                <svg class="w-5 h-5 text-[#8B0000] mr-4 rtl:ml-4 rtl:mr-0 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-bold text-foreground">{{ __('messages.about_us') }}</p>
                    <p class="text-sm text-muted-foreground">{{ __('messages.version') }}: 1.0.0</p>
                </div>
            </div>
        </div>

        {{-- Secure Contact Section --}}
        <div class="bg-card border border-border p-8 reveal reveal-delay-2" x-intersect="$el.classList.add('revealed')">
            <div class="flex items-center gap-3 mb-6">
                <svg class="w-5 h-5 text-[#8B0000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <h2 class="text-xl font-serif font-bold text-foreground">{{ __('messages.contact_secure_title') }}</h2>
            </div>

            <div class="bg-[#0A0A0A] border border-[#8B0000]/30 p-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-[#8B0000] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <div>
                    <p class="font-bold text-white text-sm">{{ __('messages.contact_secure_title') }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ __('messages.contact_secure_text') }}</p>
                </div>
            </div>

            <ul class="space-y-3">
                <li class="flex items-center gap-3 p-3 bg-muted border border-border">
                    <svg class="w-5 h-5 text-[#8B0000] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-xs text-muted-foreground">{{ __('messages.contact_email_label') }}</p>
                        <p class="font-bold text-foreground">{{ setting('contact_email', __('messages.contact_email_value')) }}</p>
                    </div>
                </li>
                <li class="flex items-center gap-3 p-3 bg-muted border border-border">
                    <svg class="w-5 h-5 text-[#8B0000] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    <div>
                        <p class="text-xs text-muted-foreground">{{ __('messages.contact_telegram_label') }}</p>
                        <p class="font-bold text-foreground">{{ setting('telegram_username', __('messages.contact_telegram_value')) }}</p>
                    </div>
                </li>
            </ul>
        </div>

        {{-- Visit Website --}}
        <div class="bg-card border border-border reveal reveal-delay-3" x-intersect="$el.classList.add('revealed')">
            <a href="{{ setting('website_url', 'https://khandantelegraph.news') }}" target="_blank" class="flex items-center justify-between p-6 hover:bg-muted transition-colors">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-[#8B0000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-foreground">{{ __('messages.visit_website') }}</p>
                        <p class="text-sm text-muted-foreground">{{ setting('website_url', 'https://khandantelegraph.news') }}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="h-8"></div>
    </div>
</div>
@endsection
