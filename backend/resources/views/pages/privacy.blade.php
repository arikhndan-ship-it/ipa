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
                    {{ __('messages.privacy_policy') }}
                </p>
                <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-5 animate-fade-in-up delay-200">
                    {{ __('messages.privacy_policy') }}
                </h1>
                <div class="mx-auto mb-6 h-px w-28 bg-gradient-to-r from-transparent via-[#8B0000] to-transparent animate-scale-x-in delay-400"></div>
                <p class="text-sm text-gray-400 animate-fade-in-up delay-300">
                    {{ __('messages.site_name') }} — {{ __('messages.site_description') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-4xl mx-auto px-4 py-16">
        <div class="text-gray-300 space-y-8 leading-relaxed animate-fade-in-up">
            @if(app()->getLocale() === 'ckb')
            {{-- Kurdish version --}}
            <p class="text-sm text-gray-400">دوایین نوێکردنەوە: ١٥ی تەمموزی ٢٠٢٦</p>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">١. پێشەکی</h2>
                <p class="text-gray-400">
                    خەندان تێڵیگراف ("ئێمە"، "ئێمەمان" یان "ئێمەین") پابەندە بە پاراستنی تایبەتمەندییەکەت. ئەم سیاسەتی تایبەتمەندییە ڕوون دەکاتەوە کە چۆن زانیارییەکانی کۆدەکەینەوە، بەکاری دەهێنین، ئاشکرای دەکەین و پارێزگاری لێدەکەین کاتێک سەردانی ماڵپەڕ و ئەپلیکەیشنی مۆبایلمان دەکەیت.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٢. زانیارییەکانی کۆدەکەینەوە</h2>
                <h3 class="text-lg font-semibold text-gray-200 mb-2">زانیاری کەسی</h3>
                <p class="text-gray-400 mb-3">
                    ڕەنگە زانیارییەکی کەسی وەک ناو و ئیمەیڵ کۆبکەینەوە کاتێک:
                </p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 mr-4">
                    <li>پەیوەندیمان پێوە دەکەیت لە ڕێگەی فۆرمی پەیوەندییەوە</li>
                    <li>زانیاری یان ڕاپۆرتێک دەنێریت</li>
                    <li>بەشدار دەبیت لە ئاگادارکردنەوەکان</li>
                    <li>بۆچوونێک دەنێریت لەسەر وتارێک</li>
                </ul>

                <h3 class="text-lg font-semibold text-gray-200 mb-2 mt-6">زانیاری ناکەسی</h3>
                <p class="text-gray-400">
                    بە شێوەیەکی خۆکارانە زانیاری ناکەسی کۆدەکەینەوە کاتێک سەردانی پلاتفۆرمەکەمان دەکەیت، لەوانە ناونیشانی IP، جۆری وێبگەڕ، جۆری ئامێر، سیستەمی کارپێکردن و ڕەفتاری گەڕان. ئەم زانیارییە بۆ شیکاری و باشترکردنی خزمەتگوزارییەکانمان بەکار دەهێنرێت.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٣. چۆنیەتی بەکارهێنانی زانیارییەکانت</h2>
                <p class="text-gray-400 mb-3">ئێمە زانیارییە کۆکراوەکان بۆ ئەم مەبەستانە بەکار دەهێنین:</p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 mr-4">
                    <li>بۆ دابینکردن، کارپێکردن و پاراستنی ماڵپەڕ و ئەپلیکەیشنی مۆبایل</li>
                    <li>بۆ وەڵامدانەوەی پرس و پرسیارەکانت</li>
                    <li>بۆ ناردنی ئاگادارکردنەوە دەربارەی وتار و نوێکارییە نوێیەکان</li>
                    <li>بۆ باشترکردنی ئەزموونی بەکارهێنەر و شیکاری بەکارهێنانی پلاتفۆرم</li>
                    <li>بۆ دۆزینەوە، ڕێگریکردن و چارەسەرکردنی کێشە تەکنیکی و ئەمنییەکان</li>
                    <li>بۆ ڕێزلێگرتن لە بەرپرسیاریێتییە یاساییەکان</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٤. پاراستنی زانیاری</h2>
                <p class="text-gray-400">
                    ئێمە ڕێکارە تەکنیکی و ڕێکخستنییە گونجاوەکان جێبەجێ دەکەین بۆ پاراستنی زانیارییە کەسییەکانت. بەڵام تکایە بزانە کە هیچ ڕێگەیەک بۆ گواستنەوە لە ڕێگەی ئینتەرنێت یان هەڵگرتنی ئەلیکترۆنی ١٠٠٪ پارێزراو نییە. هەرچەندە هەوڵ دەدەین زانیارییەکانت بپارێزین، ناتوانین پاراستنی تەواوی مسۆگەر بکەین.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٥. خزمەتگوزارییەکانی لایەنی سێیەم</h2>
                <p class="text-gray-400">
                    ئێمە زانیارییە کەسییەکانت نافرۆشین، بازرگانی پێناکەین یان بە کرێ نادەین. ڕەنگە زانیارییەکانت لەگەڵ ڕێکخراوە پشتیوانییەکانی لایەنی سێیەم هاوبەش بکەین کە یارمەتیمان دەدەن لە کارپێکردنی پلاتفۆرمەکەمان، بە مەرجێک ئەو لایەنان ڕازی بن بە نهێنی ڕاگرتنی زانیارییەکان.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٦. کووکییەکان (Cookies)</h2>
                <p class="text-gray-400">
                    پلاتفۆرمەکەمان ڕەنگە کووکی و تەکنەلۆژیای شوێنگەڕاندنی هاوشێوە بەکار بهێنێت بۆ باشترکردنی ئەزموونەکەت. دەتوانیت کووکی لە ڕێکخستنەکانی وێبگەڕەکەتدا ناچالاک بکەیت، هەرچەندە ئەمە ڕەنگە کاریگەری لەسەر هەندێک ئەرکی پلاتفۆرمەکەمان هەبێت.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٧. مافەکانی تۆ</h2>
                <p class="text-gray-400 mb-3">مافەکانی تۆ بریتین لە:</p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 mr-4">
                    <li>دەستگەیشتن بە زانیارییە کەسییەکانی خۆت کە لای ئێمەن</li>
                    <li>داواکردنی ڕاستکردنەوەی زانیاری هەڵەکان</li>
                    <li>داواکردنی سڕینەوەی زانیارییەکانت</li>
                    <li>کشێنەوەی ڕەزامەندی لە هەر کاتێکدا</li>
                    <li>ڕەتکردنەوەی پرۆسێسکردنی زانیارییەکانت</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٨. گۆڕانکاری لەم سیاسەتەدا</h2>
                <p class="text-gray-400">
                    ڕەنگە ئەم سیاسەتی تایبەتمەندییە نوێ بکەینەوە. ئاگادارت دەکەینەوە بە بڵاوکردنەوەی سیاسەتی نوێ لەم پەڕەیەدا و نوێکردنەوەی ڕێکەوتی "دوایین نوێکردنەوە".
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">٩. پەیوەندیمان پێوە بکە</h2>
                <p class="text-gray-400 mb-3">
                    ئەگەر هەر پرسیارێکت هەیە دەربارەی ئەم سیاسەتە، تکایە پەیوەندیمان پێوە بکە:
                </p>
                <ul class="text-gray-400 space-y-1 mr-4">
                    <li>ئیمەیڵ: <a href="mailto:khandatelegraph@gmail.com" class="text-[#CC0000] hover:underline">khandatelegraph@gmail.com</a></li>
                    <li>تیلیگرام: <a href="{{ setting('telegram_url', 'https://t.me/khandantelegraph') }}" class="text-[#CC0000] hover:underline">{{ setting('telegram_username', '@khandantelegraph') }}</a></li>
                    <li>ماڵپەڕ: <a href="{{ setting('website_url', 'https://khandantelegraph.news') }}" class="text-[#CC0000] hover:underline">{{ setting('website_domain', 'khandantelegraph.news') }}</a></li>
                </ul>
            </section>
            @else
            {{-- English version --}}
            <p class="text-sm text-gray-400">Last updated: July 15, 2026</p>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">1. Introduction</h2>
                <p class="text-gray-400">
                    {{ setting('site_name_en', 'Khandantelegraph') }} ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and mobile application.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">2. Information We Collect</h2>
                <h3 class="text-lg font-semibold text-gray-200 mb-2">Personal Information</h3>
                <p class="text-gray-400 mb-3">
                    We may collect personally identifiable information such as your name and email address when you:
                </p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                    <li>Contact us through our contact form</li>
                    <li>Submit a tip or report</li>
                    <li>Subscribe to notifications</li>
                    <li>Post a comment on an article</li>
                </ul>

                <h3 class="text-lg font-semibold text-gray-200 mb-2 mt-6">Non-Personal Information</h3>
                <p class="text-gray-400">
                    We automatically collect certain non-personal information when you visit our platform, including your IP address, browser type, device type, operating system, and browsing behavior. This information is used for analytics and to improve our services.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">3. How We Use Your Information</h2>
                <p class="text-gray-400 mb-3">We use the information we collect for the following purposes:</p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                    <li>To provide, operate, and maintain our website and mobile application</li>
                    <li>To respond to your inquiries, comments, or questions</li>
                    <li>To send you notifications about new articles or updates (if you opt in)</li>
                    <li>To improve user experience and analyze platform usage</li>
                    <li>To detect, prevent, and address technical issues or security threats</li>
                    <li>To comply with legal obligations</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">4. Data Protection</h2>
                <p class="text-gray-400">
                    We implement appropriate technical and organizational security measures to protect your personal information. However, please note that no method of transmission over the Internet or electronic storage is 100% secure. While we strive to protect your data, we cannot guarantee its absolute security.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">5. Third-Party Services</h2>
                <p class="text-gray-400">
                    We do not sell, trade, or rent your personal information to third parties. We may share your information with trusted third-party service providers who assist us in operating our platform, conducting our business, or servicing you, provided those parties agree to keep this information confidential.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">6. Cookies</h2>
                <p class="text-gray-400">
                    Our platform may use cookies and similar tracking technologies to enhance your experience. You can choose to disable cookies in your browser settings, though this may affect some functionality of our platform.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">7. Your Rights</h2>
                <p class="text-gray-400 mb-3">You have the right to:</p>
                <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                    <li>Access the personal data we hold about you</li>
                    <li>Request correction of inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Withdraw consent at any time</li>
                    <li>Object to processing of your data</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">8. Changes to This Policy</h2>
                <p class="text-gray-400">
                    We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-serif font-bold text-white mb-4">9. Contact Us</h2>
                <p class="text-gray-400 mb-3">
                    If you have any questions about this Privacy Policy, please contact us:
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
