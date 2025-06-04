@if ($aboutContent)
    <section id="tentang"
        class="py-12 md:py-20 bg-white dark:bg-gray-800 transition-colors duration-300 relative overflow-hidden">
        {{-- Background Elements --}}
        <div
            class="absolute top-0 left-0 w-72 h-72 bg-blue-300/20 rounded-full -translate-x-36 -translate-y-36 blur-3xl">
        </div>
        <div
            class="absolute bottom-0 right-0 w-72 h-72 bg-emerald-300/20 rounded-full translate-x-36 translate-y-36 blur-3xl">
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-10 md:mb-14">
                <h2
                    class="text-2xl md:text-3xl lg:text-4xl font-bold font-poppins text-primary-700 dark:text-white animate-fade-in-up">
                    {{ $aboutContent->title }}
                </h2>
                @if ($aboutContent->description)
                    <p class="text-md md:text-lg text-gray-600 dark:text-gray-400 mt-3 md:mt-4 max-w-3xl mx-auto animate-fade-in-up"
                        style="animation-delay: 0.2s">
                        {{ $aboutContent->description }}
                    </p>
                @endif
            </div>

            @if ($aboutContent->content && isset($aboutContent->content['features']))
                <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($aboutContent->content['features'] as $index => $feature)
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group animate-fade-in-up"
                            style="animation-delay: {{ 0.4 + $index * 0.2 }}s">
                            <div
                                class="flex justify-center items-center w-12 h-12 md:w-16 md:h-16 bg-accent-100 dark:bg-accent-800/50 rounded-full mx-auto mb-4 md:mb-6 group-hover:scale-110 transition-transform duration-300">
                                @svg($feature['icon'], 'w-6 h-6 md:w-8 md:h-8 text-accent-600 dark:text-accent-400')
                            </div>
                            <h3
                                class="text-lg md:text-xl font-semibold font-poppins text-primary-700 dark:text-white mb-2 md:mb-3 text-center group-hover:text-accent-600 dark:group-hover:text-accent-400 transition-colors">
                                {{ $feature['title'] }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm text-center leading-relaxed">
                                {{ $feature['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
