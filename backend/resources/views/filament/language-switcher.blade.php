<div style="padding: 8px 16px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: auto;">
    <div class="flex items-center gap-1 mb-2 px-2">
        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-xs font-medium text-gray-400">{{ __('messages.language') }}</span>
    </div>
    <div style="display: flex; gap: 6px;">
        <a href="/admin-locale/en" 
           style="flex: 1; text-align: center; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; background-color: {{ session('admin_locale', 'ckb') === 'en' ? '#CC0000' : 'transparent' }}; color: {{ session('admin_locale', 'ckb') === 'en' ? '#fff' : '#9CA3AF' }}; border: 1px solid {{ session('admin_locale', 'ckb') === 'en' ? '#CC0000' : '#374151' }};">
            {{ __('messages.admin_locale_en') }}
        </a>
        <a href="/admin-locale/ckb" 
           style="flex: 1; text-align: center; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; background-color: {{ session('admin_locale', 'ckb') === 'ckb' ? '#CC0000' : 'transparent' }}; color: {{ session('admin_locale', 'ckb') === 'ckb' ? '#fff' : '#9CA3AF' }}; border: 1px solid {{ session('admin_locale', 'ckb') === 'ckb' ? '#CC0000' : '#374151' }};">
            {{ __('messages.admin_locale_ckb') }}
        </a>
    </div>
</div>
