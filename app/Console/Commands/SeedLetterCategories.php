<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LetterCategory;
use App\Services\LetterTemplateService;

class SeedLetterCategories extends Command
{
    protected $signature = 'seed:letter-categories';
    protected $description = 'Seed letter categories with default templates';

    public function handle()
    {
        $this->info('Seeding letter categories...');
        
        $templateService = new LetterTemplateService();
        $categories = $templateService->getDefaultCategories();
        
        foreach ($categories as $categoryData) {
            $category = LetterCategory::updateOrCreate(
                ['code' => $categoryData['code']],
                [
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                    'template' => $categoryData['template'],
                    'is_active' => $categoryData['is_active'],
                ]
            );
            
            $this->info("✓ Created/Updated category: {$category->name} ({$category->code})");
        }
        
        $this->info('Letter categories seeded successfully!');
    }
}
