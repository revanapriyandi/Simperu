<div class="bg-white dark:bg-gray-700 p-6 md:p-8 rounded-xl shadow-xl animate-slide-in-left">
    <h3 class="text-xl md:text-2xl font-semibold text-primary-700 dark:text-white mb-6 font-poppins">
        Kirim Pesan
    </h3>

    <form wire:submit="sendMessage">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" size="lg" class="w-full bg-accent-500 hover:bg-accent-600"
                wire:loading.attr="disabled" wire:target="sendMessage">

                <span wire:loading.remove wire:target="sendMessage">
                    Kirim Pesan
                </span>

                <span wire:loading wire:target="sendMessage" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Mengirim...
                </span>
            </x-filament::button>
        </div>
    </form>
</div>
