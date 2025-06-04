@if ($announcements->count() > 0)
    <section class="py-12 md:py-20 bg-blue-50 dark:bg-gray-800/70 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 md:mb-14">
                <h2
                    class="text-2xl md:text-3xl lg:text-4xl font-bold font-poppins text-blue-700 dark:text-white animate-fade-in-up">
                    Pengumuman Terbaru
                </h2>
                <p class="text-md md:text-lg text-gray-600 dark:text-gray-400 mt-3 md:mt-4 max-w-2xl mx-auto animate-fade-in-up"
                    style="animation-delay: 0.2s">
                    Informasi penting dan terkini untuk seluruh warga perumahan
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach ($announcements as $index => $announcement)
                    <div class="bg-white dark:bg-gray-700/50 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group animate-fade-in-up overflow-hidden"
                        style="animation-delay: {{ 0.3 + $index * 0.1 }}s">
                        {{-- Header with Category Badge --}}
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-between mb-3">
                                <span
                                    class="bg-emerald-100 text-emerald-800 dark:bg-emerald-800/50 dark:text-emerald-200 text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $announcement->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <h3
                                class="text-lg font-bold font-poppins text-blue-700 dark:text-white mb-3 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">
                                {{ $announcement->title }}
                            </h3>

                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-3">
                                {{ Str::limit(strip_tags($announcement->content), 120) }}
                            </p>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 pb-6">
                            <div
                                class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex items-center space-x-2">
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                        @svg('heroicon-o-megaphone', 'w-4 h-4 text-blue-600 dark:text-blue-400')
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                        Pengumuman Resmi
                                    </span>
                                </div>

                                <button
                                    class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 text-sm font-semibold flex items-center space-x-1 group-hover:translate-x-1 transition-transform duration-200">
                                    <span>Baca Selengkapnya</span>
                                    @svg('heroicon-o-arrow-right', 'w-4 h-4')
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- View All Announcements Button --}}
            <div class="text-center mt-10">
                <a href="/admin"
                    class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                    @svg('heroicon-o-newspaper', 'w-5 h-5 mr-2')
                    Lihat Semua Pengumuman
                </a>
            </div>
        </div>
    </section>
@endif
