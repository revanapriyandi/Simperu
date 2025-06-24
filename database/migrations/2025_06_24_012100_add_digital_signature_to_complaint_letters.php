<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('complaint_letters', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('complaint_letters', 'digital_signature')) {
                $table->text('digital_signature')->nullable()->after('pdf_path');
            }
            if (!Schema::hasColumn('complaint_letters', 'signature_hash')) {
                $table->string('signature_hash', 255)->nullable()->after('digital_signature');
            }
            if (!Schema::hasColumn('complaint_letters', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('signature_hash');
            }
            if (!Schema::hasColumn('complaint_letters', 'signed_by')) {
                $table->foreignId('signed_by')->nullable()->after('signed_at')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('complaint_letters', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('signed_by');
            }
            if (!Schema::hasColumn('complaint_letters', 'approval_notes')) {
                $table->text('approval_notes')->nullable()->after('approval_status');
            }
            if (!Schema::hasColumn('complaint_letters', 'template_data')) {
                $table->json('template_data')->nullable()->after('approval_notes');
            }
            if (!Schema::hasColumn('complaint_letters', 'barcode_path')) {
                $table->string('barcode_path')->nullable()->after('template_data');
            }
        });

        // Add indexes if they don't exist
        try {
            DB::statement('CREATE INDEX IF NOT EXISTS complaint_letters_approval_status_index ON complaint_letters (approval_status)');
            DB::statement('CREATE INDEX IF NOT EXISTS complaint_letters_signed_at_index ON complaint_letters (signed_at)');
        } catch (\Exception $e) {
            // Indexes might already exist, ignore error
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_letters', function (Blueprint $table) {
            // Drop foreign key constraints first
            if (Schema::hasColumn('complaint_letters', 'signed_by')) {
                $table->dropForeign(['signed_by']);
            }

            // Drop columns if they exist
            $columnsToRemove = [
                'digital_signature',
                'signature_hash',
                'signed_at',
                'signed_by',
                'approval_status',
                'approval_notes',
                'template_data',
                'barcode_path'
            ];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('complaint_letters', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
