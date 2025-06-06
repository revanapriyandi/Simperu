<x-filament-panels::page.simple>
    <!-- Progress Overview -->
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="register" class="space-y-6">
        {{ $this->form }}

        <div class="pt-8 border-t border-gray-100">
            <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" class="!justify-center" />
        </div>
    </x-filament-panels::form>

    @if (filament()->hasLogin())
        <div class="mt-8 text-center border-t border-gray-100 pt-6">
            <div class="inline-flex items-center space-x-2">
                <span class="text-sm text-gray-600">Sudah punya akun?</span>
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 rounded-lg px-4 py-2">
                    {{ $this->loginAction }}
                </div>
            </div>
        </div>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

</x-filament-panels::page.simple>
