<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_submissions', function (Blueprint $table) {
            // Add missing fields if they don't exist
            if (!Schema::hasColumn('payment_submissions', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('receipt_path');
            }
            if (!Schema::hasColumn('payment_submissions', 'notes')) {
                $table->text('notes')->nullable()->after('admin_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('payment_submissions', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }
            if (Schema::hasColumn('payment_submissions', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
