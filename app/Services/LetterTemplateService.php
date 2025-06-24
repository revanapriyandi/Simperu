<?php

namespace App\Services;

use App\Models\ComplaintLetter;
use App\Models\LetterCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LetterTemplateService
{
    /**
     * Generate nomor surat berdasarkan kategori
     */
    public function generateLetterNumber(LetterCategory $category): string
    {
        $year = now()->year;
        $month = now()->format('m');
        
        // Get last number untuk kategori ini di tahun ini
        $lastLetter = ComplaintLetter::where('category_id', $category->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->first();

        $sequence = 1;
        if ($lastLetter && $lastLetter->letter_number) {
            // Extract sequence number from letter number
            $pattern = '/(\d+)\/' . preg_quote($category->code) . '\/PVWP\//';
            if (preg_match($pattern, $lastLetter->letter_number, $matches)) {
                $sequence = (int) $matches[1] + 1;
            }
        }

        // Format: 01/LNG/PVWP/V/2025
        return sprintf(
            '%02d/%s/PVWP/%s/%d',
            $sequence,
            $category->code,
            $this->getMonthRoman($month),
            $year
        );
    }

    /**
     * Get default letter categories
     */
    public function getDefaultCategories(): array
    {
        return [
            [
                'code' => 'LNG',
                'name' => 'Surat Lingkungan',
                'description' => 'Surat terkait sampah & keamanan lingkungan',
                'template' => 'lingkungan',
                'is_active' => true,
            ],
            [
                'code' => 'FST',
                'name' => 'Surat Fasilitas',
                'description' => 'Surat terkait fasilitas perumahan',
                'template' => 'fasilitas',
                'is_active' => true,
            ],
            [
                'code' => 'KLH',
                'name' => 'Keterangan Kelahiran',
                'description' => 'Surat keterangan kelahiran',
                'template' => 'kelahiran',
                'is_active' => true,
            ],
            [
                'code' => 'KMT',
                'name' => 'Keterangan Kematian',
                'description' => 'Surat keterangan kematian',
                'template' => 'kematian',
                'is_active' => true,
            ],
            [
                'code' => 'IZA',
                'name' => 'Izin Acara',
                'description' => 'Surat izin mengadakan acara',
                'template' => 'izin_acara',
                'is_active' => true,
            ],
            [
                'code' => 'PMT',
                'name' => 'Peminjaman Tempat',
                'description' => 'Surat peminjaman tempat/fasilitas',
                'template' => 'peminjaman',
                'is_active' => true,
            ],
            [
                'code' => 'UND',
                'name' => 'Undangan',
                'description' => 'Surat undangan rapat/acara',
                'template' => 'undangan',
                'is_active' => true,
            ],
        ];
    }

    /**
     * Create default letter template content
     */
    public function getDefaultTemplate(string $templateType): string
    {
        return match ($templateType) {
            'undangan' => $this->getUndanganTemplate(),
            'lingkungan' => $this->getLingkunganTemplate(),
            'fasilitas' => $this->getFasilitasTemplate(),
            'kelahiran' => $this->getKelahiranTemplate(),
            'kematian' => $this->getKematianTemplate(),
            'izin_acara' => $this->getIzinAcaraTemplate(),
            'peminjaman' => $this->getPeminjamanTemplate(),
            default => $this->getDefaultLetterTemplate(),
        };
    }

    protected function getUndanganTemplate(): string
    {
        return 'Assalamu\'alaikum warahmatullahi wabarakatuh

Dengan hormat,

Dalam rangka [keperluan_acara], bersama ini kami mengundang Bapak/Ibu untuk hadir dalam:

Hari/Tanggal : [tanggal_acara]
Waktu        : [waktu_acara]
Tempat       : [tempat_acara]
Acara        : [nama_acara]

Demikian undangan ini kami sampaikan, atas perhatian dan kehadirannya kami ucapkan terima kasih.

Wassalamu\'alaikum warahmatullahi wabarakatuh';
    }

    protected function getLingkunganTemplate(): string
    {
        return 'Dengan hormat,

Sehubungan dengan [permasalahan_lingkungan] yang terjadi di area perumahan Villa Windaro Permai, khususnya di [lokasi_spesifik], bersama ini kami sampaikan hal-hal sebagai berikut:

1. [Deskripsi masalah]
2. [Dampak yang ditimbulkan]
3. [Permintaan tindakan]

Demikian surat ini kami buat, atas perhatian dan tindak lanjutnya kami ucapkan terima kasih.';
    }

    protected function getFasilitasTemplate(): string
    {
        return 'Dengan hormat,

Berkaitan dengan [jenis_fasilitas] di lingkungan perumahan Villa Windaro Permai, bersama ini kami sampaikan:

[Deskripsi permasalahan atau permintaan fasilitas]

Harapan kami [tindakan yang diharapkan] dapat segera ditindaklanjuti untuk kenyamanan bersama.

Demikian surat ini kami buat, atas perhatian dan kerjasamanya kami ucapkan terima kasih.';
    }

    protected function getKelahiranTemplate(): string
    {
        return 'Yang bertanda tangan di bawah ini, Pengurus Perumahan Villa Windaro Permai, dengan ini menerangkan bahwa:

Nama            : [nama_bayi]
Tempat Lahir    : [tempat_lahir]
Tanggal Lahir   : [tanggal_lahir]
Jenis Kelamin   : [jenis_kelamin]
Nama Ayah       : [nama_ayah]
Nama Ibu        : [nama_ibu]
Alamat          : [alamat_lengkap]

Adalah benar telah lahir dan berdomisili di lingkungan Perumahan Villa Windaro Permai.

Surat keterangan ini dibuat untuk keperluan [keperluan_surat].';
    }

    protected function getKematianTemplate(): string
    {
        return 'Yang bertanda tangan di bawah ini, Pengurus Perumahan Villa Windaro Permai, dengan ini menerangkan bahwa:

Nama            : [nama_almarhum]
Tempat Lahir    : [tempat_lahir]
Tanggal Lahir   : [tanggal_lahir]
Tanggal Wafat   : [tanggal_wafat]
Sebab Kematian  : [sebab_kematian]
Alamat          : [alamat_lengkap]

Adalah benar telah meninggal dunia dan berdomisili di lingkungan Perumahan Villa Windaro Permai.

Surat keterangan ini dibuat untuk keperluan [keperluan_surat].';
    }

    protected function getIzinAcaraTemplate(): string
    {
        return 'Dengan hormat,

Yang bertanda tangan di bawah ini bermaksud mengajukan permohonan izin untuk mengadakan acara dengan detail sebagai berikut:

Nama Acara      : [nama_acara]
Jenis Acara     : [jenis_acara]
Tanggal         : [tanggal_acara]
Waktu           : [waktu_acara]
Tempat          : [tempat_acara]
Jumlah Peserta  : [jumlah_peserta]
Penanggung Jawab: [nama_penanggung_jawab]

Kami berkomitmen untuk menjaga ketertiban dan kebersihan selama acara berlangsung.

Demikian permohonan ini kami sampaikan, atas persetujuannya kami ucapkan terima kasih.';
    }

    protected function getPeminjamanTemplate(): string
    {
        return 'Dengan hormat,

Yang bertanda tangan di bawah ini bermaksud mengajukan permohonan peminjaman fasilitas dengan detail sebagai berikut:

Fasilitas yang dipinjam : [nama_fasilitas]
Keperluan              : [keperluan_peminjaman]
Tanggal Peminjaman     : [tanggal_pinjam]
Waktu                  : [waktu_pinjam]
Lama Peminjaman        : [durasi_pinjam]
Penanggung Jawab       : [nama_penanggung_jawab]
Kontak                 : [nomor_kontak]

Kami berkomitmen untuk menjaga dan merawat fasilitas yang dipinjam dengan baik.

Demikian permohonan ini kami sampaikan, atas persetujuannya kami ucapkan terima kasih.';
    }

    protected function getDefaultLetterTemplate(): string
    {
        return 'Dengan hormat,

[Isi surat]

Demikian surat ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih.';
    }

    /**
     * Convert month number to Roman numeral
     */
    protected function getMonthRoman(string $month): string
    {
        $romans = [
            '01' => 'I', '02' => 'II', '03' => 'III', '04' => 'IV',
            '05' => 'V', '06' => 'VI', '07' => 'VII', '08' => 'VIII',
            '09' => 'IX', '10' => 'X', '11' => 'XI', '12' => 'XII'
        ];

        return $romans[$month] ?? 'I';
    }

    /**
     * Replace template variables with actual data
     */
    public function processTemplate(string $template, array $data): string
    {
        $content = $template;
        
        foreach ($data as $key => $value) {
            $placeholder = '[' . $key . ']';
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }

    /**
     * Get available template variables for a template type
     */
    public function getTemplateVariables(string $templateType): array
    {
        return match ($templateType) {
            'undangan' => [
                'keperluan_acara', 'tanggal_acara', 'waktu_acara', 'tempat_acara', 'nama_acara'
            ],
            'lingkungan' => [
                'permasalahan_lingkungan', 'lokasi_spesifik'
            ],
            'fasilitas' => [
                'jenis_fasilitas'
            ],
            'kelahiran' => [
                'nama_bayi', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 
                'nama_ayah', 'nama_ibu', 'alamat_lengkap', 'keperluan_surat'
            ],
            'kematian' => [
                'nama_almarhum', 'tempat_lahir', 'tanggal_lahir', 'tanggal_wafat',
                'sebab_kematian', 'alamat_lengkap', 'keperluan_surat'
            ],
            'izin_acara' => [
                'nama_acara', 'jenis_acara', 'tanggal_acara', 'waktu_acara', 
                'tempat_acara', 'jumlah_peserta', 'nama_penanggung_jawab'
            ],
            'peminjaman' => [
                'nama_fasilitas', 'keperluan_peminjaman', 'tanggal_pinjam', 
                'waktu_pinjam', 'durasi_pinjam', 'nama_penanggung_jawab', 'nomor_kontak'
            ],
            default => []
        };
    }
}
