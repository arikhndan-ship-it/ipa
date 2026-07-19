@extends('layouts.app')

@section('content')
<div class="bg-background pb-20 overflow-hidden">
    {{-- Header --}}
    <div class="bg-[#0A0A0A] text-white py-20 border-b border-border relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none mix-blend-overlay"
             style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
        </div>
        <div class="absolute inset-0 pointer-events-none"
             style="background: radial-gradient(ellipse 60% 55% at 50% 30%, rgba(139,0,0,0.18) 0%, transparent 70%);">
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#8B0000] mb-3 animate-fade-in-up">
                    {{ __('messages.terms_of_service') }}
                </p>
                <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-5 animate-fade-in-up delay-200">
                    {{ __('messages.terms_of_service') }}
                </h1>
                <div class="mx-auto mb-6 h-px w-28 bg-gradient-to-r from-transparent via-[#8B0000] to-transparent animate-scale-x-in delay-400"></div>
                <p class="text-sm text-gray-400 animate-fade-in-up delay-300">
                    {{ setting('site_name_en', 'Khandantelegraph') }} — {{ __('messages.site_description') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-4xl mx-auto px-4 py-16">
        <div class="text-gray-300 space-y-8 leading-relaxed animate-fade-in-up">
            @if(app()->getLocale() === 'ckb')
            {{-- Kurdish version --}}
            <p class="text-sm text-gray-400">دوایین نوێکردنەوە: ١٦ی تەمموزی ٢٠٢٦</p>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">١. پەیمانی بەکارهێنان</h2>
                <p class="text-gray-400">
                    بە بەکارهێنانی پلاتفۆرمی خەندان تێڵیگراف، پەیمانی بەکارهێنانی ئێستا دەپەسەندیت. ئەگەر ڕێککەوت نەبیت لەسەر هیچ بەشێک، نابێت بە پلاتفۆرمەکەمان بگەیت.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٢. مۆڵەتی بەکارهێنان</h2>
                <p class="text-gray-400 mb-3">
                    ڕێگەت پێدەدرێت بە شێوەیەکی کاتی هەڵبگریت لە کۆپیەک لە ماددەکان بە مەبەستی بینینی کەسی و نابازرگانی. ئەمە مۆڵەت نییە، و نابێت:
                </p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 mr-4">
                    <li>ماددەکان دەستکاری بکەیت یان کۆپی بکەیت</li>
                    <li>ماددەکان بۆ مەبەستی بازرگانی یان نمایشی گشتی بەکار بهێنیت</li>
                    <li>هەوڵی دەرهێنانی کۆدی سەرچاوەی پلاتفۆرمەکە بدەیت</li>
                    <li>هیچ زانیارییەک لە پلاتفۆرمەکە بسڕیتەوە یان بگۆڕیت</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٣. ڕەچاوکردنی ڕێنماییەکان</h2>
                <p class="text-gray-400">
                    ڕەنگە ئەم پەیمانە لە هەر کاتێکدا نوێ بکرێتەوە. بەردەوامبوون لە بەکارهێنانی پلاتفۆرمەکە دوای هەر گۆڕانکارییەک واتە پەسەندکردنی گۆڕانکارییەکان.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٤. کۆمێنت و بەشداربوون</h2>
                <p class="text-gray-400 mb-3">
                    کاتێک کۆمێنت دەنێریت یان ماددەت هەڵدەگرێتەوە:
                </p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 mr-4">
                    <li>دەبێت ماف و ئازادییەکانی کەسانی دیکەت نەشێکێنێت</li>
                    <li>نابێت نایاسایی، بەزەییانە یان هەراسانکەر بێت</li>
                    <li>نابێت تێدای هیچ ڤایرۆسێک یان کۆدی زیانبەخش بێت</li>
                    <li>مافی خەندان تێڵیگرافمان هەیە بۆ سڕینەوەی هەر ماددەیەک کە پێویست ببینین</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٥. ڕەتکردنەوەی بەرپرسیارێتی</h2>
                <p class="text-gray-400">
                    ماددەکانی پلاتفۆرمی خەندان تێڵیگراف بە شێوەی "وەک خۆی" دابین کراوە. هیچ بەڵێنێک نادەین دەربارەی تەواوی، وردی، یان بەردەستبوونی ماددەکان.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٦. یاسای کەرتبوون</h2>
                <p class="text-gray-400">
                    ئەم پەیمانە لەژێر یاسای هەرێمی کوردستانەوە ڕێکخراوە و لێکدراوە.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٧. پەیوەندیمان پێوە بکە</h2>
                <p class="text-gray-400 mb-3">
                    ئەگەر هەر پرسیارێکت هەیە دەربارەی ئەم پەیمانە، تکایە پەیوەندیمان پێوە بکە:
                </p>
                <ul class="text-gray-400 space-y-1 mr-4">
                    <li>ئیمەیڵ: <a href="mailto:khandatelegraph@gmail.com" class="text-[#CC0000] hover:underline">khandatelegraph@gmail.com</a></li>
                    <li>تیلیگرام: <a href="{{ setting('telegram_url', 'https://t.me/khandantelegraph') }}" class="text-[#CC0000] hover:underline">{{ setting('telegram_username', '@khandantelegraph') }}</a></li>
                    <li>ماڵپەڕ: <a href="{{ setting('website_url', 'https://khandantelegraph.news') }}" class="text-[#CC0000] hover:underline">{{ setting('website_domain', 'khandantelegraph.news') }}</a></li>
                </ul>
            </section>
            @else
            {{-- English version --}}
            <p class="text-sm text-gray-400">Last updated: July 16, 2026</p>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">1. Acceptance of Terms</h2>
                <p class="text-gray-400">
                    By accessing or using {{ setting('site_name_en', 'Khandantelegraph') }} ("the Platform"), you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, you may not access the Platform.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">2. License to Use</h2>
                <p class="text-gray-400 mb-3">
                    Permission is granted to temporarily download one copy of the materials on the Platform for personal, non-commercial viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
                </p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                    <li>Modify or copy the materials</li>
                    <li>Use the materials for any commercial purpose or public display</li>
                    <li>Attempt to decompile or reverse engineer any software on the Platform</li>
                    <li>Remove any copyright or other proprietary notations from the materials</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">3. Modifications</h2>
                <p class="text-gray-400">
                    We may revise these Terms of Service at any time without notice. By continuing to use the Platform after any changes, you agree to be bound by the updated terms.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">4. Comments and User Content</h2>
                <p class="text-gray-400 mb-3">
                    When posting comments or other content on the Platform:
                </p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                    <li>You must not infringe on the rights of others</li>
                    <li>Content must not be unlawful, abusive, or harassing</li>
                    <li>Content must not contain viruses or malicious code</li>
                    <li>We reserve the right to remove any content at our discretion</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">5. Disclaimer</h2>
                <p class="text-gray-400">
                    The materials on the Platform are provided on an "as is" basis. We make no warranties, expressed or implied, and hereby disclaim all other warranties regarding the accuracy, reliability, or availability of the materials.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">6. Governing Law</h2>
                <p class="text-gray-400">
                    These terms shall be governed by and construed in accordance with the laws of the Kurdistan Region of Iraq.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">7. Contact Us</h2>
                <p class="text-gray-400 mb-3">
                    If you have any questions about these Terms of Service, please contact us:
                </p>
                <ul class="text-gray-400 space-y-1 ml-4">
                    <li>Email: <a href="mailto:khandatelegraph@gmail.com" class="text-[#CC0000] hover:underline">khandatelegraph@gmail.com</a></li>
                    <li>Telegram: <a href="{{ setting('telegram_url', 'https://t.me/khandantelegraph') }}" class="text-[#CC0000] hover:underline">{{ setting('telegram_username', '@khandantelegraph') }}</a></li>
                    <li>Website: <a href="{{ setting('website_url', 'https://khandantelegraph.news') }}" class="text-[#CC0000] hover:underline">{{ setting('website_domain', 'khandantelegraph.news') }}</a></li>
                </ul>
            </section>
            @endif
        </div>
    </div>
</div>
@endsection
