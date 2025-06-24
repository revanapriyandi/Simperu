<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-x-3">
                <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg">
                    <x-heroicon-m-megaphone class="h-5 w-5 text-white" />
                </div>
                <span class="text-lg font-semibold">Pengumuman Terbaru</span>
            </div>
        </x-slot>
        
        <x-slot name="description">
            Informasi terkini dari pengurus perumahan
        </x-slot>

        <div class="space-y-4">
            @forelse($announcements as $announcement)
                <div class="group p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 hover:shadow-md">
                    <div class="flex items-start justify-between gap-x-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start gap-3">
                                <!-- Priority Indicator -->
                                <div class="flex-shrink-0 mt-1">
                                    @if($announcement->priority === 'high')
                                        <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                    @elseif($announcement->priority === 'medium')
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                    @else
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                                        {{ $announcement->title }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-3 leading-relaxed">
                                        {{ strip_tags($announcement->content) }}
                                    </p>
                                    
                                    <!-- Meta Information -->
                                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <x-heroicon-m-clock class="w-3 h-3" />
                                            <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($announcement->created_at->isToday())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                <span class="w-1 h-1 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                                                Hari ini
                                            </span>
                                        @elseif($announcement->created_at->isYesterday())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                Kemarin
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="flex-shrink-0">
                            <button class="opacity-0 group-hover:opacity-100 transition-opacity p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    
                    <!-- Priority Badge -->
                    @if($announcement->priority === 'high')
                        <div class="mt-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                            <x-heroicon-m-exclamation-triangle class="w-3 h-3 mr-1" />
                            Penting & Mendesak
                        </div>
                    @elseif($announcement->priority === 'medium')
                        <div class="mt-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                            <x-heroicon-m-information-circle class="w-3 h-3 mr-1" />
                            Perhatian
                        </div>
                    @endif
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <x-heroicon-o-megaphone class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Belum ada pengumuman
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        Pengumuman baru dari pengurus perumahan akan muncul di sini. 
                        Pastikan untuk memeriksa secara berkala.
                    </p>
                </div>
            @endforelse
            
            @if($announcements->count() > 0)
                <!-- View All Link -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('filament.resident.resources.announcements.index') }}" 
                       class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors group">
                        <span>Lihat semua pengumuman</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </a>
                </div>
            @endif
        </div>

        @if($announcements->count() > 0)
            <!-- Quick Info -->
            <div class="mt-6 p-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                <div class="flex items-center gap-2 text-sm text-amber-800 dark:text-amber-200">
                    <x-heroicon-m-bell class="w-4 h-4" />
                    <span class="font-medium">
                        {{ $announcements->count() }} dari {{ $announcements->count() >= 3 ? 'banyak' : 'total' }} pengumuman terbaru
                    </span>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
