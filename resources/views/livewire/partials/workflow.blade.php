@if ($processFlowContent)
    <section id="alur"
        class="py-12 md:py-20 bg-white dark:bg-gray-800 transition-colors duration-300 relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,<svg width=\"40\"
                height=\"40\" viewBox=\"0 0 40 40\" xmlns=\"http://www.w3.org/2000/svg\">
                <g fill=\"none\" fill-rule=\"evenodd\">
                    <g fill=\"%23000000\" fill-opacity=\"0.1\">
                        <path d=\"m0 40l40-40h-40v40z\" />
                    </g></svg>'); background-size: 40px 40px;">
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-10 md:mb-14">
                <h2
                    class="text-2xl md:text-3xl lg:text-4xl font-bold font-poppins text-primary-700 dark:text-white animate-fade-in-up">
                    {{ $processFlowContent->title }}
                </h2>
                @if ($processFlowContent->description)
                    <p class="text-md md:text-lg text-gray-600 dark:text-gray-400 mt-3 md:mt-4 max-w-3xl mx-auto animate-fade-in-up"
                        style="animation-delay: 0.2s">
                        {{ $processFlowContent->description }}
                    </p>
                @endif
            </div>

            @if ($processFlowContent->content)
                <div class="grid lg:grid-cols-2 gap-8 md:gap-12">
                    {{-- Admin/Pengurus Flow --}}
                    @if (isset($processFlowContent->content['admin_steps']))
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl shadow-lg animate-fade-in-up"
                            style="animation-delay: 0.4s">
                            <h3
                                class="text-xl md:text-2xl font-semibold font-poppins text-primary-600 dark:text-accent-400 mb-6 text-center">
                                Untuk Pengurus/Admin
                            </h3>
                            <ol class="relative border-l border-gray-300 dark:border-gray-600 space-y-6">
                                @foreach ($processFlowContent->content['admin_steps'] as $index => $step)
                                    <li class="ml-6">
                                        <span
                                            class="absolute flex items-center justify-center w-8 h-8 bg-primary-100 rounded-full -left-4 ring-4 ring-white dark:ring-gray-700 dark:bg-primary-900">
                                            @svg($step['icon'], 'w-4 h-4 text-primary-600 dark:text-primary-300')
                                        </span>
                                        <h4
                                            class="flex items-center mb-1 text-md font-semibold text-gray-900 dark:text-white">
                                            {{ $step['title'] }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $step['description'] }}
                                        </p>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif

                    {{-- Warga Flow --}}
                    @if (isset($processFlowContent->content['resident_steps']))
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl shadow-lg animate-fade-in-up"
                            style="animation-delay: 0.6s">
                            <h3
                                class="text-xl md:text-2xl font-semibold font-poppins text-accent-600 dark:text-accent-400 mb-6 text-center">
                                Untuk Warga
                            </h3>
                            <ol class="relative border-l border-gray-300 dark:border-gray-600 space-y-6">
                                @foreach ($processFlowContent->content['resident_steps'] as $index => $step)
                                    <li class="ml-6">
                                        <span
                                            class="absolute flex items-center justify-center w-8 h-8 bg-accent-100 rounded-full -left-4 ring-4 ring-white dark:ring-gray-700 dark:bg-accent-900">
                                            @svg($step['icon'], 'w-4 h-4 text-accent-600 dark:text-accent-300')
                                        </span>
                                        <h4
                                            class="flex items-center mb-1 text-md font-semibold text-gray-900 dark:text-white">
                                            {{ $step['title'] }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $step['description'] }}
                                        </p>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endif
