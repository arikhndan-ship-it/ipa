@extends('layouts.app')

@section('content')
<div class="bg-background min-h-screen flex items-center justify-center overflow-hidden">
    <div class="text-center px-4 py-20">
        <div class="text-[#8B0000] text-8xl md:text-9xl font-serif font-bold mb-6 animate-fade-in-up">404</div>
        <div class="mx-auto mb-8 h-px w-28 bg-gradient-to-r from-transparent via-[#8B0000] to-transparent"></div>
        <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4 animate-fade-in-up delay-200">
            {{ app()->getLocale() === 'ckb' ? 'پەڕە نەدۆزرایەوە' : 'Page Not Found' }}
        </h1>
        <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto animate-fade-in-up delay-300">
            {{ app()->getLocale() === 'ckb' ? 'پەڕەی کە دەگەڕێیت نەدۆزرایەوە. ڕەنگە لابرابێت یان ناونیشانەکە هەڵە بێت.' : 'The page you are looking for could not be found. It may have been removed or the URL may be incorrect.' }}
        </p>
        <a href="{{ route('home') }}"
           class="inline-block bg-[#8B0000] hover:bg-[#CC0000] text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg animate-fade-in-up delay-400">
            {{ app()->getLocale() === 'ckb' ? 'بگەڕێوە بۆ ماڵەوە' : 'Back to Home' }}
        </a>
    </div>
</div>
@endsection
