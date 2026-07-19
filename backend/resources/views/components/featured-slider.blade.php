<div class="relative overflow-hidden rounded-xl shadow-lg bg-white"
     x-data='{
        slides: @json($featuredArticles ?? [], JSON_HEX_APOS),
        currentSlide: 0,
        autoplay: null,
        init() {
            if (this.slides.length > 0) {
                this.startAutoplay();
            }
        },
        startAutoplay() {
            this.autoplay = setInterval(() => {
                this.nextSlide();
            }, 5000);
        },
        stopAutoplay() {
            if (this.autoplay) {
                clearInterval(this.autoplay);
                this.autoplay = null;
            }
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        },
        goToSlide(index) {
            this.currentSlide = index;
            this.stopAutoplay();
            this.startAutoplay();
        }
     }'
     x-init="init()"
     @mouseenter="stopAutoplay()"
     @mouseleave="startAutoplay()">

    @if(isset($featuredArticles) && count($featuredArticles) > 0)
        {{-- Slides --}}
        <div class="relative aspect-[21/9] md:aspect-[21/9] bg-gray-200">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="currentSlide === index"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 transform scale-105"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute inset-0">
                    {{-- Image --}}
                    <a :href="'{{ url('/articles') }}/' + (slide.slug || slide.id)" class="block w-full h-full">
                        <img :src="slide.image || 'https://placehold.co/1200x600/CC0000/FFFFFF?text=Khandan'"
                             :alt="slide.title"
                             class="w-full h-full object-cover">

                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

                        {{-- Content Overlay --}}
                        <div class="absolute bottom-0 left-0 right-0 p-4 md:p-8">
                            @if(isset($featuredArticles[0]))
                            <span class="inline-block bg-primary text-white text-xs font-bold uppercase px-3 py-1 rounded mb-2 md:mb-3">
                                <span x-text="slide.category_name || '{{ __('messages.featured') }}'"></span>
                            </span>
                            @endif
                            <h2 class="text-white font-bold text-lg md:text-2xl lg:text-3xl leading-tight mb-2">
                                <span x-text="slide.title"></span>
                            </h2>
                            <p class="text-gray-200 text-sm md:text-base hidden sm:block line-clamp-2">
                                <span x-text="slide.excerpt || ''"></span>
                            </p>
                            <div class="flex items-center mt-2 md:mt-3 text-gray-300 text-xs md:text-sm">
                                <span x-text="slide.author_name || '{{ __('messages.site_name') }}'"></span>
                                <span class="mx-2">•</span>
                                <span x-text="slide.published_at ? new Date(slide.published_at).toLocaleDateString() : ''"></span>
                            </div>
                        </div>
                    </a>
                </div>
            </template>
        </div>

        {{-- Navigation Arrows --}}
        <button @click="prevSlide()"
                class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white p-2 rounded-full transition-colors z-10">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button @click="nextSlide()"
                class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white p-2 rounded-full transition-colors z-10">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        {{-- Navigation Dots --}}
        <div class="absolute bottom-2 md:bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 rtl:space-x-reverse z-10">
            <template x-for="(slide, index) in slides" :key="'dot-' + index">
                <button @click="goToSlide(index)"
                        class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full transition-all duration-300"
                        :class="currentSlide === index ? 'bg-primary w-6 md:w-8' : 'bg-white/60 hover:bg-white/80'">
                </button>
            </template>
        </div>
    @else
        {{-- Fallback when no featured articles --}}
        <div class="bg-gradient-to-r from-primary to-primary-dark p-8 md:p-16 text-center">
            <span class="inline-block bg-white/20 text-white text-xs font-bold uppercase px-3 py-1 rounded mb-4">
                {{ __('messages.featured') }}
            </span>
            <h2 class="text-white font-bold text-2xl md:text-4xl mb-4">
                {{ __('messages.site_name') }} — {{ __('messages.site_description') }}
            </h2>
            <p class="text-gray-200 text-sm md:text-base max-w-2xl mx-auto">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
        </div>
    @endif
</div>
