<?php

namespace Database\Seeders;

use App\Models\FinancialTransaction;
use App\Models\User;
use App\Models\Family;
use App\Models\FeeType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FinancialTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get required data
        $adminUser = User::where('role', 'admin')->first();
        $families = Family::all();
        $feeTypes = FeeType::all();

        if (!$adminUser || $families->isEmpty() || $feeTypes->isEmpty()) {
            $this->command->warn('Admin user, Families, or FeeTypes not found. Please run related seeders first.');
            return;
        }

        $currentDate = Carbon::now();

        // Generate income transactions (fee payments from residents)
        $this->generateIncomeTransactions($adminUser, $families, $feeTypes, $currentDate);

        // Generate expense transactions (operational costs)
        $this->generateExpenseTransactions($adminUser, $currentDate);

        // Generate donation income
        $this->generateDonationTransactions($adminUser, $families, $currentDate);

        $this->command->info('FinancialTransactionSeeder completed successfully!');
    }

    private function generateIncomeTransactions(User $adminUser, $families, $feeTypes, Carbon $currentDate): void
    {
        // Generate income from fee payments for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentDate->copy()->subMonths($i);

            foreach ($families as $family) {
                // Each family pays 2-4 types of fees per month
                $selectedFeeTypes = $feeTypes->random(rand(2, 4));

                foreach ($selectedFeeTypes as $feeType) {
                    // 80% chance of payment per fee type
                    if (rand(1, 100) <= 80) {
                        $transactionDate = $month->copy()->addDays(rand(1, 28));

                        FinancialTransaction::create([
                            'transaction_date' => $transactionDate,
                            'type' => 'income',
                            'category' => 'fee',
                            'fee_type_id' => $feeType->id,
                            'family_id' => $family->id,
                            'amount' => $feeType->amount,
                            'description' => "Pembayaran {$feeType->name} - {$family->head_of_family} (Periode: " . $month->format('F Y') . ")",
                            'reference_number' => 'TRX' . $transactionDate->format('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                            'receipt_path' => null,
                            'status' => 'verified',
                            'verified_by' => $adminUser->id,
                            'verified_at' => $transactionDate->copy()->addHours(rand(1, 48)),
                            'notes' => 'Pembayaran iuran rutin bulanan',
                            'created_by' => $adminUser->id,
                            'created_at' => $transactionDate,
                            'updated_at' => $transactionDate->copy()->addHours(rand(1, 48)),
                        ]);
                    }
                }
            }
        }
    }

    private function generateExpenseTransactions(User $adminUser, Carbon $currentDate): void
    {
        $expenses = [
            // Maintenance expenses
            [
                'category' => 'maintenance',
                'description' => 'Perbaikan lampu jalan blok A dan B',
                'amount' => 1500000,
                'reference' => 'INV-2024-001'
            ],
            [
                'category' => 'maintenance',
                'description' => 'Pengecatan gapura utama perumahan',
                'amount' => 2500000,
                'reference' => 'INV-2024-002'
            ],
            [
                'category' => 'maintenance',
                'description' => 'Perbaikan drainase blok C',
                'amount' => 3200000,
                'reference' => 'INV-2024-003'
            ],
            [
                'category' => 'maintenance',
                'description' => 'Servis pompa air komunal',
                'amount' => 800000,
                'reference' => 'INV-2024-004'
            ],

            // Operational expenses
            [
                'category' => 'operational',
                'description' => 'Gaji petugas keamanan (3 bulan)',
                'amount' => 9000000,
                'reference' => 'PAY-2024-001'
            ],
            [
                'category' => 'operational',
                'description' => 'Gaji petugas kebersihan (3 bulan)',
                'amount' => 4500000,
                'reference' => 'PAY-2024-002'
            ],
            [
                'category' => 'operational',
                'description' => 'Tagihan listrik fasilitas umum',
                'amount' => 1200000,
                'reference' => 'PLN-2024-001'
            ],
            [
                'category' => 'operational',
                'description' => 'Pembelian alat kebersihan dan supplies',
                'amount' => 650000,
                'reference' => 'PUR-2024-001'
            ],

            // Event expenses
            [
                'category' => 'event',
                'description' => 'Perayaan HUT RI ke-79 - dekorasi dan doorprize',
                'amount' => 2800000,
                'reference' => 'EVT-2024-001'
            ],
            [
                'category' => 'event',
                'description' => 'Takjil Ramadan bersama',
                'amount' => 1500000,
                'reference' => 'EVT-2024-002'
            ],
            [
                'category' => 'event',
                'description' => 'Lomba anak-anak dalam rangka Hari Kartini',
                'amount' => 750000,
                'reference' => 'EVT-2024-003'
            ],
        ];

        foreach ($expenses as $index => $expense) {
            $transactionDate = $currentDate->copy()->subDays(rand(30, 180));

            FinancialTransaction::create([
                'transaction_date' => $transactionDate,
                'type' => 'expense',
                'category' => $expense['category'],
                'fee_type_id' => null,
                'family_id' => null,
                'amount' => $expense['amount'],
                'description' => $expense['description'],
                'reference_number' => $expense['reference'],
                'receipt_path' => 'receipts/expense-receipt-' . ($index + 1) . '.jpg',
                'status' => 'verified',
                'verified_by' => $adminUser->id,
                'verified_at' => $transactionDate->copy()->addDays(rand(1, 3)),
                'notes' => 'Pengeluaran operasional perumahan',
                'created_by' => $adminUser->id,
                'created_at' => $transactionDate,
                'updated_at' => $transactionDate->copy()->addDays(rand(1, 3)),
            ]);
        }
    }

    private function generateDonationTransactions(User $adminUser, $families, Carbon $currentDate): void
    {
        $donations = [
            [
                'description' => 'Sumbangan untuk korban bencana alam',
                'base_amount' => 100000
            ],
            [
                'description' => 'Donasi pembangunan musholla',
                'base_amount' => 200000
            ],
            [
                'description' => 'Bantuan untuk warga yang tertimpa musibah',
                'base_amount' => 150000
            ],
            [
                'description' => 'Sumbangan peningkatan fasilitas taman bermain',
                'base_amount' => 250000
            ],
        ];

        foreach ($donations as $donation) {
            // 60% of families participate in donations
            $participatingFamilies = $families->random((int) ($families->count() * 0.6));

            foreach ($participatingFamilies as $family) {
                $transactionDate = $currentDate->copy()->subDays(rand(15, 90));
                $amount = $donation['base_amount'] + rand(-50000, 100000); // Variation in donation amounts

                FinancialTransaction::create([
                    'transaction_date' => $transactionDate,
                    'type' => 'income',
                    'category' => 'donation',
                    'fee_type_id' => null,
                    'family_id' => $family->id,
                    'amount' => $amount,
                    'description' => $donation['description'] . ' - dari ' . $family->head_of_family,
                    'reference_number' => 'DON' . $transactionDate->format('Ymd') . str_pad($family->id, 3, '0', STR_PAD_LEFT),
                    'receipt_path' => null,
                    'status' => 'verified',
                    'verified_by' => $adminUser->id,
                    'verified_at' => $transactionDate->copy()->addHours(rand(2, 24)),
                    'notes' => 'Donasi sukarela dari warga',
                    'created_by' => $adminUser->id,
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate->copy()->addHours(rand(2, 24)),
                ]);
            }
        }
    }
}
