<?php

namespace App\Enums;

enum ComplaintPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    /**
     * Get all priority values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get priority label for display
     */
    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Rendah',
            self::MEDIUM => 'Sedang',
            self::HIGH => 'Tinggi',
            self::URGENT => 'Sangat Penting',
        };
    }

    /**
     * Get priority options for forms
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $priority) {
            $options[$priority->value] = $priority->label();
        }
        return $options;
    }

    /**
     * Get badge color for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::LOW => 'secondary',
            self::MEDIUM => 'warning',
            self::HIGH => 'danger',
            self::URGENT => 'success',
        };
    }
}
