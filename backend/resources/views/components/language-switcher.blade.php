<div class="flex items-center space-x-2 rtl:space-x-reverse">
    @php
        $currentLocale = app()->getLocale();
        $currentUrl = url()->current();
    @endphp

    <a href="{{ route('language.switch', 'en') }}"
       class="text-xs font-medium px-2 py-1 rounded transition-colors duration-150
              {{ $currentLocale === 'en' ? 'bg-white text-primary font-bold' : 'text-white hover:bg-red-700' }}">
        EN
    </a>
    <span class="text-red-300 text-xs">|</span>
    <a href="{{ route('language.switch', 'ckb') }}"
       class="text-xs font-medium px-2 py-1 rounded transition-colors duration-150
              {{ $currentLocale === 'ckb' ? 'bg-white text-primary font-bold' : 'text-white hover:bg-red-700' }}">
        کوردی
    </a>
</div>
