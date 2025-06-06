<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hubungkan Telegram - SIMPERU</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-gray-50">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-blue-100">
                    <svg class="h-12 w-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 0C5.374 0 0 5.373 0 12s5.374 12 12 12 12-5.373 12-12S18.626 0 12 0zm5.568 8.16c-.169 1.858-.896 6.728-.896 6.728-.405 2.718-1.08 3.189-1.756 3.189-.896 0-1.417-.405-1.621-1.08-.135-.405-.169-.744-.169-1.08 0-.405.034-.744.169-1.148.203-.676.676-1.621 1.621-1.621.676 0 1.351.473 1.756 3.189 0 0 .727-4.87.896-6.728.034-.405.034-.744 0-1.08-.034-.405-.135-.744-.305-1.013-.169-.27-.405-.473-.676-.609-.27-.135-.608-.203-.878-.203-.27 0-.608.068-.878.203-.27.135-.507.338-.676.609-.169.27-.27.608-.305 1.013-.034.336-.034.675 0 1.08z" />
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Hubungkan Telegram
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Anda perlu menghubungkan akun Telegram untuk menerima notifikasi penting
                </p>
            </div>

            <div class="mt-8 space-y-6">
                <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600 font-semibold text-sm">1</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Klik tombol "Buka Telegram" di bawah</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600 font-semibold text-sm">2</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Kirim pesan "/start" ke bot</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600 font-semibold text-sm">3</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Kembali ke halaman ini setelah konfirmasi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <a href="{{ $telegramUrl }}" target="_blank"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 0C5.374 0 0 5.373 0 12s5.374 12 12 12 12-5.373 12-12S18.626 0 12 0zm5.568 8.16c-.169 1.858-.896 6.728-.896 6.728-.405 2.718-1.08 3.189-1.756 3.189-.896 0-1.417-.405-1.621-1.08-.135-.405-.169-.744-.169-1.08 0-.405.034-.744.169-1.148.203-.676.676-1.621 1.621-1.621.676 0 1.351.473 1.756 3.189 0 0 .727-4.87.896-6.728.034-.405.034-.744 0-1.08-.034-.405-.135-.744-.305-1.013-.169-.27-.405-.473-.676-.609-.27-.135-.608-.203-.878-.203-.27 0-.608.068-.878.203-.27.135-.507.338-.676.609-.169.27-.27.608-.305 1.013-.034.336-.034.675 0 1.08z" />
                        </svg>
                        Buka Telegram
                    </a>

                    <button id="checkStatus" onclick="checkTelegramStatus()"
                        class="w-full flex justify-center py-3 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Periksa Status Koneksi
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-xs text-gray-500">
                        Nama: {{ $user->name }}<br>
                        Email: {{ $user->email }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkTelegramStatus() {
            const button = document.getElementById('checkStatus');
            const originalText = button.innerHTML;

            button.innerHTML =
                '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memeriksa...';
            button.disabled = true;

            fetch('/telegram/status')
                .then(response => response.json())
                .then(data => {
                    if (data.linked) {
                        alert('✅ Telegram berhasil terhubung! Anda akan diarahkan ke dashboard.');
                        window.location.href = '/resident';
                    } else {
                        alert('❌ Telegram belum terhubung. Silakan ikuti langkah-langkah di atas.');

                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Terjadi kesalahan saat memeriksa status.');
                })
                .finally(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        // Auto check status every 10 seconds
        setInterval(checkTelegramStatus, 10000);
    </script>
</body>

</html>
