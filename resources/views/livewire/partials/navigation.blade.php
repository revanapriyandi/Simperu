<nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md shadow-md fixed w-full z-50 top-0 transition-all duration-300"
    x-data="{ scrolled: false }" @scroll.window="scrolled = (window.scrollY > 50)">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="#beranda" class="text-2xl font-bold font-poppins text-primary-700 dark:text-white">
                    {{ $settings['site_name'] }}
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-4">
                <a href="#beranda"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400 transition-colors">Beranda</a>
                <a href="#tentang"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400 transition-colors">Tentang
                    SIMPERU</a>
                <a href="#manfaat"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400 transition-colors">Manfaat</a>
                <a href="#alur"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400 transition-colors">Alur
                    Sistem</a>
                <a href="#layanan"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400 transition-colors">Layanan
                    Unggulan</a>
                <a href="#kontak"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400 transition-colors">Kontak</a>
                <a href="/admin"
                    class="ml-2 px-4 py-2 rounded-md text-sm font-medium text-primary-700 dark:text-gray-200 bg-primary-100 dark:bg-gray-700 hover:bg-primary-200 dark:hover:bg-gray-600 transition-colors">Masuk</a>
                <a href="/admin/register"
                    class="px-4 py-2 rounded-md text-sm font-medium text-white bg-accent-500 hover:bg-accent-600 transition-colors">Daftar</a>
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    title="Toggle Dark Mode"
                    class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-yellow-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-6.364-.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M12 12a2.25 2.25 0 0 0-2.25 2.25c0 1.242.93 2.16 2.064 2.244a2.25 2.25 0 0 0 2.436-2.244c0-.053 0-.105-.002-.157a2.25 2.25 0 0 0-2.248-2.093Z" />
                    </svg>
                </button>
            </div>
            <div class="md:hidden flex items-center">
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    title="Toggle Dark Mode" class="p-2 mr-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-yellow-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-6.364-.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M12 12a2.25 2.25 0 0 0-2.25 2.25c0 1.242.93 2.16 2.064 2.244a2.25 2.25 0 0 0 2.436-2.244c0-.053 0-.105-.002-.157a2.25 2.25 0 0 0-2.248-2.093Z" />
                    </svg>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Buka menu utama</span>
                    <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div x-cloak x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
        class="md:hidden absolute top-16 inset-x-0 p-2 transition transform origin-top-right" id="mobile-menu"
        x-transition:enter="duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="duration-100 ease-in"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div
            class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white dark:bg-gray-800 divide-y divide-gray-50 dark:divide-gray-700">
            <div class="pt-5 pb-6 px-5 space-y-1">
                <a href="#beranda" @click="mobileMenuOpen = false"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400">Beranda</a>
                <a href="#tentang" @click="mobileMenuOpen = false"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400">Tentang
                    SIMPERU</a>
                <a href="#manfaat" @click="mobileMenuOpen = false"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400">Manfaat</a>
                <a href="#alur" @click="mobileMenuOpen = false"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400">Alur
                    Sistem</a>
                <a href="#layanan" @click="mobileMenuOpen = false"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400">Layanan
                    Unggulan</a>
                <a href="#kontak" @click="mobileMenuOpen = false"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-accent-400">Kontak</a>
            </div>
            <div class="py-6 px-5 space-y-4">
                <a href="/admin/register"
                    class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-accent-500 hover:bg-accent-600">Daftar</a>
                <p class="mt-6 text-center text-base font-medium text-gray-500 dark:text-gray-400">
                    Sudah punya akun?
                    <a href="/admin"
                        class="text-primary-600 dark:text-accent-400 hover:text-primary-500 dark:hover:text-accent-300">
                        Masuk
                    </a>
                </p>
            </div>
        </div>
    </div>
</nav>
