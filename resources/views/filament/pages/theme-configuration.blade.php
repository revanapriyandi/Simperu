<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <x-heroicon-o-paint-brush class="w-8 h-8 text-blue-600" />
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">
                        Kustomisasi Tampilan Aplikasi
                    </h3>
                    <p class="text-blue-700 text-sm leading-relaxed">
                        Sesuaikan warna, layout, dan branding untuk menciptakan identitas visual yang unik untuk sistem informasi perumahan Anda. 
                        Perubahan akan diterapkan secara real-time di seluruh aplikasi.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Preview Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-6">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-eye class="w-5 h-5 mr-2 text-gray-600" />
                        Preview
                    </h4>
                    
                    <!-- Mini preview of the theme -->
                    <div class="space-y-4">
                        <div class="border rounded-lg p-3 bg-gray-50">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-4 h-4 rounded" style="background: var(--color-primary, #3b82f6)"></div>
                                <span class="text-xs font-medium">Primary Color</span>
                            </div>
                            <div class="flex space-x-1">
                                <div class="w-3 h-3 rounded" style="background: var(--color-primary-100, #dbeafe)"></div>
                                <div class="w-3 h-3 rounded" style="background: var(--color-primary-300, #93c5fd)"></div>
                                <div class="w-3 h-3 rounded" style="background: var(--color-primary-500, #3b82f6)"></div>
                                <div class="w-3 h-3 rounded" style="background: var(--color-primary-700, #1d4ed8)"></div>
                                <div class="w-3 h-3 rounded" style="background: var(--color-primary-900, #1e3a8a)"></div>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-3 bg-gray-50">
                            <div class="text-xs font-medium mb-2">Sample Card</div>
                            <div class="bg-white border rounded p-2 shadow-sm">
                                <div class="text-sm font-medium mb-1">Card Title</div>
                                <div class="text-xs text-gray-600">Card content with current styling</div>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Badge
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="text-xs text-gray-500 space-y-1">
                            <div>🎨 Real-time preview</div>
                            <div>💾 Auto-save changes</div>
                            <div>🔄 Reset to defaults</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration Form -->
            <div class="lg:col-span-2">
                <form wire:submit="save">
                    {{ $this->form }}
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        {{ $this->getFormActions() }}
                    </div>
                </form>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
            <h4 class="font-semibold text-amber-900 mb-3 flex items-center">
                <x-heroicon-o-light-bulb class="w-5 h-5 mr-2" />
                Tips Kustomisasi
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-amber-800">
                <div>
                    <strong>Warna Primer:</strong> Gunakan warna yang mencerminkan identitas perumahan Anda
                </div>
                <div>
                    <strong>Kontras:</strong> Pastikan teks mudah dibaca dengan background yang dipilih
                </div>
                <div>
                    <strong>Konsistensi:</strong> Gunakan palet warna yang harmonis di seluruh aplikasi
                </div>
                <div>
                    <strong>Testing:</strong> Uji tampilan di berbagai ukuran layar setelah perubahan
                </div>
            </div>
        </div>
    </div>

    <!-- Live CSS Variables Update Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update CSS variables in real-time
            function updateCSSVariable(property, value) {
                document.documentElement.style.setProperty('--color-' + property.replace('_color', ''), value);
            }

            // Listen for color picker changes
            document.addEventListener('input', function(e) {
                if (e.target.type === 'color') {
                    const property = e.target.name?.replace('data.', '');
                    if (property && property.includes('color')) {
                        updateCSSVariable(property, e.target.value);
                    }
                }
            });
        });
    </script>
</x-filament-panels::page>
