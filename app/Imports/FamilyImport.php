<?php

namespace App\Imports;

use App\Models\Family;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FamilyImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'families' => new FamilySheetImport(),
            'members' => new FamilyMemberImport(),
        ];
    }
}

class FamilySheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return Family::updateOrCreate(
            [
                'kk_number' => $row['kk_number'],
            ],
            [
                'head_of_family' => $row['head_of_family'],
                'wife_name' => $row['wife_name'] ?? null,
                'house_block' => $row['house_block'],
                'phone_1' => $row['phone_1'] ?? null,
                'phone_2' => $row['phone_2'] ?? null,
                'house_status' => $row['house_status'] ?? 'owner',
                'status' => $row['status'] ?? 'active',
                'family_members_count' => $row['family_members_count'] ?? 1,
                'license_plate_1' => $row['license_plate_1'] ?? null,
                'license_plate_2' => $row['license_plate_2'] ?? null,
            ]
        );
    }
}

// FamilyImport.php sudah sesuai dengan template terbaru: sheet 'families' dan 'members'.
// FamilySheetImport dan FamilyMemberImport sudah sesuai dengan kolom template.
// Tidak perlu perubahan besar, hanya pastikan validasi dan relasi berjalan baik.
// Jika ingin menambah validasi atau logika khusus, tambahkan di sini.
