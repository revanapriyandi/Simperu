<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ComplaintStatus: string implements HasLabel, HasColor
{
    case SUBMITTED = 'submitted';
    case IN_REVIEW = 'in_review';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    /**
     * Get all status values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get status label for display
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::SUBMITTED => 'Diajukan',
            self::IN_REVIEW => 'Sedang Ditinjau',
            self::IN_PROGRESS => 'Sedang Diproses',
            self::RESOLVED => 'Selesai',
            self::CLOSED => 'Ditutup',
        };
    }

    /**
     * Get badge color for UI
     */
    public function getColor(): string
    {
        return match ($this) {
            self::SUBMITTED => 'warning',
            self::IN_REVIEW => 'info',
            self::IN_PROGRESS => 'primary',
            self::RESOLVED => 'success',
            self::CLOSED => 'secondary',
        };
    }
}
