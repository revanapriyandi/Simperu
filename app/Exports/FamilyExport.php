<?php

namespace App\Exports;

use App\Models\Family;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FamilyExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Family::select([
            'kk_number',
            'head_of_family',
            'wife_name',
            'house_block',
            'phone_1',
            'phone_2',
            'house_status',
            'status',
            'family_members_count',
            'license_plate_1',
            'license_plate_2',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'kk_number',
            'head_of_family',
            'wife_name',
            'house_block',
            'phone_1',
            'phone_2',
            'house_status',
            'status',
            'family_members_count',
            'license_plate_1',
            'license_plate_2',
        ];
    }
}
