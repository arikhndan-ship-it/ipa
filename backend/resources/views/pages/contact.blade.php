@extends('layouts.app')

@section('content')
<div class="bg-background pb-12 overflow-hidden">
    {{-- Header --}}
    <div class="bg-[#0A0A0A] text-white py-12 border-b-4 border-[#8B0000] relative overflow-hidden dark-hero">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none mix-blend-overlay animate-fade-in"
             style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
        </div>
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <h1 class="text-3xl md:text-4xl font-serif font-bold mb-3 animate-fade-in-up">{{ __('messages.contact_title') }}</h1>
            <p class="text-gray-400 max-w-2xl text-base animate-fade-in-up delay-200">{{ __('messages.contact_subtitle') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-6xl mx-auto" dir="ltr">
            {{-- Form (always on LEFT side regardless of language) --}}
            <div class="lg:col-span-7 reveal" x-intersect="$el.classList.add('revealed')">
                <div class="bg-card border border-border p-6 md:p-8 relative">
                    <div class="absolute top-0 left-0 w-24 h-1 bg-[#8B0000]"></div>

                    <h2 class="text-xl font-serif font-bold mb-6">{{ __('messages.contact_form_title') }}</h2>

                    {{-- Success Message --}}
                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <svg class="w-4 h-4 ltr:mr-2 rtl:ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <svg class="w-4 h-4 ltr:mr-2 rtl:ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest block">
                                    {{ __('messages.contact_form_name') }}
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') }}"
                                       class="w-full bg-background border-2 border-border focus:border-[#8B0000] outline-none px-3 py-2.5 text-sm transition-colors text-foreground @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest block">
                                    {{ __('messages.contact_form_email') }}
                                </label>
                                <input type="text"
                                       name="email"
                                       id="email"
                                       value="{{ old('email') }}"
                                       required
                                       class="w-full bg-background border-2 border-border focus:border-[#8B0000] outline-none px-3 py-2.5 text-sm transition-colors text-foreground @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest block">
                                {{ __('messages.contact_form_subject') }}
                            </label>
                            <input type="text"
                                   name="subject"
                                   id="subject"
                                   value="{{ old('subject') }}"
                                   required
                                   class="w-full bg-background border-2 border-border focus:border-[#8B0000] outline-none px-3 py-2.5 text-sm transition-colors text-foreground @error('subject') border-red-500 @enderror">
                            @error('subject')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest block">
                                {{ __('messages.contact_form_message') }}
                            </label>
                            <textarea name="message"
                                      id="message"
                                      rows="4"
                                      maxlength="5000"
                                      required
                                      class="w-full bg-background border-2 border-border focus:border-[#8B0000] outline-none px-3 py-2.5 text-sm transition-colors text-foreground resize-none h-[100px] @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-[#8B0000] hover:bg-[#8B0000]/90 text-white font-bold py-3 flex items-center justify-center gap-2 transition-colors text-sm">
                            <span>{{ __('messages.contact_form_send') }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Secure Contact Info --}}
            <div class="lg:col-span-5 flex flex-col gap-6 reveal reveal-delay-2" x-intersect="$el.classList.add('revealed')">
                {{-- Identity Protection --}}
                <div class="bg-[#0A0A0A] text-white p-6 border-r-4 border-[#8B0000] dark-hero">
                    <svg class="text-[#8B0000] mb-3" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <h3 class="text-lg font-bold font-serif mb-2">{{ __('messages.contact_secure_title') }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ __('messages.contact_secure_text') }}</p>
                </div>

                {{-- Other Contact Methods --}}
                <div class="bg-card border border-border p-6">
                    <svg class="text-muted-foreground mb-3" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <h3 class="text-lg font-bold font-serif mb-4 text-foreground">{{ __('messages.contact_other_title') }}</h3>

                    <ul class="space-y-3">
                        <li class="flex flex-col gap-1">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest">{{ __('messages.contact_email_label') }}</span>
                            <span class="text-foreground font-mono font-bold tracking-wider text-sm">{{ setting('contact_email', __('messages.contact_email_value')) }}</span>
                        </li>
                        <li class="flex flex-col gap-1">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest">{{ __('messages.contact_telegram_label') }}</span>
                            <span class="text-foreground font-mono font-bold tracking-wider text-sm">{{ setting('telegram_username', __('messages.contact_telegram_value')) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
