<?php

namespace Database\Seeders;

use App\Models\ComplaintLetter;
use App\Models\User;
use App\Models\LetterCategory;
use App\Enums\ComplaintStatus;
use App\Enums\ComplaintPriority;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ComplaintLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and letter categories
        $users = User::where('role', 'resident')->get();
        $categories = LetterCategory::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Users or Letter Categories not found. Please run UserSeeder and LetterCategorySeeder first.');
            return;
        }

        $complaints = [
            [
                'subject' => 'Kerusakan Lampu Jalan di Blok A',
                'description' => 'Dengan hormat, saya ingin melaporkan adanya kerusakan lampu jalan di area Blok A tepatnya di depan rumah nomor 15-20. Lampu tersebut sudah mati sejak 3 hari yang lalu sehingga mengakibatkan area tersebut menjadi gelap di malam hari. Hal ini tentu saja mengganggu keamanan dan kenyamanan warga yang melewati area tersebut. Kami mengharapkan perbaikan segera agar keamanan warga tetap terjaga.',
                'status' => ComplaintStatus::SUBMITTED->value,
                'priority' => ComplaintPriority::HIGH->value,
                'category_type' => 'infrastruktur'
            ],
            [
                'subject' => 'Keluhan Tentang Sampah yang Menumpuk',
                'description' => 'Yang terhormat pengurus perumahan, saya ingin menyampaikan keluhan mengenai penumpukan sampah di area tempat pembuangan sampah komunal blok B. Sampah sudah menumpuk tinggi dan mulai menimbulkan bau tidak sedap. Selain itu, sampah juga berserakan di sekitar tempat pembuangan karena sudah penuh. Mohon untuk segera dilakukan pengangkutan sampah dan pembenahan sistem pengelolaan sampah agar lingkungan tetap bersih.',
                'status' => ComplaintStatus::IN_PROGRESS->value,
                'priority' => ComplaintPriority::MEDIUM->value,
                'category_type' => 'kebersihan'
            ],
            [
                'subject' => 'Permintaan Perbaikan Jalan Berlubang',
                'description' => 'Kepada Yth. Pengurus Perumahan Villa Windaro Permai, melalui surat ini saya ingin mengajukan permintaan perbaikan jalan yang berlubang di area Blok C. Lubang tersebut cukup besar dan dalam, sehingga membahayakan pengendara sepeda motor dan mobil yang melewati area tersebut. Beberapa warga sudah mengeluh karena kendaraan mereka rusak akibat lubang tersebut. Kami sangat mengharapkan perbaikan segera demi keselamatan dan kenyamanan bersama.',
                'status' => ComplaintStatus::RESOLVED->value,
                'priority' => ComplaintPriority::URGENT->value,
                'category_type' => 'infrastruktur'
            ],
            [
                'subject' => 'Gangguan Kebisingan dari Konstruksi',
                'description' => 'Dengan hormat, saya ingin menyampaikan keluhan tentang kebisingan yang ditimbulkan oleh aktivitas konstruksi di rumah nomor 45. Suara bising dari alat-alat konstruksi dimulai sangat pagi sekitar pukul 06.00 dan berlangsung hingga malam hari. Hal ini sangat mengganggu istirahat keluarga, terutama anak-anak dan lansia. Mohon untuk mengatur jadwal konstruksi yang lebih wajar dan mempertimbangkan kenyamanan warga sekitar.',
                'status' => ComplaintStatus::IN_REVIEW->value,
                'priority' => ComplaintPriority::MEDIUM->value,
                'category_type' => 'gangguan'
            ],
            [
                'subject' => 'Masalah Drainase yang Tersumbat',
                'description' => 'Yang terhormat pengurus perumahan, saya melaporkan adanya masalah drainase yang tersumbat di area Blok D. Setiap kali hujan, air menggenang cukup tinggi karena saluran air tidak berfungsi dengan baik. Genangan air ini sudah berlangsung selama berminggu-minggu dan mulai menimbulkan bau tidak sedap serta menjadi tempat berkembang biak nyamuk. Kami memohon untuk segera dilakukan pembersihan dan perbaikan sistem drainase.',
                'status' => ComplaintStatus::SUBMITTED->value,
                'priority' => ComplaintPriority::HIGH->value,
                'category_type' => 'infrastruktur'
            ],
            [
                'subject' => 'Pelanggaran Aturan Parkir Kendaraan',
                'description' => 'Kepada pengurus yang terhormat, saya ingin melaporkan adanya pelanggaran aturan parkir di area jalan utama perumahan. Beberapa warga memarkirkan kendaraan mereka di area yang tidak seharusnya, sehingga menyebabkan penyempitan jalan dan kesulitan bagi kendaraan lain untuk melewati area tersebut. Mohon untuk ditegaskan kembali aturan parkir dan dilakukan tindakan tegas terhadap pelanggar.',
                'status' => ComplaintStatus::CLOSED->value,
                'priority' => ComplaintPriority::LOW->value,
                'category_type' => 'ketertiban'
            ],
            [
                'subject' => 'Kerusakan Fasilitas Taman Bermain',
                'description' => 'Dengan hormat, saya ingin melaporkan kerusakan pada fasilitas taman bermain anak di area tengah perumahan. Beberapa permainan sudah rusak dan tidak aman untuk digunakan anak-anak, seperti ayunan yang rantainya putus dan perosotan yang sudah retak. Kami khawatir hal ini dapat membahayakan keselamatan anak-anak. Mohon untuk segera dilakukan perbaikan atau penggantian fasilitas yang rusak.',
                'status' => ComplaintStatus::IN_PROGRESS->value,
                'priority' => ComplaintPriority::MEDIUM->value,
                'category_type' => 'fasilitas'
            ]
        ];

        foreach ($complaints as $index => $complaintData) {
            // Generate letter number
            $letterNumber = 'SPG/' . date('Y') . '/' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            // Get random user and appropriate category
            $user = $users->random();
            $category = $categories->where('name', 'like', '%' . $complaintData['category_type'] . '%')->first()
                ?? $categories->random();

            // Generate timestamps based on status
            $submittedAt = Carbon::now()->subDays(rand(1, 30));
            $processedAt = null;
            $processedBy = null;
            $adminResponse = null;

            if (in_array($complaintData['status'], [
                ComplaintStatus::IN_REVIEW->value,
                ComplaintStatus::IN_PROGRESS->value,
                ComplaintStatus::RESOLVED->value,
                ComplaintStatus::CLOSED->value
            ])) {
                $processedAt = $submittedAt->copy()->addDays(rand(1, 5));
                $processedBy = User::where('role', 'admin')->first()?->id ?? 1;
                $adminResponse = $this->getAdminResponse($complaintData['status']);
            }

            // Create complaint letter
            ComplaintLetter::create([
                'letter_number' => $letterNumber,
                'user_id' => $user->id,
                'category_id' => $category->id,
                'subject' => $complaintData['subject'],
                'letter_date' => $submittedAt,
                'recipient' => 'Pengurus Perumahan Villa Windaro Permai',
                'description' => $complaintData['description'],
                'content' => $complaintData['description'], // Use description as content
                'status' => $complaintData['status'],
                'priority' => $complaintData['priority'],
                'attachments' => [], // Empty array for now
                'submitted_by' => $user->id,
                'submitted_at' => $submittedAt,
                'admin_notes' => $this->getAdminNotes($complaintData['status']),
                'admin_response' => $adminResponse,
                'processed_by' => $processedBy,
                'processed_at' => $processedAt,
                'pdf_path' => null, // Will be generated when needed
            ]);
        }

        $this->command->info('ComplaintLetterSeeder completed successfully!');
    }

    private function getAdminNotes(string $status): ?string
    {
        return match ($status) {
            ComplaintStatus::IN_REVIEW->value => 'Pengaduan sedang ditinjau oleh tim teknis. Akan segera ditindaklanjuti.',
            ComplaintStatus::IN_PROGRESS->value => 'Perbaikan sedang dalam proses pelaksanaan. Target selesai dalam 3-5 hari kerja.',
            ComplaintStatus::RESOLVED->value => 'Pengaduan telah selesai ditangani. Terima kasih atas laporannya.',
            ComplaintStatus::CLOSED->value => 'Pengaduan telah ditutup setelah diselesaikan. Tidak ada tindakan lebih lanjut yang diperlukan.',
            default => null,
        };
    }

    private function getAdminResponse(string $status): ?string
    {
        return match ($status) {
            ComplaintStatus::IN_REVIEW->value => 'Terima kasih atas laporan Anda. Pengaduan sedang ditinjau oleh tim terkait. Kami akan segera memberikan respon lebih lanjut.',
            ComplaintStatus::IN_PROGRESS->value => 'Terima kasih atas laporan Anda. Tim maintenance sudah ditugaskan untuk menangani masalah ini. Kami akan memberikan update progress secara berkala.',
            ComplaintStatus::RESOLVED->value => 'Pengaduan telah selesai ditangani dengan baik. Semoga permasalahan tidak terulang lagi. Terima kasih atas partisipasi Anda dalam menjaga kenyamanan perumahan.',
            ComplaintStatus::CLOSED->value => 'Pengaduan telah ditutup karena sudah diselesaikan. Jika masih ada masalah serupa, silakan ajukan pengaduan baru.',
            default => null,
        };
    }
}
