<x-filament-panels::page>
    <div class="space-y-6">
        @livewire(\App\Filament\Resident\Widgets\QuickActionsWidget::class)
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @livewire(\App\Filament\Resident\Widgets\ComplaintLetterStatsWidget::class)
            @livewire(\App\Filament\Resident\Widgets\AnnouncementsWidget::class)
        </div>
        
        <div class="w-full">
            @livewire(\App\Filament\Resident\Widgets\RecentComplaintLettersWidget::class)
        </div>
    </div>
</x-filament-panels::page>
