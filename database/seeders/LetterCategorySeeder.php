<?php

namespace Database\Seeders;

use App\Models\LetterCategory;
use App\Services\LetterTemplateService;
use Illuminate\Database\Seeder;

class LetterCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templateService = new LetterTemplateService();
        $categories = $templateService->getDefaultCategories();
        
        foreach ($categories as $categoryData) {
            LetterCategory::updateOrCreate(
                ['code' => $categoryData['code']],
                [
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                    'template' => $categoryData['template'],
                    'is_active' => $categoryData['is_active'],
                ]
            );
        }
        
        $this->command->info('Letter categories seeded successfully!');
    }
}
