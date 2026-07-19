<div class="p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $this->getGreeting() }}, {{ $this->getUserName() }}!
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ __('messages.welcome_subtitle') }}
            </p>
        </div>
        
        {{-- Mini stats row --}}
        <div class="flex items-center gap-4 text-sm">
            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $this->getStats()['publishedToday'] }} {{ __('messages.published_today') }}</span>
            </div>
            @if($this->getStats()['pendingComments'] > 0)
                <div class="flex items-center gap-2 px-3 py-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="text-amber-700 dark:text-amber-300 font-medium">{{ $this->getStats()['pendingComments'] }} {{ __('messages.pending_comments_count') }}</span>
                </div>
            @endif
            @if($this->getStats()['unreadContacts'] > 0)
                <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span class="text-blue-700 dark:text-blue-300 font-medium">{{ $this->getStats()['unreadContacts'] }} {{ __('messages.unread_tips') }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Quick Action Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('filament.admin.resources.articles.create') }}" 
           class="flex flex-col items-center gap-2 p-4 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl border border-red-200 dark:border-red-800/30 hover:shadow-md hover:scale-[1.02] transition-all duration-200 group">
            <div class="w-10 h-10 rounded-lg bg-red-500 dark:bg-red-600 flex items-center justify-center group-hover:bg-red-600 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <span class="text-xs font-bold text-red-700 dark:text-red-300 uppercase tracking-wider">{{ __('messages.write_article') }}</span>
        </a>

        @if($this->isAuthor())
            <a href="{{ route('filament.admin.resources.categories.index') }}"
               class="flex flex-col items-center gap-2 p-4 bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-xl border border-amber-200 dark:border-amber-800/30 hover:shadow-md hover:scale-[1.02] transition-all duration-200 group">
                <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center group-hover:bg-amber-600 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-amber-700 dark:text-amber-300 uppercase tracking-wider">{{ __('messages.categories') }}</span>
            </a>
        @endif

        <a href="{{ route('filament.admin.resources.articles.index') }}"
           class="flex flex-col items-center gap-2 p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl border border-purple-200 dark:border-purple-800/30 hover:shadow-md hover:scale-[1.02] transition-all duration-200 group">
            <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center group-hover:bg-purple-600 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <span class="text-xs font-bold text-purple-700 dark:text-purple-300 uppercase tracking-wider">{{ __('messages.all_articles') }}</span>
        </a>

        <a href="{{ route('filament.admin.resources.comments.index') }}"
           class="flex flex-col items-center gap-2 p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-800/30 hover:shadow-md hover:scale-[1.02] transition-all duration-200 group">
            <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center group-hover:bg-green-600 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
            <span class="text-xs font-bold text-green-700 dark:text-green-300 uppercase tracking-wider">{{ __('messages.comments') }}</span>
        </a>
    </div>
</div>
