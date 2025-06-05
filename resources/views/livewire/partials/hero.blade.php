@if ($heroContent)
    <section id="beranda"
        class="relative pt-16 min-h-screen flex items-center overflow-hidden bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">

        {{-- Hero Background Swiper --}}
        <div class="swiper heroSwiper absolute inset-0 w-full h-full z-0">
            <div class="swiper-wrapper">
                @forelse ($this->heroImages as $index => $image)
                    <div class="swiper-slide hero-bg-slide" style="background-image: url('{{ $image['url'] }}');"
                        data-swiper-slide-index="{{ $index }}">
                        <div class="absolute inset-0 bg-black/60 dark:bg-black/70"></div>
                        @if (isset($image['alt']))
                            <div class="sr-only">{{ $image['alt'] }}</div>
                        @endif
                    </div>
                @empty
                    {{-- Fallback slide if no images are available --}}
                    <div class="swiper-slide hero-bg-slide"
                        style="background: linear-gradient(135deg, #1E3A8A 0%, #059669 100%);">
                        <div class="absolute inset-0 bg-black/40 dark:bg-black/60"></div>
                    </div>
                @endforelse
            </div>

            @if (count($this->heroImages) > 1)
                <div class="swiper-button-next text-white hover:text-accent-400 transition-colors"></div>
                <div class="swiper-button-prev text-white hover:text-accent-400 transition-colors"></div>
                <div class="swiper-pagination"></div>
            @endif
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

                {{-- Dynamic Content Blocks --}}
                @if ($heroContent->content && is_array($heroContent->content))
                    <div class="md:w-2/5 lg:w-4/12 w-full max-w-md mt-8 sm:mt-10 md:mt-0 animate-fade-in-up md:animate-slide-in-right space-y-4"
                        style="animation-delay: 0.2s">

                        @foreach ($heroContent->content as $blockIndex => $block)
                            @if (isset($block['type']))
                                {{-- Feature List Block --}}
                                @if ($block['type'] === 'feature_list' && isset($block['data']['features']))
                                    <div
                                        class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md p-4 sm:p-6 rounded-xl shadow-2xl border border-white/20 dark:border-gray-700">
                                        <div class="border-b-2 border-accent-400 pb-2 mb-3 sm:mb-4">
                                            <h2
                                                class="text-lg sm:text-xl font-semibold text-white dark:text-white font-poppins text-center md:text-left">
                                                FITUR UNGGULAN
                                            </h2>
                                        </div>
                                        <ul class="space-y-2 sm:space-y-3">
                                            @foreach ($block['data']['features'] as $index => $feature)
                                                <li class="flex items-center p-2 sm:p-3 bg-white/20 dark:bg-gray-700/40 rounded-lg hover:bg-white/30 dark:hover:bg-gray-700/60 transition-all duration-300 transform hover:scale-[1.02]"
                                                    style="animation-delay: {{ 0.4 + $blockIndex * 0.1 + $index * 0.05 }}s">
                                                    <div
                                                        class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-accent-500 rounded-full flex items-center justify-center mr-2 sm:mr-3 shadow-lg">
                                                        @if (isset($feature['icon']) && $feature['icon'])
                                                            @svg($feature['icon'], 'w-4 h-4 sm:w-5 sm:h-5 text-white')
                                                        @else
                                                            @svg('heroicon-o-star', 'w-4 h-4 sm:w-5 sm:h-5 text-white')
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow">
                                                        <h3
                                                            class="text-sm sm:text-md font-semibold text-white dark:text-white">
                                                            {{ $feature['title'] ?? 'Feature' }}
                                                        </h3>
                                                        @if (isset($feature['description']))
                                                            <p class="text-xs text-gray-100 dark:text-gray-200">
                                                                {{ $feature['description'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    @svg('heroicon-o-chevron-right', 'w-4 h-4 sm:w-5 sm:h-5 text-gray-200 dark:text-gray-300 ml-2 flex-shrink-0')
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Statistics Block --}}
                                @if ($block['type'] === 'statistics')
                                    <div
                                        class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md p-4 sm:p-6 rounded-xl shadow-2xl border border-white/20 dark:border-gray-700">
                                        <div class="border-b-2 border-accent-400 pb-2 mb-3 sm:mb-4">
                                            <h2
                                                class="text-lg sm:text-xl font-semibold text-white dark:text-white font-poppins text-center md:text-left">
                                                STATISTIK
                                            </h2>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            @if (isset($block['data']['show_families']) && $block['data']['show_families'])
                                                <div class="text-center p-3 bg-white/20 dark:bg-gray-700/40 rounded-lg">
                                                    <div class="text-xl sm:text-2xl font-bold text-accent-400">
                                                        {{ $statistics['total_families'] }}</div>
                                                    <div class="text-xs text-gray-100 dark:text-gray-200">Keluarga</div>
                                                </div>
                                            @endif
                                            @if (isset($block['data']['show_members']) && $block['data']['show_members'])
                                                <div class="text-center p-3 bg-white/20 dark:bg-gray-700/40 rounded-lg">
                                                    <div class="text-xl sm:text-2xl font-bold text-accent-400">
                                                        {{ $statistics['total_members'] }}</div>
                                                    <div class="text-xs text-gray-100 dark:text-gray-200">Anggota</div>
                                                </div>
                                            @endif
                                            @if (isset($block['data']['show_announcements']) && $block['data']['show_announcements'])
                                                <div class="text-center p-3 bg-white/20 dark:bg-gray-700/40 rounded-lg">
                                                    <div class="text-xl sm:text-2xl font-bold text-accent-400">
                                                        {{ $statistics['total_announcements'] }}</div>
                                                    <div class="text-xs text-gray-100 dark:text-gray-200">Pengumuman
                                                    </div>
                                                </div>
                                            @endif
                                            @if (isset($block['data']['show_houses']) && $block['data']['show_houses'])
                                                <div class="text-center p-3 bg-white/20 dark:bg-gray-700/40 rounded-lg">
                                                    <div class="text-xl sm:text-2xl font-bold text-accent-400">
                                                        {{ $statistics['active_houses'] }}</div>
                                                    <div class="text-xs text-gray-100 dark:text-gray-200">Rumah Aktif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Benefits Grid Block --}}
                                @if ($block['type'] === 'benefit_grid' && isset($block['data']['benefits']))
                                    <div
                                        class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md p-4 sm:p-6 rounded-xl shadow-2xl border border-white/20 dark:border-gray-700">
                                        <div class="border-b-2 border-accent-400 pb-2 mb-3 sm:mb-4">
                                            <h2
                                                class="text-lg sm:text-xl font-semibold text-white dark:text-white font-poppins text-center md:text-left">
                                                KEUNTUNGAN
                                            </h2>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach ($block['data']['benefits'] as $index => $benefit)
                                                <div
                                                    class="flex items-start p-2 sm:p-3 bg-white/20 dark:bg-gray-700/40 rounded-lg hover:bg-white/30 dark:hover:bg-gray-700/60 transition-all duration-300">
                                                    <div
                                                        class="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 bg-green-500 rounded-full flex items-center justify-center mr-2 sm:mr-3">
                                                        @if (isset($benefit['icon']) && $benefit['icon'])
                                                            @svg($benefit['icon'], 'w-3 h-3 sm:w-4 sm:h-4 text-white')
                                                        @else
                                                            @svg('heroicon-o-check-circle', 'w-3 h-3 sm:w-4 sm:h-4 text-white')
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow">
                                                        <h4 class="text-sm font-semibold text-white dark:text-white">
                                                            {{ $benefit['title'] ?? 'Benefit' }}
                                                        </h4>
                                                        @if (isset($benefit['description']))
                                                            <p class="text-xs text-gray-100 dark:text-gray-200">
                                                                {{ $benefit['description'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Contact Info Block --}}
                                @if ($block['type'] === 'contact_info')
                                    <div
                                        class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md p-4 sm:p-6 rounded-xl shadow-2xl border border-white/20 dark:border-gray-700">
                                        <div class="border-b-2 border-accent-400 pb-2 mb-3 sm:mb-4">
                                            <h2
                                                class="text-lg sm:text-xl font-semibold text-white dark:text-white font-poppins text-center md:text-left">
                                                KONTAK
                                            </h2>
                                        </div>
                                        <div class="space-y-2">
                                            @if (isset($block['data']['email']))
                                                <div
                                                    class="flex items-center p-2 bg-white/20 dark:bg-gray-700/40 rounded-lg">
                                                    @svg('heroicon-o-envelope', 'w-4 h-4 text-accent-400 mr-2 flex-shrink-0')
                                                    <span
                                                        class="text-sm text-white">{{ $block['data']['email'] }}</span>
                                                </div>
                                            @endif
                                            @if (isset($block['data']['phone']))
                                                <div
                                                    class="flex items-center p-2 bg-white/20 dark:bg-gray-700/40 rounded-lg">
                                                    @svg('heroicon-o-phone', 'w-4 h-4 text-accent-400 mr-2 flex-shrink-0')
                                                    <span
                                                        class="text-sm text-white">{{ $block['data']['phone'] }}</span>
                                                </div>
                                            @endif
                                            @if (isset($block['data']['address']))
                                                <div
                                                    class="flex items-start p-2 bg-white/20 dark:bg-gray-700/40 rounded-lg">
                                                    @svg('heroicon-o-map-pin', 'w-4 h-4 text-accent-400 mr-2 flex-shrink-0 mt-0.5')
                                                    <span
                                                        class="text-sm text-white">{{ $block['data']['address'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Social Links Block --}}
                                @if ($block['type'] === 'social_links')
                                    <div
                                        class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md p-4 sm:p-6 rounded-xl shadow-2xl border border-white/20 dark:border-gray-700">
                                        <div class="border-b-2 border-accent-400 pb-2 mb-3 sm:mb-4">
                                            <h2
                                                class="text-lg sm:text-xl font-semibold text-white dark:text-white font-poppins text-center md:text-left">
                                                SOSIAL MEDIA
                                            </h2>
                                        </div>
                                        <div class="flex justify-center space-x-3">
                                            @if (isset($block['data']['facebook']) && $block['data']['facebook'])
                                                <a href="{{ $block['data']['facebook'] }}"
                                                    class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                                    @svg('heroicon-s-globe-alt', 'w-4 h-4 text-white')
                                                </a>
                                            @endif
                                            @if (isset($block['data']['instagram']) && $block['data']['instagram'])
                                                <a href="{{ $block['data']['instagram'] }}"
                                                    class="w-8 h-8 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors">
                                                    @svg('heroicon-s-camera', 'w-4 h-4 text-white')
                                                </a>
                                            @endif
                                            @if (isset($block['data']['twitter']) && $block['data']['twitter'])
                                                <a href="{{ $block['data']['twitter'] }}"
                                                    class="w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                                                    @svg('heroicon-s-chat-bubble-left', 'w-4 h-4 text-white')
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
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
