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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->enum('type', ['income', 'expense']);
            $table->string('category', 100);
            $table->foreignId('fee_type_id')->nullable()->constrained('fee_types')->onDelete('set null');
            $table->foreignId('family_id')->nullable()->constrained('families')->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->string('reference_number', 100)->nullable();
            $table->string('receipt_path', 500)->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index(['transaction_date']);
            $table->index(['type']);
            $table->index(['category']);
            $table->index(['status']);
            $table->index(['family_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
