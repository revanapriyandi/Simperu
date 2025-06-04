@if ($heroContent)
    <section id="beranda"
        class="relative pt-16 min-h-screen flex items-center overflow-hidden bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
        <div class="swiper heroSwiper absolute inset-0 w-full h-full z-0">
            <div class="swiper-wrapper">
                @foreach ($this->heroImages as $index => $image)
                    <div class="swiper-slide hero-bg-slide" style="background-image: url('{{ $image['url'] }}');">
                        <div class="absolute inset-0 bg-black/60 dark:bg-black/70"></div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 z-10 h-full flex items-center">
            <div class="flex flex-col md:flex-row items-center md:justify-between gap-8 w-full py-10 md:py-0">
                {{-- Main Content --}}
                <div class="md:w-3/5 lg:w-7/12 text-center md:text-left">
                    @if ($heroContent->subtitle)
                        <div class="mb-4 animate-fade-in-down">
                            <span
                                class="inline-block bg-accent-500/90 text-white dark:text-white text-xs sm:text-sm font-semibold px-3 py-1 sm:px-4 rounded-full uppercase tracking-wider">
                                {{ $heroContent->subtitle }}
                            </span>
                        </div>
                    @endif

                    @if ($heroContent->title)
                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-poppins text-white dark:text-white mb-4 sm:mb-6 leading-tight animate-fade-in-down"
                            style="animation-delay: 0.1s">
                            {!! $heroContent->title !!}
                        </h1>
                    @endif

                    @if ($heroContent->description)
                        <p class="text-base sm:text-lg md:text-xl text-gray-200 dark:text-gray-300 mb-6 sm:mb-10 animate-fade-in-up"
                            style="animation-delay: 0.2s">
                            {{ $heroContent->description }}
                        </p>
                    @endif

                    <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start space-y-3 sm:space-y-0 sm:space-x-4 animate-fade-in-up"
                        style="animation-delay: 0.3s">
                        @if ($heroContent->button_text && $heroContent->button_link)
                            <a href="{{ $heroContent->button_link }}"
                                class="bg-accent-500 hover:bg-accent-600 text-white font-semibold py-2.5 px-6 sm:py-3 sm:px-8 rounded-lg text-base sm:text-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                                {{ $heroContent->button_text }}
                            </a>
                        @endif

                        <a href="#kontak"
                            class="bg-white hover:bg-gray-100 text-primary-700 dark:text-gray-800 dark:bg-gray-200 dark:hover:bg-gray-300 font-semibold py-2.5 px-6 sm:py-3 sm:px-8 rounded-lg text-base sm:text-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                            Hubungi Kami
                        </a>
                    </div>
                </div>

                {{-- Feature Highlights --}}
                @if ($heroContent->content && isset($heroContent->content['features']))
                    <div class="md:w-2/5 lg:w-4/12 w-full max-w-md mt-8 sm:mt-10 md:mt-0 animate-fade-in-up md:animate-slide-in-right"
                        style="animation-delay: 0.2s">
                        <div
                            class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md p-4 sm:p-6 rounded-xl shadow-2xl border border-white/20 dark:border-gray-700">
                            <div class="border-b-2 border-accent-400 pb-2 mb-3 sm:mb-4">
                                <h2
                                    class="text-lg sm:text-xl font-semibold text-white dark:text-white font-poppins text-center md:text-left">
                                    FITUR UNGGULAN
                                </h2>
                            </div>

                            <ul class="space-y-2 sm:space-y-3">
                                @foreach ($heroContent->content['features'] as $index => $feature)
                                    <li class="flex items-center p-2 sm:p-3 bg-white/20 dark:bg-gray-700/40 rounded-lg hover:bg-white/30 dark:hover:bg-gray-700/60 transition-all duration-300 transform hover:scale-[1.02]"
                                        style="animation-delay: {{ 0.4 + $index * 0.1 }}s">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-accent-500 rounded-full flex items-center justify-center mr-2 sm:mr-3 shadow-lg">
                                            @svg($feature['icon'], 'w-4 h-4 sm:w-5 sm:h-5 text-white')
                                        </div>
                                        <div class="flex-grow">
                                            <h3 class="text-sm sm:text-md font-semibold text-white dark:text-white">
                                                {{ $feature['title'] }}
                                            </h3>
                                            <p class="text-xs text-gray-100 dark:text-gray-200">
                                                {{ $feature['description'] }}
                                            </p>
                                        </div>
                                        @svg('heroicon-o-chevron-right', 'w-4 h-4 sm:w-5 sm:h-5 text-gray-200 dark:text-gray-300 ml-2 flex-shrink-0 group-hover:transform group-hover:translate-x-1 transition-transform')
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10">
            <a href="#tentang" class="block animate-bounce">
                <div class="w-6 h-10 border-2 border-white/50 dark:border-white/40 rounded-full flex justify-center">
                    <div class="w-1 h-3 bg-white/70 dark:bg-white/80 rounded-full mt-2 animate-pulse"></div>
                </div>
            </a>
        </div>
    </section>
@endif
