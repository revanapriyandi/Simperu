<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FamilyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data from CSV
        $familiesData = [
            [
                'kk_number' => '1406021611070085',
                'head_name' => 'Hardy Adrian',
                'spouse_name' => 'Fitriana',
                'children_names' => "Nur'asnah",
                'block' => 'A2',
                'phone1' => '081364997082',
                'phone2' => '082288926427',
                'house_status' => 'Milik Sendiri',
                'member_count' => 1,
                'vehicle1' => 'BM 2212 AD',
                'vehicle2' => 'BM 3729 UB',
            ],
            [
                'kk_number' => '1406022502130001',
                'head_name' => 'Cosmos Max Novet',
                'spouse_name' => 'Kartika',
                'children_names' => 'Angellica, Willy, Ryu',
                'block' => 'A6',
                'phone1' => '081227115444',
                'phone2' => '085312874442',
                'house_status' => 'Milik Sendiri',
                'member_count' => 5,
                'vehicle1' => 'BM 1667 AN',
                'vehicle2' => 'B 3463 ERE',
            ],
            [
                'kk_number' => '1406021706090004',
                'head_name' => 'Bertoni Siahaan',
                'spouse_name' => 'Ingriani Siregar',
                'children_names' => 'Diaz Kevin Gevarel, Grietha Jeann Felicya',
                'block' => 'B7',
                'phone1' => '081376499099',
                'phone2' => '081372790099',
                'house_status' => 'Milik Sendiri',
                'member_count' => 5,
                'vehicle1' => 'BM 1307 QA',
                'vehicle2' => 'BM 2312 QG',
            ],
        ];

        foreach ($familiesData as $familyData) {
            // Create or find user for family head
            $user = User::updateOrCreate(
                ['email' => Str::slug($familyData['head_name']) . '@villawindaro.local'],
                [
                    'name' => $familyData['head_name'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]
            );

            // Create family
            $family = Family::updateOrCreate(
                ['kk_number' => $familyData['kk_number']],
                [
                    'user_id' => $user->id,
                    'head_of_family' => $familyData['head_name'], // Fixed column name
                    'wife_name' => $familyData['spouse_name'], // Fixed column name
                    'house_block' => $familyData['block'], // Fixed column name
                    'phone_1' => $familyData['phone1'], // Fixed column name
                    'phone_2' => $familyData['phone2'], // Fixed column name
                    'house_status' => $this->mapHouseStatus($familyData['house_status']), // Map to enum
                    'family_members_count' => $familyData['member_count'], // Fixed column name
                    'license_plate_1' => $familyData['vehicle1'], // Fixed column name
                    'license_plate_2' => $familyData['vehicle2'], // Fixed column name
                    'status' => 'active',
                ]
            );

            // Create family head as family member
            FamilyMember::updateOrCreate(
                [
                    'family_id' => $family->id,
                    'name' => $familyData['head_name']
                ],
                [
                    'user_id' => $user->id,
                    'nik' => $this->generateNIK(),
                    'relationship' => 'Kepala Keluarga',
                    'birth_date' => now()->subYears(rand(25, 55)),
                    'gender' => 'Laki-laki',
                    'occupation' => 'Karyawan',
                    'phone' => $familyData['phone1'],
                ]
            );

            // Create spouse if exists
            if (!empty($familyData['spouse_name']) && $familyData['spouse_name'] !== '-') {
                FamilyMember::updateOrCreate(
                    [
                        'family_id' => $family->id,
                        'name' => $familyData['spouse_name']
                    ],
                    [
                        'nik' => $this->generateNIK(),
                        'relationship' => 'Istri',
                        'birth_date' => now()->subYears(rand(25, 50)),
                        'gender' => 'Perempuan',
                        'occupation' => 'Ibu Rumah Tangga',
                        'phone' => $familyData['phone2'],
                    ]
                );
            }

            // Create children if exists
            if (!empty($familyData['children_names']) && $familyData['children_names'] !== '-') {
                $childrenNames = array_map('trim', explode(',', $familyData['children_names']));
                foreach ($childrenNames as $childName) {
                    if (!empty($childName)) {
                        FamilyMember::updateOrCreate(
                            [
                                'family_id' => $family->id,
                                'name' => $childName
                            ],
                            [
                                'nik' => $this->generateNIK(),
                                'relationship' => 'Anak',
                                'birth_date' => now()->subYears(rand(5, 20)),
                                'gender' => rand(0, 1) ? 'Laki-laki' : 'Perempuan',
                                'occupation' => 'Pelajar',
                            ]
                        );
                    }
                }
            }
        }
    }
    
    private function mapHouseStatus($status): string
    {
        return match(strtolower($status)) {
            'milik sendiri' => 'owner',
            'sewa' => 'tenant', 
            'tinggal dengan keluarga' => 'family',
            default => 'owner'
        };
    }

    private function generateNIK(): string
    {
        // Generate dummy NIK (16 digits)
        return '1406' . str_pad(rand(1, 999999999999), 12, '0', STR_PAD_LEFT);
    }
}
