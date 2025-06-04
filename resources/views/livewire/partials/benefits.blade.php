@if ($benefitsContent)
    <section id="manfaat" class="py-12 md:py-20 bg-primary-50 dark:bg-gray-800/70 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 md:mb-14">
                <h2
                    class="text-2xl md:text-3xl lg:text-4xl font-bold font-poppins text-primary-700 dark:text-white animate-fade-in-up">
                    {{ $benefitsContent->title }}
                </h2>
                @if ($benefitsContent->description)
                    <p class="text-md md:text-lg text-gray-600 dark:text-gray-400 mt-3 md:mt-4 max-w-2xl mx-auto animate-fade-in-up"
                        style="animation-delay: 0.2s">
                        {{ $benefitsContent->description }}
                    </p>
                @endif
            </div>

            @if ($benefitsContent->content && isset($benefitsContent->content['benefits']))
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($benefitsContent->content['benefits'] as $index => $benefit)
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 animate-fade-in-up"
                            style="animation-delay: {{ 0.3 + $index * 0.1 }}s">
                            <div class="flex items-center mb-3">
                                <div class="p-3 bg-accent-100 dark:bg-accent-500/20 rounded-full mr-3">
                                    @svg($benefit['icon'], 'w-6 h-6 text-accent-600 dark:text-accent-400')
                                </div>
                                <h3 class="text-lg font-semibold font-poppins text-primary-700 dark:text-white">
                                    {{ $benefit['title'] }}
                                </h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                {{ $benefit['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
