<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\User;
use App\Models\FamilyMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $families = [
            [
                'kk_number' => '3201234567890001',
                'head_of_family' => 'Budi Santoso',
                'wife_name' => 'Siti Aminah',
                'house_block' => 'A-01',
                'phone_1' => '081234567801',
                'phone_2' => '021-12345001',
                'house_status' => 'owner',
                'family_members_count' => 4,
                'license_plate_1' => 'B 1234 ABC',
                'license_plate_2' => 'B 5678 DEF',
                'status' => 'active',
                'user' => [
                    'name' => 'Budi Santoso',
                    'email' => 'budi.santoso@gmail.com',
                    'nik' => '3201234567890001',
                    'password' => 'password123',
                    'phone' => '081234567801',
                    'house_number' => 'A-01',
                    'kk_number' => '3201234567890001',
                    'role' => 'resident',
                ],
                'members' => [
                    [
                        'name' => 'Budi Santoso',
                        'nik' => '3201234567890001',
                        'relationship' => 'head',
                        'birth_date' => '1985-03-15',
                        'gender' => 'male',
                        'occupation' => 'Karyawan Swasta',
                    ],
                    [
                        'name' => 'Siti Aminah',
                        'nik' => '3201234567890002',
                        'relationship' => 'wife',
                        'birth_date' => '1987-07-22',
                        'gender' => 'female',
                        'occupation' => 'Ibu Rumah Tangga',
                    ],
                    [
                        'name' => 'Ahmad Budi Pratama',
                        'nik' => '3201234567890003',
                        'relationship' => 'child',
                        'birth_date' => '2010-12-10',
                        'gender' => 'male',
                        'occupation' => 'Pelajar',
                    ],
                    [
                        'name' => 'Sari Budi Lestari',
                        'nik' => '3201234567890004',
                        'relationship' => 'child',
                        'birth_date' => '2013-05-25',
                        'gender' => 'female',
                        'occupation' => 'Pelajar',
                    ],
                ],
            ],
            [
                'kk_number' => '3201234567890005',
                'head_of_family' => 'Agus Wijaya',
                'wife_name' => 'Rina Sari',
                'house_block' => 'A-02',
                'phone_1' => '081234567802',
                'phone_2' => null,
                'house_status' => 'owner',
                'family_members_count' => 3,
                'license_plate_1' => 'B 2345 GHI',
                'license_plate_2' => null,
                'status' => 'active',
                'user' => [
                    'name' => 'Agus Wijaya',
                    'email' => 'agus.wijaya@gmail.com',
                    'nik' => '3201234567890005',
                    'password' => 'password123',
                    'phone' => '081234567802',
                    'house_number' => 'A-02',
                    'kk_number' => '3201234567890005',
                    'role' => 'resident',
                ],
                'members' => [
                    [
                        'name' => 'Agus Wijaya',
                        'nik' => '3201234567890005',
                        'relationship' => 'head',
                        'birth_date' => '1982-11-08',
                        'gender' => 'male',
                        'occupation' => 'Wiraswasta',
                    ],
                    [
                        'name' => 'Rina Sari',
                        'nik' => '3201234567890006',
                        'relationship' => 'wife',
                        'birth_date' => '1985-02-14',
                        'gender' => 'female',
                        'occupation' => 'Guru',
                    ],
                    [
                        'name' => 'Kevin Agus Wijaya',
                        'nik' => '3201234567890007',
                        'relationship' => 'child',
                        'birth_date' => '2015-09-18',
                        'gender' => 'male',
                        'occupation' => 'Pelajar',
                    ],
                ],
            ],
            [
                'kk_number' => '3201234567890008',
                'head_of_family' => 'Dedi Kurniawan',
                'wife_name' => null,
                'house_block' => 'B-01',
                'phone_1' => '081234567803',
                'phone_2' => null,
                'house_status' => 'tenant',
                'family_members_count' => 1,
                'license_plate_1' => 'B 3456 JKL',
                'license_plate_2' => null,
                'status' => 'active',
                'user' => [
                    'name' => 'Dedi Kurniawan',
                    'email' => 'dedi.kurniawan@gmail.com',
                    'nik' => '3201234567890008',
                    'password' => 'password123',
                    'phone' => '081234567803',
                    'house_number' => 'B-01',
                    'kk_number' => '3201234567890008',
                    'role' => 'resident',
                ],
                'members' => [
                    [
                        'name' => 'Dedi Kurniawan',
                        'nik' => '3201234567890008',
                        'relationship' => 'head',
                        'birth_date' => '1990-06-30',
                        'gender' => 'male',
                        'occupation' => 'Software Developer',
                    ],
                ],
            ],
            [
                'kk_number' => '3201234567890009',
                'head_of_family' => 'Hendra Saputra',
                'wife_name' => 'Maya Indira',
                'house_block' => 'B-02',
                'phone_1' => '081234567804',
                'phone_2' => '021-12345004',
                'house_status' => 'owner',
                'family_members_count' => 5,
                'license_plate_1' => 'B 4567 MNO',
                'license_plate_2' => 'B 7890 PQR',
                'status' => 'active',
                'user' => [
                    'name' => 'Hendra Saputra',
                    'email' => 'hendra.saputra@gmail.com',
                    'nik' => '3201234567890009',
                    'password' => 'password123',
                    'phone' => '081234567804',
                    'house_number' => 'B-02',
                    'kk_number' => '3201234567890009',
                    'role' => 'resident',
                ],
                'members' => [
                    [
                        'name' => 'Hendra Saputra',
                        'nik' => '3201234567890009',
                        'relationship' => 'head',
                        'birth_date' => '1980-01-25',
                        'gender' => 'male',
                        'occupation' => 'Manager',
                    ],
                    [
                        'name' => 'Maya Indira',
                        'nik' => '3201234567890010',
                        'relationship' => 'wife',
                        'birth_date' => '1983-08-12',
                        'gender' => 'female',
                        'occupation' => 'Dokter',
                    ],
                    [
                        'name' => 'Rafi Hendra Saputra',
                        'nik' => '3201234567890011',
                        'relationship' => 'child',
                        'birth_date' => '2008-04-16',
                        'gender' => 'male',
                        'occupation' => 'Pelajar',
                    ],
                    [
                        'name' => 'Dina Maya Saputra',
                        'nik' => '3201234567890012',
                        'relationship' => 'child',
                        'birth_date' => '2011-11-03',
                        'gender' => 'female',
                        'occupation' => 'Pelajar',
                    ],
                    [
                        'name' => 'Adit Hendra Saputra',
                        'nik' => '3201234567890013',
                        'relationship' => 'child',
                        'birth_date' => '2016-07-20',
                        'gender' => 'male',
                        'occupation' => 'Belum Sekolah',
                    ],
                ],
            ],
            [
                'kk_number' => '3201234567890014',
                'head_of_family' => 'Iwan Setiawan',
                'wife_name' => 'Lusi Handayani',
                'house_block' => 'C-01',
                'phone_1' => '081234567805',
                'phone_2' => null,
                'house_status' => 'family',
                'family_members_count' => 6,
                'license_plate_1' => 'B 5678 STU',
                'license_plate_2' => null,
                'status' => 'active',
                'user' => [
                    'name' => 'Iwan Setiawan',
                    'email' => 'iwan.setiawan@gmail.com',
                    'nik' => '3201234567890014',
                    'password' => 'password123',
                    'phone' => '081234567805',
                    'house_number' => 'C-01',
                    'kk_number' => '3201234567890014',
                    'role' => 'resident',
                ],
                'members' => [
                    [
                        'name' => 'Iwan Setiawan',
                        'nik' => '3201234567890014',
                        'relationship' => 'head',
                        'birth_date' => '1978-12-05',
                        'gender' => 'male',
                        'occupation' => 'Pegawai Negeri',
                    ],
                    [
                        'name' => 'Lusi Handayani',
                        'nik' => '3201234567890015',
                        'relationship' => 'wife',
                        'birth_date' => '1981-03-18',
                        'gender' => 'female',
                        'occupation' => 'Perawat',
                    ],
                    [
                        'name' => 'Eka Iwan Setiawan',
                        'nik' => '3201234567890016',
                        'relationship' => 'child',
                        'birth_date' => '2005-10-12',
                        'gender' => 'female',
                        'occupation' => 'Pelajar',
                    ],
                    [
                        'name' => 'Rio Iwan Setiawan',
                        'nik' => '3201234567890017',
                        'relationship' => 'child',
                        'birth_date' => '2009-01-28',
                        'gender' => 'male',
                        'occupation' => 'Pelajar',
                    ],
                    [
                        'name' => 'Pak Sutrisno',
                        'nik' => '3201234567890018',
                        'relationship' => 'parent',
                        'birth_date' => '1950-05-15',
                        'gender' => 'male',
                        'occupation' => 'Pensiunan',
                    ],
                    [
                        'name' => 'Bu Sutrisno',
                        'nik' => '3201234567890019',
                        'relationship' => 'parent',
                        'birth_date' => '1955-09-22',
                        'gender' => 'female',
                        'occupation' => 'Ibu Rumah Tangga',
                    ],
                ],
            ],
        ];

        foreach ($families as $familyData) {
            // Buat user terlebih dahulu
            $userData = $familyData['user'];
            $userData['password'] = Hash::make($userData['password']);
            $userData['email_verified_at'] = now();
            $userData['is_active'] = true;

            $user = User::create($userData);

            // Buat family
            $familyInfo = collect($familyData)->except(['user', 'members'])->toArray();
            $familyInfo['user_id'] = $user->id;

            $family = Family::create($familyInfo);

            // Buat family members
            foreach ($familyData['members'] as $memberData) {
                $memberData['family_id'] = $family->id;

                FamilyMember::create($memberData);
            }
        }
    }
}
