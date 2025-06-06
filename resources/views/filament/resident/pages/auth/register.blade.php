<x-filament-panels::page.simple>
    <div
        class="fi-simple-main-ctn mx-auto w-full bg-white px-6 py-12 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sm:max-w-lg sm:rounded-xl sm:px-12">
        <div class="fi-simple-header mb-12 text-center">
            <div class="fi-simple-header-heading text-center">
                <h1
                    class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                    Daftar Akun Warga
                </h1>
                <p class="fi-simple-header-subheading mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Silakan lengkapi data diri Anda untuk membuat akun warga
                </p>
            </div>
        </div>

        <x-filament-panels::form id="form" wire:submit="register">
            {{ $this->form }}

            <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
        </x-filament-panels::form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Sudah punya akun?
                <a href="{{ filament()->getLoginUrl() }}" class="text-primary-600 hover:text-primary-500 font-medium">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</x-filament-panels::page.simple>
