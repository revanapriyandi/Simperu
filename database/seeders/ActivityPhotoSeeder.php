<?php

namespace Database\Seeders;

use App\Models\ActivityPhoto;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil admin user sebagai uploader
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->warn('Admin user not found. Please run UserSeeder first.');
            return;
        }

        $activities = [
            [
                'title' => 'Kerja Bakti Bulanan Januari',
                'description' => 'Dokumentasi kegiatan kerja bakti bulanan yang diikuti oleh seluruh warga perumahan. Kegiatan meliputi pembersihan saluran air, penyapuan jalan, dan penataan taman.',
                'activity_date' => now()->subDays(30),
                'is_featured' => true,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Rapat Koordinasi RT/RW',
                'description' => 'Rapat koordinasi bulanan membahas program kerja, laporan keuangan, dan rencana pembangunan fasilitas baru untuk kenyamanan warga.',
                'activity_date' => now()->subDays(25),
                'is_featured' => false,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Pemasangan CCTV Baru',
                'description' => 'Pemasangan sistem CCTV di 5 titik strategis untuk meningkatkan keamanan perumahan. Warga antusias menyambut program peningkatan keamanan ini.',
                'activity_date' => now()->subDays(20),
                'is_featured' => true,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Program Penghijauan Blok A',
                'description' => 'Kegiatan penanaman pohon pelindung dan pembuatan taman mini di area Blok A. Diikuti oleh puluhan warga dengan antusias tinggi.',
                'activity_date' => now()->subDays(18),
                'is_featured' => false,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Senam Pagi Bersama',
                'description' => 'Kegiatan senam pagi rutin setiap hari Minggu yang diikuti oleh warga dari berbagai usia. Aktivitas ini bertujuan meningkatkan kesehatan dan kebersamaan.',
                'activity_date' => now()->subDays(15),
                'is_featured' => false,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Perbaikan Jalan Area Parkir',
                'description' => 'Dokumentasi proses perbaikan jalan di area parkir yang mengalami kerusakan. Perbaikan dilakukan secara gotong royong oleh warga dan pengurus.',
                'activity_date' => now()->subDays(12),
                'is_featured' => true,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Bakti Sosial untuk Warga Kurang Mampu',
                'description' => 'Kegiatan bakti sosial berupa pemberian sembako dan bantuan dana untuk warga yang membutuhkan. Warga sangat kompak dalam kegiatan ini.',
                'activity_date' => now()->subDays(10),
                'is_featured' => true,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Pelatihan Keamanan untuk Satpam',
                'description' => 'Pelatihan rutin untuk petugas keamanan perumahan mencakup komunikasi, penanganan situasi darurat, dan penggunaan peralatan keamanan.',
                'activity_date' => now()->subDays(8),
                'is_featured' => false,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Festival Anak-Anak Perumahan',
                'description' => 'Acara hiburan khusus untuk anak-anak dengan berbagai permainan edukatif, lomba mewarnai, dan pertunjukan badut. Anak-anak sangat gembira.',
                'activity_date' => now()->subDays(5),
                'is_featured' => true,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Pemeliharaan Taman Bersama',
                'description' => 'Kegiatan rutin pemeliharaan taman dan area hijau perumahan. Meliputi penyiraman, pemangkasan, dan penanaman bunga-bunga baru.',
                'activity_date' => now()->subDays(3),
                'is_featured' => false,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Sosialisasi Peraturan Baru',
                'description' => 'Sosialisasi peraturan baru mengenai jam berkunjung tamu dan tata tertib parkir kendaraan. Warga mendapat penjelasan lengkap dari pengurus.',
                'activity_date' => now()->subDays(2),
                'is_featured' => false,
                'uploaded_by' => $admin->id,
            ],
            [
                'title' => 'Peringatan HUT RI di Perumahan',
                'description' => 'Perayaan kemerdekaan Indonesia dengan berbagai lomba tradisional seperti balap karung, tarik tambang, dan makan kerupuk. Sangat meriah!',
                'activity_date' => now()->subDays(120),
                'is_featured' => true,
                'uploaded_by' => $admin->id,
            ],
        ];

        foreach ($activities as $activity) {
            ActivityPhoto::create($activity);
        }
    }
}
