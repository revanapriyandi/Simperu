<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Tanda Tangan Digital - Tidak Valid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tanda Tangan Digital Tidak Valid</h1>
            <p class="text-gray-600">Dokumen tidak dapat diverifikasi atau tanda tangan digital tidak sah</p>
        </div>

        <!-- Error Information Card -->
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Error Banner -->
            <div class="bg-red-600 text-white p-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span class="font-semibold">Verifikasi Gagal</span>
                </div>
            </div>

            <!-- Error Details -->
            <div class="p-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h3 class="text-red-800 font-medium">Tanda tangan digital tidak dapat diverifikasi</h3>
                            <div class="text-red-700 text-sm mt-2 space-y-1">
                                <p>Kemungkinan penyebab:</p>
                                <ul class="list-disc list-inside ml-4 space-y-1">
                                    <li>Kode verifikasi yang digunakan tidak valid atau sudah kedaluwarsa</li>
                                    <li>Dokumen telah dimodifikasi setelah ditandatangani</li>
                                    <li>Tanda tangan digital palsu atau dipalsukan</li>
                                    <li>Sistem verifikasi mengalami gangguan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hash Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Informasi Hash</h3>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-700 text-sm">Hash yang dicoba:</p>
                        <code class="text-xs font-mono bg-gray-200 px-2 py-1 rounded mt-1 block break-all">{{ $hash }}</code>
                    </div>
                </div>

                <!-- Recommendations -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Langkah yang Disarankan</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 text-sm font-medium">1</span>
                            </div>
                            <p class="text-gray-700 text-sm">
                                <strong>Periksa sumber dokumen</strong> - Pastikan Anda mendapatkan dokumen dari sumber resmi
                            </p>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 text-sm font-medium">2</span>
                            </div>
                            <p class="text-gray-700 text-sm">
                                <strong>Hubungi pengurus</strong> - Konfirmasi keaslian dokumen dengan Pengurus Perumahan
                            </p>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 text-sm font-medium">3</span>
                            </div>
                            <p class="text-gray-700 text-sm">
                                <strong>Coba lagi nanti</strong> - Jika yakin dokumen asli, sistem mungkin sedang mengalami gangguan
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-blue-800 font-medium mb-2">Butuh Bantuan?</h4>
                    <p class="text-blue-700 text-sm mb-2">
                        Jika Anda yakin dokumen ini asli namun verifikasi gagal, silakan hubungi:
                    </p>
                    <div class="text-blue-700 text-sm">
                        <p><strong>Pengurus Perumahan Villa Windaro Permai</strong></p>
                        <p>Email: info@villawindaro.com</p>
                        <p>Telepon: (0761) 123-4567</p>
                    </div>
                </div>

                <!-- Action -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button onclick="window.history.back()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </button>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="max-w-2xl mx-auto mt-8">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-amber-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h4 class="text-amber-800 font-medium">Peringatan Keamanan</h4>
                        <p class="text-amber-700 text-sm mt-1">
                            Jangan menggunakan dokumen yang tidak dapat diverifikasi untuk keperluan resmi. 
                            Pastikan selalu mendapatkan dokumen dari sumber yang terpercaya.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>Sistem Informasi Manajemen Perumahan Villa Windaro Permai</p>
            <p class="mt-1">Verifikasi dilakukan pada {{ now()->format('d F Y H:i') }} WIB</p>
        </div>
    </div>
</body>
</html>
