<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LandingPageSeeder::class,
            FeeTypeSeeder::class,
            LetterCategorySeeder::class,
            FamilySeeder::class,
            AnnouncementSeeder::class,
            ActivityPhotoSeeder::class,
            ComplaintLetterSeeder::class,
            PaymentSubmissionSeeder::class,
            FinancialTransactionSeeder::class,
        ]);
    }
}
