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
            // Digital Signature Fields
            $table->text('digital_signature')->nullable()->after('pdf_path');
            $table->string('signature_hash', 255)->nullable()->after('digital_signature');
            $table->timestamp('signed_at')->nullable()->after('signature_hash');
            $table->foreignId('signed_by')->nullable()->after('signed_at')->constrained('users')->onDelete('set null');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('signed_by');
            $table->text('approval_notes')->nullable()->after('approval_status');
            
            // Letter Template Related
            $table->json('template_data')->nullable()->after('approval_notes');
            $table->string('barcode_path')->nullable()->after('template_data');
            
            // Add index for better performance
            $table->index(['approval_status']);
            $table->index(['signed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_letters', function (Blueprint $table) {
            $table->dropForeign(['signed_by']);
            $table->dropIndex(['approval_status']);
            $table->dropIndex(['signed_at']);
            $table->dropColumn([
                'digital_signature',
                'signature_hash',
                'signed_at',
                'signed_by',
                'approval_status',
                'approval_notes',
                'template_data',
                'barcode_path'
            ]);
        });
    }
};
