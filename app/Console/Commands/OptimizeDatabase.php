<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database performance by adding indexes and analyzing tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database optimization...');

        // Add indexes for better query performance
        $this->addMissingIndexes();

        // Analyze tables for better query planning
        $this->analyzeTables();

        // Clear query cache
        $this->clearQueryCache();

        $this->info('Database optimization completed!');

        return 0;
    }

    private function addMissingIndexes()
    {
        $this->info('Adding missing database indexes...');

        $indexes = [
            'users' => [
                ['role'],
                ['telegram_chat_id'],
                ['phone', 'house_number'],
            ],
            'families' => [
                ['user_id', 'status'],
                ['kk_number'],
                ['house_block'],
            ],
            'payment_submissions' => [
                ['family_id', 'status'],
                ['period_month', 'period_year'],
                ['status', 'created_at'],
            ],
            'complaint_letters' => [
                ['submitted_by', 'status'],
                ['status', 'submitted_at'],
                ['category_id', 'status'],
            ],
            'announcements' => [
                ['is_active', 'publish_date'],
                ['type', 'is_active'],
            ],
        ];

        foreach ($indexes as $table => $tableIndexes) {
            if (!Schema::hasTable($table)) {
                $this->warn("Table {$table} does not exist, skipping...");
                continue;
            }

            foreach ($tableIndexes as $columns) {
                $indexName = $table . '_' . implode('_', $columns) . '_index';

                try {
                    if (!$this->indexExists($table, $indexName)) {
                        Schema::table($table, function ($table) use ($columns, $indexName) {
                            $table->index($columns, $indexName);
                        });
                        $this->line("✓ Added index {$indexName}");
                    } else {
                        $this->line("- Index {$indexName} already exists");
                    }
                } catch (\Exception $e) {
                    $this->error("✗ Failed to add index {$indexName}: " . $e->getMessage());
                }
            }
        }
    }

    private function analyzeTables()
    {
        $this->info('Analyzing tables for query optimization...');

        $tables = ['users', 'families', 'payment_submissions', 'complaint_letters', 'announcements'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    DB::statement("ANALYZE TABLE {$table}");
                    $this->line("✓ Analyzed table {$table}");
                } catch (\Exception $e) {
                    $this->error("✗ Failed to analyze table {$table}: " . $e->getMessage());
                }
            }
        }
    }

    private function clearQueryCache()
    {
        $this->info('Clearing query cache...');

        try {
            DB::statement('RESET QUERY CACHE');
            $this->line('✓ Query cache cleared');
        } catch (\Exception $e) {
            $this->line('- Query cache clearing not supported or already clear');
        }
    }

    private function indexExists($table, $indexName)
    {
        $indexes = DB::select(DB::raw("SHOW INDEX FROM {$table} WHERE Key_name = '{$indexName}'"));
        return count($indexes) > 0;
    }
}
