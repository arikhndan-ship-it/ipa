<footer class="bg-[#0A0A0A] text-white border-t-4 border-[#8B0000] mt-20 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8 mb-12">

            {{-- Contact Column --}}
            <div class="md:col-span-4 flex flex-col gap-6">
                <h3 class="text-xl font-bold font-serif @if(app()->getLocale() === 'ckb') border-r-2 pr-4 @else border-l-2 pl-4 @endif border-[#8B0000] text-gray-300 uppercase tracking-widest text-sm">
                    {{ __('messages.footer_contact') }}
                </h3>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li>{{ __('messages.footer_email') }}: <span class="text-gray-300">{{ setting('contact_email', __('messages.footer_contact_email')) }}</span></li>
                    <li>{{ __('messages.footer_telegram') }}: <span class="text-gray-300">{{ setting('telegram_username', __('messages.footer_contact_telegram')) }}</span></li>
                    <li class="pt-4">
                        <a href="{{ route('contact') }}" class="text-[#8B0000] hover:text-white transition-colors cursor-pointer font-bold">
                            {{ __('messages.footer_send_tip') }} @if(app()->getLocale() === 'ckb') &larr; @else &rarr; @endif
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Quick Links Column --}}
            <div class="md:col-span-4 flex flex-col gap-6">
                <h3 class="text-xl font-bold font-serif @if(app()->getLocale() === 'ckb') border-r-2 pr-4 @else border-l-2 pl-4 @endif border-[#8B0000] text-gray-300 uppercase tracking-widest text-sm">
                    {{ __('messages.footer_links') }}
                </h3>
                <ul class="space-y-3 text-gray-400 font-bold text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-[#8B0000] transition-colors cursor-pointer">{{ __('messages.nav_home') }}</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-[#8B0000] transition-colors cursor-pointer">{{ __('messages.nav_about') }}</a></li>
                    <li><a href="{{ route('articles.index') }}" class="hover:text-[#8B0000] transition-colors cursor-pointer">{{ __('messages.nav_reports') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#8B0000] transition-colors cursor-pointer">{{ __('messages.nav_contact') }}</a></li>
                </ul>
            </div>

            {{-- Logo + Mission Column --}}
            <div class="md:col-span-4 flex flex-col gap-6 items-start md:items-end">
                <div class="flex items-center gap-4 justify-end w-full @if(app()->getLocale() === 'en') flex-row-reverse @endif">
                    <div class="@if(app()->getLocale() === 'en') text-left @else text-right @endif">
                        <h2 class="text-2xl font-serif font-bold text-white tracking-tighter">
                            @if(app()->getLocale() === 'ckb')
                                {{ __('messages.site_name') }}
                            @else
                                {{ setting('site_name_en', 'Khandantelegraph') }}
                            @endif
                        </h2>
                        <p class="text-[10px] text-gray-400 font-sans uppercase tracking-widest mt-1">{{ __('messages.nav_tagline') }}</p>
                    </div>
                    @if(app()->getLocale() === 'ckb')
                        <img src="{{ asset('images/logo-ckb.png') }}" alt="{{ __('messages.site_name') }}"
                             class="h-14 w-14 rounded-sm border border-gray-800 object-cover">
                    @else
                        <img src="{{ asset('images/logo-en.png') }}" alt="{{ setting('site_name_en', 'Khandantelegraph') }}"
                             class="h-14 w-14 rounded-sm border border-gray-800 object-cover">
                    @endif
                </div>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm mt-2 @if(app()->getLocale() === 'en') text-left @else text-right @endif">
                    {{ __('messages.footer_mission') }}
                </p>

                {{-- Social Media Links --}}
                <div class="flex items-center gap-4 mt-4 @if(app()->getLocale() === 'en') justify-start @else justify-end @endif w-full">
                    <a href="{{ setting('telegram_url', 'https://t.me/khandantelegraph') }}"
                       target="_blank" rel="noopener noreferrer"
                       class="text-gray-400 hover:text-[#CC0000] transition-colors duration-300"
                       aria-label="Telegram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </a>
                    <a href="{{ setting('facebook_url', 'https://www.facebook.com/share/194x5ECuH1/') }}"
                       target="_blank" rel="noopener noreferrer"
                       class="text-gray-400 hover:text-[#CC0000] transition-colors duration-300"
                       aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        {{-- Copyright Bar --}}
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500 font-mono">
            <p>{{ __('messages.footer_copyright') }}</p>
            <div class="flex gap-4">
                <a href="{{ route('terms') }}" class="hover:text-gray-300 cursor-pointer">{{ __('messages.footer_terms') }}</a>
                <a href="{{ route('privacy') }}" class="hover:text-gray-300 cursor-pointer">{{ __('messages.footer_privacy') }}</a>
            </div>
        </div>
    </div>
</footer>
