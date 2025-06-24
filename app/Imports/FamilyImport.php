<?php

namespace App\Imports;

use App\Models\Family;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Import keluarga dari CSV dengan format:
 * Nomor Kartu Keluarga;Nama Kepala Keluarga;Nama Istri;Nama Anak;Blok Rumah;No HP 1;No HP 2;Status Rumah;Jumlah Anggota Keluarga;Plat Nomor 1;Plat Nomor 2
 */
class FamilyImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithCustomCsvSettings
{
    public function model(array $row): ?Family
    {
        // Normalize header keys untuk mapping yang fleksibel
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            $normalizedKey = $this->normalizeKey($key);
            $normalizedRow[$normalizedKey] = $value;
        }

        // Extract data dengan mapping yang tepat
        $kkNumber = $this->cleanValue($normalizedRow['nomor_kartu_keluarga'] ?? $normalizedRow['kk_number'] ?? null);
        $headOfFamily = $this->cleanValue($normalizedRow['nama_kepala_keluarga'] ?? $normalizedRow['head_of_family'] ?? null);
        $wifeName = $this->cleanValue($normalizedRow['nama_istri'] ?? $normalizedRow['wife_name'] ?? null);
        $childrenNames = $this->cleanValue($normalizedRow['nama_anak'] ?? $normalizedRow['children_names'] ?? null);
        $houseBlock = $this->cleanValue($normalizedRow['blok_rumah'] ?? $normalizedRow['house_block'] ?? null);
        $phone1 = $this->normalizePhone($normalizedRow['no_hp_1'] ?? $normalizedRow['phone_1'] ?? null);
        $phone2 = $this->normalizePhone($normalizedRow['no_hp_2'] ?? $normalizedRow['phone_2'] ?? null);
        $houseStatus = $this->normalizeHouseStatus($normalizedRow['status_rumah'] ?? $normalizedRow['house_status'] ?? null);
        $membersCount = (int) ($normalizedRow['jumlah_anggota_keluarga'] ?? $normalizedRow['family_members_count'] ?? 1);
        $plate1 = $this->cleanValue($normalizedRow['plat_nomor_1'] ?? $normalizedRow['license_plate_1'] ?? null);
        $plate2 = $this->cleanValue($normalizedRow['plat_nomor_2'] ?? $normalizedRow['license_plate_2'] ?? null);

        // Validasi data wajib
        if (empty($kkNumber) || empty($headOfFamily) || empty($houseBlock)) {
            return null;
        }

        // Create atau update family
        $family = Family::updateOrCreate(
            ['kk_number' => $kkNumber],
            [
                'head_of_family' => $headOfFamily,
                'wife_name' => $wifeName,
                'house_block' => $houseBlock,
                'phone_1' => $phone1,
                'phone_2' => $phone2,
                'house_status' => $houseStatus,
                'family_members_count' => $membersCount,
                'license_plate_1' => $plate1,
                'license_plate_2' => $plate2,
                'status' => 'active',
            ]
        );

        // Process anggota keluarga jika ada
        $this->processFamilyMembers($family, $headOfFamily, $wifeName, $childrenNames);

        return $family;
    }

    private function processFamilyMembers(Family $family, string $headOfFamily, ?string $wifeName, ?string $childrenNames): void
    {
        // Hapus member lama untuk re-import
        $family->members()->delete();

        // Tambah kepala keluarga
        FamilyMember::create([
            'family_id' => $family->id,
            'name' => $headOfFamily,
            'relationship' => 'kepala_keluarga',
            'gender' => 'laki-laki',
        ]);

        // Tambah istri jika ada
        if (!empty($wifeName)) {
            FamilyMember::create([
                'family_id' => $family->id,
                'name' => $wifeName,
                'relationship' => 'istri',
                'gender' => 'perempuan',
            ]);
        }

        // Tambah anak-anak jika ada
        if (!empty($childrenNames)) {
            $children = array_map('trim', explode(',', $childrenNames));
            foreach ($children as $child) {
                if (!empty($child)) {
                    FamilyMember::create([
                        'family_id' => $family->id,
                        'name' => $child,
                        'relationship' => 'anak',
                        'gender' => 'laki-laki', // Default, bisa diubah manual
                    ]);
                }
            }
        }
    }

    private function normalizeKey(string $key): string
    {
        return Str::slug(strtolower(trim($key)), '_');
    }

    private function cleanValue($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        return trim((string) $value);
    }

    private function normalizePhone($phone): ?string
    {
        if (empty($phone)) {
            return null;
        }
        
        $phone = preg_replace('/[^0-9]/', '', (string) $phone);
        
        // Tambahkan prefix 0 jika belum ada
        if (!empty($phone) && !str_starts_with($phone, '0')) {
            $phone = '0' . $phone;
        }
        
        return $phone;
    }

    private function normalizeHouseStatus(?string $status): string
    {
        if (empty($status)) {
            return 'owner';
        }

        $status = strtolower(trim($status));
        return match ($status) {
            'milik sendiri', 'owner', 'pemilik' => 'owner',
            'sewa', 'tenant', 'penyewa' => 'tenant', 
            'keluarga', 'family' => 'family',
            default => 'owner',
        };
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'enclosure' => '"',
            'escape' => '\\',
            'contiguous' => false,
            'input_encoding' => 'cp1252'
        ];
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
