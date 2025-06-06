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
                'code' => 'SKD',
                'name' => 'Surat Keterangan Domisili',
                'description' => 'Surat keterangan bahwa seseorang berdomisili di perumahan ini',
                'template' => '<p>Yang bertanda tangan di bawah ini, Ketua RT/RW Perumahan [NAMA_PERUMAHAN], dengan ini menerangkan bahwa:</p>
<ul>
<li>Nama: [NAMA_LENGKAP]</li>
<li>NIK: [NIK]</li>
<li>Alamat: [ALAMAT_LENGKAP]</li>
</ul>
<p>Adalah benar berdomisili di wilayah kami sejak [TANGGAL_MULAI].</p>
<p>Demikian surat keterangan ini dibuat untuk dapat digunakan sebagaimana mestinya.</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SKU',
                'name' => 'Surat Keterangan Usaha',
                'description' => 'Surat keterangan untuk usaha/bisnis yang beroperasi di area perumahan',
                'template' => '<p>Yang bertanda tangan di bawah ini, Ketua RT/RW Perumahan [NAMA_PERUMAHAN], dengan ini menerangkan bahwa:</p>
<ul>
<li>Nama: [NAMA_LENGKAP]</li>
<li>NIK: [NIK]</li>
<li>Alamat: [ALAMAT_LENGKAP]</li>
<li>Jenis Usaha: [JENIS_USAHA]</li>
</ul>
<p>Adalah benar menjalankan usaha [JENIS_USAHA] di wilayah kami sejak [TANGGAL_MULAI_USAHA].</p>
<p>Demikian surat keterangan ini dibuat untuk dapat digunakan sebagaimana mestinya.</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SKTM',
                'name' => 'Surat Keterangan Tidak Mampu',
                'description' => 'Surat keterangan untuk warga yang kurang mampu secara ekonomi',
                'template' => '<p>Yang bertanda tangan di bawah ini, Ketua RT/RW Perumahan [NAMA_PERUMAHAN], dengan ini menerangkan bahwa:</p>
<ul>
<li>Nama: [NAMA_LENGKAP]</li>
<li>NIK: [NIK]</li>
<li>Alamat: [ALAMAT_LENGKAP]</li>
<li>Pekerjaan: [PEKERJAAN]</li>
</ul>
<p>Adalah benar termasuk dalam kategori keluarga kurang mampu berdasarkan pengamatan dan data yang kami miliki.</p>
<p>Demikian surat keterangan ini dibuat untuk dapat digunakan sebagaimana mestinya.</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SKCK',
                'name' => 'Surat Keterangan Catatan Kepolisian',
                'description' => 'Surat pengantar untuk pembuatan SKCK',
                'template' => '<p>Yang bertanda tangan di bawah ini, Ketua RT/RW Perumahan [NAMA_PERUMAHAN], dengan ini menerangkan bahwa:</p>
<ul>
<li>Nama: [NAMA_LENGKAP]</li>
<li>NIK: [NIK]</li>
<li>Alamat: [ALAMAT_LENGKAP]</li>
</ul>
<p>Adalah benar warga perumahan kami yang berkelakuan baik dan tidak pernah terlibat dalam tindak pidana.</p>
<p>Surat pengantar ini dibuat untuk keperluan pembuatan Surat Keterangan Catatan Kepolisian (SKCK).</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SKP',
                'name' => 'Surat Keterangan Pindah',
                'description' => 'Surat keterangan untuk warga yang akan pindah alamat',
                'template' => '<p>Yang bertanda tangan di bawah ini, Ketua RT/RW Perumahan [NAMA_PERUMAHAN], dengan ini menerangkan bahwa:</p>
<ul>
<li>Nama: [NAMA_LENGKAP]</li>
<li>NIK: [NIK]</li>
<li>Alamat Asal: [ALAMAT_ASAL]</li>
<li>Alamat Tujuan: [ALAMAT_TUJUAN]</li>
</ul>
<p>Adalah benar akan pindah dari wilayah kami pada tanggal [TANGGAL_PINDAH].</p>
<p>Demikian surat keterangan ini dibuat untuk keperluan administrasi pindah domisili.</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SKK',
                'name' => 'Surat Keterangan Kelahiran',
                'description' => 'Surat keterangan untuk kelahiran anak',
                'template' => '<p>Yang bertanda tangan di bawah ini, Ketua RT/RW Perumahan [NAMA_PERUMAHAN], dengan ini menerangkan bahwa:</p>
<ul>
<li>Nama Bayi: [NAMA_BAYI]</li>
<li>Tempat/Tanggal Lahir: [TEMPAT_LAHIR] / [TANGGAL_LAHIR]</li>
<li>Jenis Kelamin: [JENIS_KELAMIN]</li>
<li>Nama Ayah: [NAMA_AYAH]</li>
<li>Nama Ibu: [NAMA_IBU]</li>
<li>Alamat: [ALAMAT_LENGKAP]</li>
</ul>
<p>Adalah benar telah lahir di wilayah kami.</p>
<p>Demikian surat keterangan ini dibuat untuk keperluan administrasi kelahiran.</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SKM',
                'name' => 'Surat Keterangan Menikah',
                'description' => 'Surat keterangan untuk pengantar pernikahan',
                'template' => '<p>Yang bertanda tangan di bawah ini, Ketua RT/RW Perumahan [NAMA_PERUMAHAN], dengan ini menerangkan bahwa:</p>
<ul>
<li>Nama Calon Pengantin Pria: [NAMA_PRIA]</li>
<li>NIK: [NIK_PRIA]</li>
<li>Nama Calon Pengantin Wanita: [NAMA_WANITA]</li>
<li>NIK: [NIK_WANITA]</li>
<li>Alamat: [ALAMAT_LENGKAP]</li>
</ul>
<p>Adalah benar warga kami yang akan melangsungkan pernikahan.</p>
<p>Demikian surat keterangan ini dibuat untuk keperluan administrasi pernikahan.</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SPE',
                'name' => 'Surat Pengaduan Umum',
                'description' => 'Surat pengaduan untuk berbagai keperluan umum',
                'template' => '<p>Kepada Yth. [PENERIMA_SURAT]</p>
<p>Dengan hormat,</p>
<p>Yang bertanda tangan di bawah ini:</p>
<ul>
<li>Nama: [NAMA_PENGADU]</li>
<li>Alamat: [ALAMAT_PENGADU]</li>
<li>Telepon: [TELEPON_PENGADU]</li>
</ul>
<p>Dengan ini mengajukan pengaduan mengenai: [SUBJEK_PENGADUAN]</p>
<p>[DETAIL_PENGADUAN]</p>
<p>Demikian pengaduan ini kami sampaikan, atas perhatian dan tindak lanjutnya kami ucapkan terima kasih.</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SPI',
                'name' => 'Surat Permohonan Izin',
                'description' => 'Surat permohonan izin untuk berbagai kegiatan',
                'template' => '<p>Kepada Yth. [PENERIMA_SURAT]</p>
<p>Dengan hormat,</p>
<p>Yang bertanda tangan di bawah ini:</p>
<ul>
<li>Nama: [NAMA_PEMOHON]</li>
<li>Alamat: [ALAMAT_PEMOHON]</li>
<li>Telepon: [TELEPON_PEMOHON]</li>
</ul>
<p>Dengan ini mengajukan permohonan izin untuk: [KEPERLUAN_IZIN]</p>
<p>Detail kegiatan: [DETAIL_KEGIATAN]</p>
<p>Waktu pelaksanaan: [WAKTU_PELAKSANAAN]</p>
<p>Demikian permohonan ini kami sampaikan, atas perhatian dan persetujuannya kami ucapkan terima kasih.</p>',
                'is_active' => true,
            ],
            [
                'code' => 'SKS',
                'name' => 'Surat Keterangan Sehat',
                'description' => 'Surat pengantar untuk pemeriksaan kesehatan',
                'template' => '<p>Yang bertanda tangan di bawah ini, Ketua RT/RW Perumahan [NAMA_PERUMAHAN], dengan ini menerangkan bahwa:</p>
<ul>
<li>Nama: [NAMA_LENGKAP]</li>
<li>NIK: [NIK]</li>
<li>Alamat: [ALAMAT_LENGKAP]</li>
</ul>
<p>Berdasarkan pengamatan dan informasi yang kami peroleh, yang bersangkutan dalam keadaan sehat jasmani dan rohani.</p>
<p>Demikian surat keterangan ini dibuat untuk keperluan [KEPERLUAN].</p>',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            LetterCategory::create($category);
        }
    }
}
