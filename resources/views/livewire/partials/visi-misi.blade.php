@php
    $visiMisiPath = public_path('assets/visi_misi.json');
    $visiMisi = [];
    if (file_exists($visiMisiPath)) {
        $json = file_get_contents($visiMisiPath);
        $data = json_decode($json, true);
        $visiMisi = $data['visi_misi'] ?? [];
    }
@endphp

<section id="visi-misi"
    class="py-12 md:py-20 bg-gray-50 dark:bg-gray-900 transition-colors duration-300 relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10 md:mb-14">
            <h2
                class="text-2xl md:text-3xl lg:text-4xl font-bold font-poppins text-primary-700 dark:text-white animate-fade-in-up">
                Visi & Misi
            </h2>
        </div>
        @if (!empty($visiMisi))
            <div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up" style="animation-delay: 0.2s">
                @foreach ($visiMisi as $item)
                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">{{ $item }}</p>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 dark:text-gray-400">Visi & Misi belum tersedia.</p>
        @endif
    </div>
</section>
