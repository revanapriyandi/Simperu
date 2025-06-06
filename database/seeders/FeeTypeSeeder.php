<?php

namespace Database\Seeders;

use App\Models\FeeType;
use Illuminate\Database\Seeder;

class FeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeTypes = [
            [
                'name' => 'Iuran Bulanan',
                'code' => 'IB',
                'amount' => 75000.00,
                'description' => 'Iuran bulanan untuk pemeliharaan fasilitas umum perumahan',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran Keamanan',
                'code' => 'IK',
                'amount' => 100000.00,
                'description' => 'Iuran untuk biaya keamanan perumahan 24 jam',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran Kebersihan',
                'code' => 'IKEB',
                'amount' => 25000.00,
                'description' => 'Iuran untuk biaya kebersihan lingkungan perumahan',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran Pemeliharaan Taman',
                'code' => 'IPT',
                'amount' => 50000.00,
                'description' => 'Iuran untuk pemeliharaan taman dan area hijau',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran Listrik Umum',
                'code' => 'ILU',
                'amount' => 30000.00,
                'description' => 'Iuran untuk listrik penerangan jalan dan fasilitas umum',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran Sosial',
                'code' => 'IS',
                'amount' => 20000.00,
                'description' => 'Iuran untuk kegiatan sosial dan kemasyarakatan',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran PDAM',
                'code' => 'IPDAM',
                'amount' => 15000.00,
                'description' => 'Iuran untuk biaya air bersih PDAM',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran Parkir',
                'code' => 'IPK',
                'amount' => 40000.00,
                'description' => 'Iuran untuk pemeliharaan area parkir',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran Sampah',
                'code' => 'ISA',
                'amount' => 35000.00,
                'description' => 'Iuran untuk pengelolaan sampah perumahan',
                'is_active' => true,
            ],
            [
                'name' => 'Iuran Perbaikan Infrastruktur',
                'code' => 'IPI',
                'amount' => 150000.00,
                'description' => 'Iuran khusus untuk perbaikan infrastruktur perumahan',
                'is_active' => false,
            ],
        ];

        foreach ($feeTypes as $feeType) {
            FeeType::create($feeType);
        }
    }
}
