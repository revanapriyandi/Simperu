@if ($servicesContent)
    <section id="layanan" class="py-12 md:py-20 bg-primary-50 dark:bg-gray-800/70 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 md:mb-14">
                <h2
                    class="text-2xl md:text-3xl lg:text-4xl font-bold font-poppins text-primary-700 dark:text-white animate-fade-in-up">
                    {{ $servicesContent->title }}
                </h2>
                @if ($servicesContent->description)
                    <p class="text-md md:text-lg text-gray-600 dark:text-gray-400 mt-3 md:mt-4 max-w-2xl mx-auto animate-fade-in-up"
                        style="animation-delay: 0.2s">
                        {{ $servicesContent->description }}
                    </p>
                @endif
            </div>

            @if ($servicesContent->content && isset($servicesContent->content['services']))
                <div class="swiper layananSwiper">
                    <div class="swiper-wrapper pb-12">
                        @foreach ($servicesContent->content['services'] as $index => $service)
                            <div class="swiper-slide">
                                <div
                                    class="bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 h-full flex flex-col">
                                    <div class="flex items-center mb-4">
                                        <div class="p-3 bg-primary-100 dark:bg-primary-500/20 rounded-full mr-4">
                                            @svg($service['icon'], 'w-6 h-6 text-primary-600 dark:text-primary-400')
                                        </div>
                                        <h3
                                            class="text-lg md:text-xl font-semibold font-poppins text-primary-700 dark:text-white">
                                            {{ $service['title'] }}
                                        </h3>
                                    </div>

                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                        {{ $service['description'] }}
                                    </p>

                                    @if (isset($service['features']) && is_array($service['features']))
                                        <ul
                                            class="text-gray-600 dark:text-gray-400 text-sm space-y-2 list-disc list-inside flex-grow">
                                            @foreach ($service['features'] as $feature)
                                                <li>{{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination layanan-pagination"></div>
                </div>
            @endif
        </div>
    </section>
@endif
