<footer class="bg-primary-800 dark:bg-gray-900 text-primary-100 dark:text-gray-400">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Company Info --}}
            <div>
                <a href="#" class="text-2xl font-bold font-poppins text-white">{{ $settings['site_name'] }}</a>
                <p class="mt-2 text-sm">
                    {{ $settings['site_tagline'] }}
                </p>
                <div class="flex space-x-4 mt-4">
                    <a href="{{ $settings['social_facebook'] }}"
                        class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="{{ $settings['social_instagram'] }}"
                        class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 rounded-full flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.013-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.498 2.52c.636-.247 1.363-.416 2.427-.465C8.966 2.013 9.32 2 12.315 2zm0 1.623c-2.387 0-2.704.01-3.667.057-1.005.046-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.852-.344 1.857-.047.963-.057 1.28-.057 3.667s.01 2.704.057 3.667c.046 1.005.207 1.504.344 1.857.182.466.398.8.748 1.15.35.35.683.566 1.15.748.353.137.852.3 1.857.344.963.047 1.28.057 3.667.057s2.704-.01 3.667-.057c1.005-.046 1.504-.207 1.857-.344.467-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.852.344-1.857.047-.963.057-1.28.057-3.667s-.01-2.704-.057-3.667c-.046-1.005-.207-1.504-.344-1.857-.182-.467-.398-.8-.748-1.15-.35-.35-.683-.566-1.15-.748-.353-.137-.852-.3-1.857-.344C15.019 3.633 14.702 3.623 12.315 3.623zm0 1.622a6.735 6.735 0 100 13.47 6.735 6.735 0 000-13.47zm0 11.822a5.087 5.087 0 110-10.175 5.087 5.087 0 010 10.175zm6.188-8.854a1.227 1.227 0 100-2.453 1.227 1.227 0 000 2.453z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="{{ $settings['whatsapp'] }}"
                        class="w-10 h-10 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center transition-colors">
                        @svg('heroicon-o-phone', 'w-5 h-5')
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="#beranda" class="text-gray-400 hover:text-accent-400 transition-colors">Beranda</a>
                    </li>
                    <li><a href="#tentang" class="text-gray-400 hover:text-accent-400 transition-colors">Tentang</a>
                    </li>
                    <li><a href="#manfaat" class="text-gray-400 hover:text-accent-400 transition-colors">Manfaat</a>
                    </li>
                    <li><a href="#alur" class="text-gray-400 hover:text-accent-400 transition-colors">Alur Sistem</a>
                    </li>
                    <li><a href="#layanan" class="text-gray-400 hover:text-accent-400 transition-colors">Layanan</a>
                    </li>
                    <li><a href="#kontak" class="text-gray-400 hover:text-accent-400 transition-colors">Kontak</a></li>
                    <li><a href="/admin" class="text-gray-400 hover:text-accent-400 transition-colors">Masuk</a></li>
                </ul>
            </div>

            {{-- Contact Info --}}
            <div>
                <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                <div class="space-y-2 text-gray-400">
                    <p class="flex items-center">
                        @svg('heroicon-o-map-pin', 'w-4 h-4 mr-2')
                        {{ $settings['address'] }}
                    </p>
                    <p class="flex items-center">
                        @svg('heroicon-o-phone', 'w-4 h-4 mr-2')
                        {{ $settings['phone'] }}
                    </p>
                    <p class="flex items-center">
                        @svg('heroicon-o-envelope', 'w-4 h-4 mr-2')
                        {{ $settings['email'] }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-primary-700 dark:border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} {{ $settings['site_name'] }}. Semua hak cipta dilindungi.</p>
            <p class="mt-2 text-sm">Dibuat dengan ❤️ untuk kemudahan pengelolaan perumahan</p>
        </div>
    </div>
</footer>
