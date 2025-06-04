<section id="statistik"
    class="py-8 sm:py-12 md:py-16 lg:py-20 bg-white dark:bg-gray-800 transition-colors duration-300 relative overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0"
            style="background-image: url('data:image/svg+xml,<svg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'></svg>');">
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-10 lg:mb-12">
            <h2
                class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold font-poppins text-gray-900 dark:text-white mb-3 sm:mb-4">
                Statistik Perumahan
            </h2>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto px-4">
                Data real-time sistem manajemen perumahan
            </p>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 animate-fade-in-up">
            @php
                $statItems = [
                    [
                        'value' => $statistics['total_families'] ?? 0,
                        'label' => 'Total Keluarga',
                        'icon' => 'heroicon-o-users',
                        'color' => 'blue',
                    ],
                    [
                        'value' => $statistics['total_members'] ?? 0,
                        'label' => 'Total Warga',
                        'icon' => 'heroicon-o-user-group',
                        'color' => 'emerald',
                    ],
                    [
                        'value' => $statistics['active_houses'] ?? 0,
                        'label' => 'Blok Rumah',
                        'icon' => 'heroicon-o-home',
                        'color' => 'purple',
                    ],
                    [
                        'value' => $statistics['total_announcements'] ?? 0,
                        'label' => 'Pengumuman',
                        'icon' => 'heroicon-o-megaphone',
                        'color' => 'orange',
                    ],
                ];
            @endphp

            @foreach ($statItems as $index => $stat)
                <div class="text-center p-4 sm:p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 sm:hover:-translate-y-2 group"
                    style="animation-delay: {{ $index * 0.1 }}s">

                    <!-- Icon Container -->
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-3 sm:mb-4 bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        @svg($stat['icon'], "w-5 h-5 sm:w-6 sm:h-6 text-{$stat['color']}-600 dark:text-{$stat['color']}-400")
                    </div>

                    <!-- Counter Number -->
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 mb-2 font-poppins leading-tight"
                        x-data="{ count: 0 }" x-init="$nextTick(() => {
                            let target = {{ $stat['value'] }};
                            let increment = target / 100;
                            let timer = setInterval(() => {
                                count += increment;
                                if (count >= target) {
                                    count = target;
                                    clearInterval(timer);
                                }
                            }, 20);
                        })" x-text="Math.floor(count)">
                        0
                    </div>

                    <!-- Label -->
                    <div
                        class="text-xs sm:text-sm lg:text-base text-gray-600 dark:text-gray-400 font-medium leading-tight px-2">
                        {{ $stat['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
