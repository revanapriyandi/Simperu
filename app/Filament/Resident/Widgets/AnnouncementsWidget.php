<?php

namespace App\Filament\Resident\Widgets;

use App\Models\Announcement;
use Filament\Widgets\Widget;

class AnnouncementsWidget extends Widget
{
    protected static string $view = 'filament.resident.widgets.announcements';
    
    protected int | string | array $columnSpan = 1;

    public function getViewData(): array
    {
        return [
            'announcements' => Announcement::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
        ];
    }
}
