<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixRegistrationIssue extends Command
{
    protected $signature = 'fix:registration';
    protected $description = 'Fix registration issue by running migration';

    public function handle()
    {
        $this->info('🔧 Fixing registration issue...');
        
        // Run specific migration
        $this->info('📊 Running user fields migration...');
        $this->call('migrate', ['--path' => 'database/migrations/2025_06_24_012000_add_extended_user_fields.php']);
        
        $this->info('📊 Running digital signature migration...');
        $this->call('migrate', ['--path' => 'database/migrations/2025_06_24_012100_add_digital_signature_to_complaint_letters.php']);
        
        $this->info('✅ Registration issue fixed! You can now register new residents.');
    }
}
