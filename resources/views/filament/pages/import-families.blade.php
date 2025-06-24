<x-filament-panels::page>
    <div class="space-y-4">
        <div>
            <a href="{{ route('families.template') }}" class="text-sm text-primary-600 hover:underline">
                Unduh Template Import
            </a>
        </div>
        <x-filament-panels::form wire:submit="import">
            {{ $this->form }}
            <x-filament::button type="submit" class="mt-4">
                Import
            </x-filament::button>
        </x-filament-panels::form>
    </div>
</x-filament-panels::page>
