<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum HouseStatus: string implements HasLabel
{
    case OWNER = 'owner';
    case TENANT = 'tenant';
    case FAMILY = 'family';

    public function getLabel(): string
    {
        return match ($this) {
            self::OWNER => 'Pemilik',
            self::TENANT => 'Penyewa',
            self::FAMILY => 'Keluarga/Anak',
        };
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->getLabel();
        }
        return $options;
    }
}
