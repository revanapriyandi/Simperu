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
        Schema::table('complaint_letters', function (Blueprint $table) {
            // Add new fields for better complaint management
            $table->text('content')->after('description'); // Rich text content
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('content');
            $table->json('attachments')->nullable()->after('priority'); // Store multiple file paths
            $table->foreignId('submitted_by')->after('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('submitted_at')->nullable()->after('submitted_by');
            $table->text('admin_response')->nullable()->after('admin_notes');
            $table->foreignId('processed_by')->nullable()->after('admin_response')->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable()->after('processed_by');

            // Update status enum to match the resource
            $table->dropColumn('status');
        });

        // Add new status column with updated values
        Schema::table('complaint_letters', function (Blueprint $table) {
            $table->enum('status', ['submitted', 'in_review', 'in_progress', 'resolved', 'closed'])->default('submitted')->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_letters', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['processed_by']);
            $table->dropColumn([
                'content',
                'priority',
                'attachments',
                'submitted_by',
                'submitted_at',
                'admin_response',
                'processed_by',
                'processed_at',
                'status'
            ]);
        });

        // Restore original status
        Schema::table('complaint_letters', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processed', 'in_progress', 'completed', 'rejected'])->default('pending');
        });
    }
};
