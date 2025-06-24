<?php

namespace Database\Seeders;

use App\Models\LetterCategory;
use Illuminate\Database\Seeder;

class LetterCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'code' => 'LNG',
                'name' => 'Surat Lingkungan (Sampah & Keamanan)',
                'description' => 'Surat pengaduan terkait masalah lingkungan, sampah, dan keamanan perumahan',
                'template' => 'Dengan hormat, bersama ini kami mengajukan pengaduan terkait masalah lingkungan...',
                'is_active' => true,
            ],
            [
                'code' => 'FST',
                'name' => 'Surat Fasilitas',
                'description' => 'Surat pengaduan terkait fasilitas umum perumahan',
                'template' => 'Dengan hormat, bersama ini kami mengajukan pengaduan terkait fasilitas...',
                'is_active' => true,
            ],
            [
                'code' => 'KLH',
                'name' => 'Surat Keterangan Kelahiran',
                'description' => 'Surat keterangan untuk keperluan kelahiran',
                'template' => 'Dengan hormat, bersama ini kami mengajukan permohonan surat keterangan kelahiran...',
                'is_active' => true,
            ],
            [
                'code' => 'KMT',
                'name' => 'Surat Keterangan Kematian',
                'description' => 'Surat keterangan untuk keperluan kematian',
                'template' => 'Dengan hormat, bersama ini kami mengajukan permohonan surat keterangan kematian...',
                'is_active' => true,
            ],
            [
                'code' => 'IZA',
                'name' => 'Surat Izin Acara',
                'description' => 'Surat permohonan izin untuk mengadakan acara di lingkungan perumahan',
                'template' => 'Dengan hormat, bersama ini kami mengajukan permohonan izin acara...',
                'is_active' => true,
            ],
            [
                'code' => 'PMT',
                'name' => 'Surat Peminjaman Tempat',
                'description' => 'Surat permohonan peminjaman tempat/fasilitas umum perumahan',
                'template' => 'Dengan hormat, bersama ini kami mengajukan permohonan peminjaman tempat...',
                'is_active' => true,
            ],
            [
                'code' => 'UMM',
                'name' => 'Surat Umum',
                'description' => 'Surat untuk keperluan umum lainnya',
                'template' => 'Dengan hormat, bersama ini kami mengajukan...',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            LetterCategory::updateOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}
