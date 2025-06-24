<x-filament-panels::page class="fi-dashboard-page">
    <div class="space-y-6">
        <!-- Header Dashboard -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-2">
                            Selamat Datang, {{ auth()->user()->name }}!
                        </h1>
                        <p class="text-blue-100">
                            Dashboard Administratif Simperu - {{ now()->format('d F Y') }}
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-16 h-16 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widgets Dashboard -->
        @if (method_exists($this, 'filtersForm'))
            {{ $this->filtersForm }}
        @endif

        <x-filament-widgets::widgets :columns="$this->getColumns()" :data="[
            ...property_exists($this, 'filters') ? ['filters' => $this->filters] : [],
            ...$this->getWidgetData(),
        ]" :widgets="$this->getVisibleWidgets()" />

        <!-- Footer Info -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center space-x-4">
                    <span>Sistem Informasi Perumahan</span>
                    <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                    <span>Simperu v1.0</span>
                </div>
                <div>
                    <span>Last Update: {{ now()->format('H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
