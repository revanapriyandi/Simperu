<?php

namespace App\Imports;

use App\Models\FamilyMember;
use App\Models\Family;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FamilyMemberImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $family = Family::where('kk_number', $row['kk_number'])->first();
        if (!$family) {
            // Optionally, skip or handle missing family
            return null;
        }
        return new FamilyMember([
            'family_id' => $family->id,
            'name' => $row['name'],
            'nik' => $row['nik'],
            'relationship' => $row['relationship'],
            'birth_date' => $row['birth_date'],
            'gender' => $row['gender'],
            'occupation' => $row['occupation'] ?? null,
        ]);
    }
}
