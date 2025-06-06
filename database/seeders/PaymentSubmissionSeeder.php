<?php

namespace Database\Seeders;

use App\Models\PaymentSubmission;
use App\Models\User;
use App\Models\Family;
use App\Models\FeeType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get required data
        $users = User::where('role', 'resident')->get();
        $families = Family::all();
        $feeTypes = FeeType::all();

        if ($users->isEmpty() || $families->isEmpty() || $feeTypes->isEmpty()) {
            $this->command->warn('Users, Families, or FeeTypes not found. Please run related seeders first.');
            return;
        }

        // Generate payment submissions for the last 6 months
        $currentDate = Carbon::now();
        $statuses = ['pending', 'approved', 'rejected'];
        $months = [];

        // Generate months array for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $months[] = $currentDate->copy()->subMonths($i);
        }

        foreach ($families as $family) {
            foreach ($months as $month) {
                // Each family submits 1-3 different fee types per month
                $selectedFeeTypes = $feeTypes->random(rand(1, 3));

                foreach ($selectedFeeTypes as $feeType) {
                    // 70% chance of submission per fee type per month
                    if (rand(1, 100) <= 70) {
                        $status = $this->getWeightedStatus();
                        $submissionDate = $month->copy()->addDays(rand(1, 28));

                        PaymentSubmission::create([
                            'user_id' => $family->user_id,
                            'family_id' => $family->id,
                            'fee_type_id' => $feeType->id,
                            'period_month' => $month->month,
                            'period_year' => $month->year,
                            'amount' => $feeType->amount + rand(-5000, 10000), // Slight variation
                            'payment_date' => $submissionDate->copy()->subDays(rand(0, 5)),
                            'receipt_path' => 'payment-receipts/receipt-' . $family->id . '-' . $feeType->id . '-' . $month->format('Y-m') . '.jpg',
                            'status' => $status,
                            'admin_notes' => $this->getAdminNotes($status),
                            'verified_by' => $status !== 'pending' ? User::where('role', 'admin')->first()?->id : null,
                            'verified_at' => $status !== 'pending' ? $submissionDate->copy()->addDays(rand(1, 7)) : null,
                            'created_at' => $submissionDate,
                            'updated_at' => $status !== 'pending' ? $submissionDate->copy()->addDays(rand(1, 7)) : $submissionDate,
                        ]);
                    }
                }
            }
        }

        // Create some additional submissions for current month (mostly pending)
        foreach ($families->take(8) as $family) {
            $currentMonthFeeTypes = $feeTypes->random(rand(1, 2));

            foreach ($currentMonthFeeTypes as $feeType) {
                PaymentSubmission::create([
                    'user_id' => $family->user_id,
                    'family_id' => $family->id,
                    'fee_type_id' => $feeType->id,
                    'period_month' => $currentDate->month,
                    'period_year' => $currentDate->year,
                    'amount' => $feeType->amount,
                    'payment_date' => $currentDate->copy()->subDays(rand(1, 10)),
                    'receipt_path' => 'payment-receipts/receipt-' . $family->id . '-' . $feeType->id . '-' . $currentDate->format('Y-m') . '.jpg',
                    'status' => 'pending',
                    'admin_notes' => null,
                    'verified_by' => null,
                    'verified_at' => null,
                    'created_at' => $currentDate->copy()->subDays(rand(1, 10)),
                    'updated_at' => $currentDate->copy()->subDays(rand(1, 10)),
                ]);
            }
        }

        $this->command->info('PaymentSubmissionSeeder completed successfully!');
    }

    private function getWeightedStatus(): string
    {
        $random = rand(1, 100);

        // 65% approved, 25% pending, 10% rejected
        if ($random <= 65) {
            return 'approved';
        } elseif ($random <= 90) {
            return 'pending';
        } else {
            return 'rejected';
        }
    }

    private function getAdminNotes(string $status): ?string
    {
        return match ($status) {
            'approved' => collect([
                'Pembayaran telah terverifikasi dan sesuai dengan jumlah iuran.',
                'Bukti pembayaran valid. Terima kasih atas ketepatan waktu pembayaran.',
                'Pembayaran diterima dengan baik. Mohon pertahankan kedisiplinan ini.',
                'Verifikasi berhasil. Pembayaran telah dicatat dalam sistem keuangan.',
            ])->random(),
            'rejected' => collect([
                'Bukti pembayaran tidak jelas atau terpotong. Mohon upload ulang dengan kualitas yang lebih baik.',
                'Jumlah pembayaran tidak sesuai dengan tarif iuran yang berlaku.',
                'Tanggal pembayaran tidak sesuai dengan periode iuran yang diklaim.',
                'Format bukti pembayaran tidak dapat diverifikasi. Silakan hubungi admin untuk klarifikasi.',
            ])->random(),
            default => null,
        };
    }
}
