<section id="kontak" class="py-12 md:py-20 bg-primary-50 dark:bg-gray-800/70 transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 md:mb-14">
            <h2
                class="text-2xl md:text-3xl lg:text-4xl font-bold font-poppins text-primary-700 dark:text-white animate-fade-in-up">
                Hubungi Kami
            </h2>
            <p class="text-md md:text-lg text-gray-600 dark:text-gray-400 mt-3 md:mt-4 max-w-2xl mx-auto animate-fade-in-up"
                style="animation-delay: 0.2s">
                Punya pertanyaan atau masukan? Jangan ragu untuk menghubungi tim SIMPERU atau kunjungi lokasi kami.
            </p>
        </div>

        <div class="max-w-6xl mx-auto lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-12 items-start">
            {{-- Contact Information --}}
            <div class="space-y-8 md:space-y-12">
                {{-- Contact Form --}}
                @livewire('contact-form')

            </div>
            {{-- Contact Info --}}
            <div class="animate-fade-in-up p-6 md:p-0" style="animation-delay: 0.4s">
                <h3 class="text-xl md:text-2xl font-semibold text-primary-700 dark:text-white mb-6 font-poppins">
                    Info Kontak
                </h3>
                <div class="space-y-6 mb-4 text-gray-600 dark:text-gray-300">
                    <div class="flex items-start">
                        @svg('heroicon-o-map-pin', 'w-6 h-6 text-accent-500 mr-3 flex-shrink-0')
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Alamat</h4>
                            <p>{{ $settings['address'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        @svg('heroicon-o-envelope', 'w-6 h-6 text-accent-500 mr-3 flex-shrink-0')
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Email</h4>
                            <a href="mailto:{{ $settings['email'] }}"
                                class="hover:text-accent-500">{{ $settings['email'] }}</a>
                        </div>
                    </div>
                    <div class="flex items-start">
                        @svg('heroicon-o-phone', 'w-6 h-6 text-accent-500 mr-3 flex-shrink-0')
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-200">WhatsApp</h4>
                            <a href="{{ $settings['whatsapp'] }}" target="_blank"
                                class="hover:text-accent-500">{{ $settings['phone'] }}</a>
                        </div>
                    </div>
                </div>
                <div class="aspect-w-16 aspect-h-9 rounded-xl shadow-xl overflow-hidden">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.5634780905!2d107.60186300302735!3d-6.902473055613459!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b9!2sBandung%2C%20Kota%20Bandung%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
                        width="100%" height="100%" style="border: 0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi SIMPERU" class="rounded-xl">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
