<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(in_array(app()->getLocale(), ['ckb', 'ar', 'ku'])) dir="rtl" @else dir="ltr" @endif class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0A0A0A">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('messages.site_name') }} - {{ $title ?? '' }}</title>
    <meta name="description" content="{{ $description ?? __('messages.site_description') }}">

    <!-- Open Graph -->
    <meta property="og:site_name" content="{{ __('messages.site_name') }}">
    <meta property="og:title" content="{{ $title ?? __('messages.site_name') }}">
    <meta property="og:description" content="{{ $description ?? __('messages.site_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    @if(isset($ogImage) && $ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="font-sans antialiased bg-background text-foreground flex flex-col min-h-screen overflow-x-hidden">
    @include('components.header')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('components.footer')

    <!-- Reveal fallback -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reveal all .reveal elements after a short delay as fallback
            setTimeout(function() {
                document.querySelectorAll('.reveal:not(.revealed)').forEach(function(el) {
                    el.classList.add('revealed');
                });
            }, 800);
        });
    </script>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>
