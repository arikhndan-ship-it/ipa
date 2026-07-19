<header class="sticky top-0 z-50 w-full animate-slide-down" x-data="{ mobileMenuOpen: false }">
    {{-- Breaking News Ticker --}}
    <div class="bg-[#8B0000] text-white overflow-hidden flex items-center border-b-4 border-black ticker-bar" style="height:2rem;" dir="ltr">
        <div class="bg-black text-white px-4 font-bold text-sm z-10 relative whitespace-nowrap flex items-center gap-2 shrink-0 h-full ticker-label">
            <span class="w-2 h-2 rounded-full bg-white/80 animate-pulse inline-block"></span>
            <span>{{ __('messages.ticker_label') }}</span>
        </div>
        <div class="relative flex-1 overflow-hidden h-full">
            @if(isset($breakingNews) && $breakingNews->isNotEmpty())
            <div class="animate-ticker items-center h-full gap-0">
                @foreach($breakingNews as $news)
                    <a href="{{ route('articles.show', $news) }}" class="ticker-link hover:underline mx-6">
                        {{ $news->title }}
                    </a>
                    <span class="ticker-sep mx-6">///</span>
                @endforeach
                @foreach($breakingNews as $news)
                    <a href="{{ route('articles.show', $news) }}" class="ticker-link hover:underline mx-6">
                        {{ $news->title }}
                    </a>
                    <span class="ticker-sep mx-6">///</span>
                @endforeach
            </div>
            @else
            <div class="flex items-center h-full px-4 text-xs text-white/70">
                {{ __('messages.breaking_news') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Navbar (matching reference) --}}
    <nav class="bg-black text-white border-b-4 border-[#8B0000]">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 h-16 sm:h-20 flex items-center justify-between">

            {{-- Mobile actions row: hamburger + notification bell --}}
            <div class="lg:hidden flex items-center gap-1">
                {{-- Notification Bell on mobile (left of hamburger) --}}
                <div class="relative" x-data="{ count: 0 }"
                     x-init="fetch('{{ url('api/v1/notifications/count') }}').then(r=>r.json()).then(d => { count = d.unread_count; }).catch(() => {})">
                    <a href="{{ route('notifications') }}" class="relative p-2 text-gray-400 hover:text-white transition-colors block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span x-show="count > 0" x-text="count"
                              class="absolute -top-1 -right-1 bg-[#CC0000] text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1"
                              style="display: none;"></span>
                    </a>
                </div>
                {{-- Mobile menu toggle --}}
                <button class="p-2 text-white hover:text-[#8B0000] transition-colors"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Desktop Nav + Language Switcher --}}
            <div class="hidden lg:flex items-center gap-8">
                <nav class="flex items-center gap-8 text-sm font-bold tracking-wider">
                    <a href="{{ route('home') }}"
                       class="relative px-2 py-1 cursor-pointer transition-colors {{ request()->routeIs('home') ? 'text-[#8B0000]' : 'text-white hover:text-gray-300' }}">
                        {{ __('messages.nav_home') }}
                        @if(request()->routeIs('home'))
                            <span class="nav-indicator"></span>
                        @endif
                    </a>
                    <a href="{{ route('about') }}"
                       class="relative px-2 py-1 cursor-pointer transition-colors {{ request()->routeIs('about') ? 'text-[#8B0000]' : 'text-white hover:text-gray-300' }}">
                        {{ __('messages.nav_about') }}
                        @if(request()->routeIs('about'))
                            <span class="nav-indicator"></span>
                        @endif
                    </a>
                    <a href="{{ route('articles.index') }}"
                       class="relative px-2 py-1 cursor-pointer transition-colors {{ request()->routeIs('articles.*') ? 'text-[#8B0000]' : 'text-white hover:text-gray-300' }}">
                        {{ __('messages.nav_reports') }}
                        @if(request()->routeIs('articles.*'))
                            <span class="nav-indicator"></span>
                        @endif
                    </a>
                    <a href="{{ route('contact') }}"
                       class="relative px-2 py-1 cursor-pointer transition-colors {{ request()->routeIs('contact') ? 'text-[#8B0000]' : 'text-white hover:text-gray-300' }}">
                        {{ __('messages.nav_contact') }}
                        @if(request()->routeIs('contact'))
                            <span class="nav-indicator"></span>
                        @endif
                    </a>
                    <a href="{{ route('settings') }}"
                       class="relative px-2 py-1 cursor-pointer transition-colors {{ request()->routeIs('settings') ? 'text-[#8B0000]' : 'text-white hover:text-gray-300' }}">
                        <svg class="w-4 h-4 inline-block -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ __('messages.settings') }}
                        @if(request()->routeIs('settings'))
                            <span class="nav-indicator"></span>
                        @endif
                    </a>
                </nav>

                {{-- Notification Bell --}}
                <div class="relative" x-data="{ open: false, count: 0, notifications: [] }"
                     x-init="fetch('{{ url('api/v1/notifications/count') }}')
                        .then(r => r.json())
                        .then(d => { count = d.unread_count; })
                        .catch(() => {});
                      setInterval(() => {
                        fetch('{{ url('api/v1/notifications/count') }}')
                          .then(r => r.json())
                          .then(d => { count = d.unread_count; })
                          .catch(() => {});
                      }, 30000);">
                    <button @click="open = !open; if(open) fetch('{{ url('api/v1/notifications') }}').then(r=>r.json()).then(d => { notifications = d.data; count = d.unread_count; })" class="relative p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span x-show="count > 0" x-text="count"
                              class="absolute -top-1 -right-1 bg-[#CC0000] text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1"
                              style="display: none;"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" @click.outside="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-black border border-gray-800 rounded-sm shadow-xl z-50 max-h-96 overflow-y-auto">
                        <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
                            <span class="text-white text-xs font-bold uppercase tracking-wider">Notifications</span>
                            <a href="{{ route('notifications') }}" class="text-[#8B0000] text-xs hover:underline" @click="open = false">View All</a>
                        </div>
                        <template x-if="notifications.length === 0">
                            <div class="px-4 py-8 text-center text-gray-500 text-xs">No notifications yet</div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <a :href="notif.url || '{{ route('notifications') }}'" class="block px-4 py-3 hover:bg-gray-900 transition-colors border-b border-gray-800 last:border-b-0"
                               :class="{ 'bg-gray-900/50': !notif.is_read }"
                               @click="open = false">
                                <div class="flex items-start gap-3">
                                    <span class="shrink-0 mt-0.5 w-2 h-2 rounded-full"
                                          :class="notif.is_read ? 'bg-gray-600' : 'bg-[#CC0000]'"></span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-white text-xs font-semibold leading-snug" x-text="notif.title"></p>
                                        <p x-show="notif.body" class="text-gray-400 text-[10px] mt-1 leading-tight" x-text="notif.body"></p>
                                        <p class="text-gray-600 text-[9px] mt-1" x-text="new Date(notif.created_at).toLocaleDateString()"></p>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                {{-- Language Switcher --}}
                <div class="flex items-center gap-1 border border-gray-700 rounded-sm overflow-hidden">
                    <a href="{{ route('language.switch', 'ckb') }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold tracking-wider transition-colors {{ app()->getLocale() === 'ckb' ? 'bg-[#8B0000] text-white' : 'text-gray-400 hover:text-white' }}">
                        <img src="{{ asset('images/logo-ckb.png') }}" alt="Khandan" class="w-3.5 h-3.5 rounded-sm object-cover">
                        {{ __('messages.nav_language_ku') }}
                    </a>
                    <div class="w-px h-5 bg-gray-700"></div>
                    <a href="{{ route('language.switch', 'en') }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold tracking-wider transition-colors {{ app()->getLocale() === 'en' ? 'bg-[#8B0000] text-white' : 'text-gray-400 hover:text-white' }}">
                        EN
                    </a>
                </div>
            </div>

            {{-- Logo area --}}
            <a href="{{ route('home') }}" class="flex items-center gap-4 cursor-pointer">
                <div class="text-right hidden sm:block">
                    <h1 class="text-2xl font-serif font-bold text-white tracking-tighter">
                        @if(app()->getLocale() === 'ckb')
                            {{ __('messages.site_name') }}
                        @else
                            {{ setting('site_name_en', 'Khandantelegraph') }}
                        @endif
                    </h1>
                    <p class="text-[10px] text-gray-400 font-sans uppercase tracking-widest mt-1">{{ __('messages.nav_tagline') }}</p>
                </div>
                @if(app()->getLocale() === 'ckb')
                    <img src="{{ asset('images/logo-ckb.png') }}" alt="{{ __('messages.site_name') }}"
                         class="h-12 w-12 rounded-sm border border-gray-800 object-cover">
                @else
                    <img src="{{ asset('images/logo-en.png') }}" alt="{{ setting('site_name_en', 'Khandantelegraph') }}"
                         class="h-12 w-12 rounded-sm border border-gray-800 object-cover">
                @endif
            </a>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="lg:hidden bg-[#0A0A0A] border-t border-gray-900 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col gap-2">
                <a href="{{ route('home') }}"
                   class="py-3 px-4 rounded-sm font-bold cursor-pointer {{ request()->routeIs('home') ? 'bg-[#8B0000] text-white' : 'text-gray-300 hover:bg-gray-900 hover:text-white' }}"
                   @click="mobileMenuOpen = false">
                    {{ __('messages.nav_home') }}
                </a>
                <a href="{{ route('about') }}"
                   class="py-3 px-4 rounded-sm font-bold cursor-pointer {{ request()->routeIs('about') ? 'bg-[#8B0000] text-white' : 'text-gray-300 hover:bg-gray-900 hover:text-white' }}"
                   @click="mobileMenuOpen = false">
                    {{ __('messages.nav_about') }}
                </a>
                <a href="{{ route('articles.index') }}"
                   class="py-3 px-4 rounded-sm font-bold cursor-pointer {{ request()->routeIs('articles.*') ? 'bg-[#8B0000] text-white' : 'text-gray-300 hover:bg-gray-900 hover:text-white' }}"
                   @click="mobileMenuOpen = false">
                    {{ __('messages.nav_reports') }}
                </a>
                <a href="{{ route('contact') }}"
                   class="py-3 px-4 rounded-sm font-bold cursor-pointer {{ request()->routeIs('contact') ? 'bg-[#8B0000] text-white' : 'text-gray-300 hover:bg-gray-900 hover:text-white' }}"
                   @click="mobileMenuOpen = false">
                    {{ __('messages.nav_contact') }}
                </a>
                <a href="{{ route('settings') }}"
                   class="py-3 px-4 rounded-sm font-bold cursor-pointer {{ request()->routeIs('settings') ? 'bg-[#8B0000] text-white' : 'text-gray-300 hover:bg-gray-900 hover:text-white' }}"
                   @click="mobileMenuOpen = false">
                    {{ __('messages.settings') }}
                </a>
                {{-- Mobile Language Switcher --}}
                    <div class="flex items-center gap-2 px-4 pt-4 pb-2 border-t border-gray-800 mt-2">
                        <img src="{{ asset('images/logo-ckb.png') }}" alt="Khandan" class="w-4 h-4 rounded-sm object-cover">
                        <a href="{{ route('language.switch', 'ckb') }}"
                           class="px-3 py-1 text-xs font-bold rounded-sm transition-colors {{ app()->getLocale() === 'ckb' ? 'bg-[#8B0000] text-white' : 'text-gray-400 border border-gray-700' }}">
                            کوردی
                        </a>
                        <a href="{{ route('language.switch', 'en') }}"
                           class="px-3 py-1 text-xs font-bold rounded-sm transition-colors {{ app()->getLocale() === 'en' ? 'bg-[#8B0000] text-white' : 'text-gray-400 border border-gray-700' }}">
                            English
                        </a>
                    </div>
            </div>
        </div>
    </nav>
</header>
