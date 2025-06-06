<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil admin user sebagai pembuat pengumuman
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->warn('Admin user not found. Please run UserSeeder first.');
            return;
        }

        $announcements = [
            [
                'title' => 'Selamat Datang di Sistem SIMPERU',
                'content' => '<p>Selamat datang di Sistem Informasi Manajemen Pengurus Perumahan (SIMPERU). Sistem ini dirancang untuk membantu pengelolaan perumahan menjadi lebih efisien dan transparan.</p><p>Fitur yang tersedia:</p><ul><li>Manajemen data warga dan keluarga</li><li>Pengajuan dan verifikasi pembayaran iuran</li><li>Sistem surat menyurat digital</li><li>Pengumuman dan informasi terkini</li><li>Laporan keuangan transparan</li></ul><p>Jika memerlukan bantuan, silakan hubungi pengurus perumahan.</p>',
                'type' => 'info',
                'is_active' => true,
                'publish_date' => now()->subDays(7),
                'expire_date' => null,
                'send_telegram' => false,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Pengumuman Iuran Bulanan Bulan ' . now()->format('F Y'),
                'content' => '<p>Kepada seluruh warga perumahan,</p><p>Dengan hormat, kami mengingatkan bahwa pembayaran iuran bulanan untuk bulan <strong>' . now()->format('F Y') . '</strong> sudah dapat dilakukan.</p><p><strong>Rincian Iuran:</strong></p><ul><li>Iuran Bulanan: Rp 75.000</li><li>Iuran Keamanan: Rp 100.000</li><li>Iuran Kebersihan: Rp 25.000</li></ul><p><strong>Cara Pembayaran:</strong></p><ol><li>Transfer ke rekening perumahan</li><li>Upload bukti pembayaran melalui sistem</li><li>Tunggu verifikasi dari pengurus</li></ol><p>Batas waktu pembayaran: <strong>' . now()->addDays(15)->format('d F Y') . '</strong></p><p>Terima kasih atas kerjasamanya.</p>',
                'type' => 'financial',
                'is_active' => true,
                'publish_date' => now()->subDays(3),
                'expire_date' => now()->addDays(20),
                'send_telegram' => true,
                'telegram_sent_at' => now()->subDays(3),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Pemeliharaan Sistem Listrik Area Parkir',
                'content' => '<p>Diberitahukan kepada seluruh warga bahwa akan dilakukan pemeliharaan sistem listrik di area parkir pada:</p><p><strong>Hari/Tanggal:</strong> ' . now()->addDays(5)->format('l, d F Y') . '<br><strong>Waktu:</strong> 08.00 - 16.00 WIB</p><p>Selama pemeliharaan, penerangan di area parkir akan dimatikan sementara. Mohon berhati-hati saat parkir kendaraan.</p><p>Kami mohon maaf atas ketidaknyamanan yang mungkin terjadi.</p>',
                'type' => 'urgent',
                'is_active' => true,
                'publish_date' => now()->subDays(1),
                'expire_date' => now()->addDays(6),
                'send_telegram' => true,
                'telegram_sent_at' => now()->subDays(1),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Jadwal Kerja Bakti Bulanan',
                'content' => '<p>Dalam rangka menjaga kebersihan dan kenyamanan lingkungan perumahan, akan diadakan kerja bakti bulanan dengan detail sebagai berikut:</p><p><strong>Hari/Tanggal:</strong> ' . now()->nextWeekday()->format('l, d F Y') . '<br><strong>Waktu:</strong> 07.00 - 10.00 WIB<br><strong>Tempat:</strong> Area taman dan jalan utama perumahan</p><p><strong>Yang perlu dibawa:</strong></p><ul><li>Alat kebersihan (sapu, sekop, dll)</li><li>Sarung tangan</li><li>Semangat gotong royong</li></ul><p>Partisipasi seluruh warga sangat diharapkan untuk menciptakan lingkungan yang bersih dan nyaman.</p>',
                'type' => 'event',
                'is_active' => true,
                'publish_date' => now(),
                'expire_date' => now()->nextWeekday()->addDay(),
                'send_telegram' => false,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Aturan Baru Jam Berkunjung',
                'content' => '<p>Demi menjaga keamanan dan kenyamanan bersama, diberlakukan aturan baru mengenai jam berkunjung tamu:</p><p><strong>Jam Berkunjung:</strong></p><ul><li>Senin - Jumat: 06.00 - 22.00 WIB</li><li>Sabtu - Minggu: 06.00 - 23.00 WIB</li></ul><p><strong>Prosedur:</strong></p><ol><li>Tamu wajib lapor ke pos keamanan</li><li>Tamu akan dihubungkan dengan pemilik rumah</li><li>Tamu mendapat kartu tamu sementara</li><li>Kartu dikembalikan saat keluar</li></ol><p>Aturan ini berlaku efektif mulai tanggal <strong>' . now()->addDays(7)->format('d F Y') . '</strong>.</p><p>Terima kasih atas pengertian dan kerjasamanya.</p>',
                'type' => 'info',
                'is_active' => true,
                'publish_date' => now()->addDays(1),
                'expire_date' => null,
                'send_telegram' => false,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Rapat Koordinasi Bulanan RT/RW',
                'content' => '<p>Mengundang seluruh warga untuk menghadiri rapat koordinasi bulanan dengan agenda sebagai berikut:</p><p><strong>Hari/Tanggal:</strong> ' . now()->addDays(10)->format('l, d F Y') . '<br><strong>Waktu:</strong> 19.30 - 21.00 WIB<br><strong>Tempat:</strong> Balai pertemuan perumahan</p><p><strong>Agenda:</strong></p><ol><li>Laporan keuangan bulan lalu</li><li>Evaluasi program kebersihan</li><li>Rencana perbaikan fasilitas</li><li>Usulan dan saran warga</li><li>Lain-lain</li></ol><p>Kehadiran warga sangat diharapkan untuk membahas berbagai hal terkait kemajuan perumahan kita bersama.</p>',
                'type' => 'event',
                'is_active' => true,
                'publish_date' => now()->addDays(2),
                'expire_date' => now()->addDays(11),
                'send_telegram' => false,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Peningkatan Sistem Keamanan',
                'content' => '<p>Dalam upaya meningkatkan keamanan perumahan, telah dilakukan beberapa peningkatan sistem keamanan:</p><p><strong>Penambahan Fasilitas:</strong></p><ul><li>CCTV tambahan di 5 titik strategis</li><li>Sistem akses kartu untuk gerbang utama</li><li>Lampu penerangan jalan yang lebih terang</li><li>Komunikasi radio untuk petugas keamanan</li></ul><p><strong>Himbauan:</strong></p><ul><li>Selalu gunakan kartu akses saat keluar masuk</li><li>Laporkan aktivitas mencurigakan ke pos keamanan</li><li>Pastikan pintu dan jendela rumah terkunci</li></ul><p>Mari bersama-sama menjaga keamanan lingkungan kita.</p>',
                'type' => 'info',
                'is_active' => true,
                'publish_date' => now()->subDays(5),
                'expire_date' => null,
                'send_telegram' => true,
                'telegram_sent_at' => now()->subDays(5),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Program Penghijauan Lingkungan',
                'content' => '<p>Dalam rangka menciptakan lingkungan yang asri dan nyaman, akan dilaksanakan program penghijauan dengan detail:</p><p><strong>Kegiatan:</strong></p><ul><li>Penanaman pohon pelindung di sepanjang jalan</li><li>Pembuatan taman mini di setiap blok</li><li>Perawatan rutin tanaman yang ada</li></ul><p><strong>Jadwal:</strong> Setiap hari Minggu pagi (07.00 - 09.00 WIB)</p><p><strong>Bantuan yang dibutuhkan:</strong></p><ul><li>Tenaga sukarela untuk menanam</li><li>Sumbangan bibit tanaman</li><li>Alat-alat berkebun</li></ul><p>Bagi yang ingin berpartisipasi, silakan hubungi pengurus atau datang langsung pada jadwal yang ditentukan.</p>',
                'type' => 'event',
                'is_active' => true,
                'publish_date' => now()->subDays(2),
                'expire_date' => now()->addDays(30),
                'send_telegram' => false,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
