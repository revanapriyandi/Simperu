<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupSimperuSystem extends Command
{
    protected $signature = 'simperu:setup';
    protected $description = 'Setup complete Simperu system with migrations and seeders';

    public function handle()
    {
        $this->info('🚀 Setting up Simperu System for Villa Windaro Permai...');
        $this->newLine();

        // Run migrations
        $this->info('📊 Running database migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info('✅ Migrations completed successfully!');
        $this->newLine();

        // Seed letter categories
        $this->info('📝 Seeding letter categories...');
        Artisan::call('seed:letter-categories');
        $this->info('✅ Letter categories seeded successfully!');
        $this->newLine();

        // Clear and cache config
        $this->info('🧹 Clearing and caching configuration...');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        $this->info('✅ System cache cleared successfully!');
        $this->newLine();

        $this->info('🎉 Simperu system setup completed successfully!');
        $this->newLine();
        
        $this->comment('📋 What\'s next:');
        $this->line('1. Configure your .env file with Telegram bot token if you want notifications');
        $this->line('2. Import family data using: php artisan import:families path/to/csv');
        $this->line('3. Create admin user via Filament admin panel');
        $this->line('4. Test resident registration at /resident/register');
        $this->newLine();
        
        $this->info('🏠 Villa Windaro Permai Management System is ready!');
    }
}
