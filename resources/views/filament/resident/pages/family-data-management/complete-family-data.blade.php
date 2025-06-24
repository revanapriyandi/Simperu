<x-filament-panels::page>
    @push('styles')
        <link href="{{ asset('css/resident-dashboard.css') }}" rel="stylesheet">
        <style>
            .family-completion-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 12px;
                padding: 2rem;
                margin-bottom: 2rem;
                color: white;
            }

            .completion-progress {
                background: rgba(255, 255, 255, 0.2);
                border-radius: 8px;
                padding: 1rem;
                margin-top: 1rem;
            }

            .progress-bar {
                background: rgba(255, 255, 255, 0.3);
                height: 8px;
                border-radius: 4px;
                overflow: hidden;
            }

            .progress-fill {
                background: #10b981;
                height: 100%;
                border-radius: 4px;
                transition: width 0.3s ease;
            }

            .wizard-container {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .form-section-enhanced {
                padding: 1.5rem;
                border-bottom: 1px solid #e5e7eb;
            }

            .form-section-enhanced:last-child {
                border-bottom: none;
            }

            .section-icon {
                width: 3rem;
                height: 3rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 1rem;
            }

            .benefits-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
                margin-top: 1.5rem;
            }

            .benefit-card {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                padding: 1rem;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .benefit-card h4 {
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: white;
            }

            .benefit-card p {
                font-size: 0.875rem;
                color: rgba(255, 255, 255, 0.8);
                margin: 0;
            }
        </style>
    @endpush

    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->

        <!-- Form Container -->
        <div class="wizard-container">
            {{ $this->form }}
        </div>

        <!-- Tips Section -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <div class="flex items-start gap-3">
                <x-heroicon-o-light-bulb class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-1 flex-shrink-0" />
                <div>
                    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Tips Pengisian Data:</h3>
                    <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                        <li>• Pastikan data yang diisi sesuai dengan dokumen resmi (KTP, KK)</li>
                        <li>• Nomor telepon yang aktif memudahkan komunikasi dengan pengurus</li>
                        <li>• Data kendaraan membantu keamanan dalam pencatatan keluar masuk</li>
                        <li>• Kontak darurat penting untuk situasi emergency</li>
                        <li>• Data dapat diubah sewaktu-waktu melalui halaman profil keluarga</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Support Contact -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Butuh bantuan? Hubungi pengurus perumahan melalui
                <a href="tel:081234567890" class="text-blue-600 hover:text-blue-700 font-medium">081-234-567-890</a>
                atau email
                <a href="mailto:admin@villawindaro.com"
                    class="text-blue-600 hover:text-blue-700 font-medium">admin@villawindaro.com</a>
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-save form data to localStorage on input change
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                if (form) {
                    const inputs = form.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        input.addEventListener('change', function() {
                            const formData = new FormData(form);
                            const data = Object.fromEntries(formData);
                            localStorage.setItem('family_form_draft', JSON.stringify(data));
                        });
                    });

                    // Load draft data if available
                    const draftData = localStorage.getItem('family_form_draft');
                    if (draftData) {
                        console.log('Draft data available for recovery');
                    }
                }
            });
        </script>
    @endpush
</x-filament-panels::page>
