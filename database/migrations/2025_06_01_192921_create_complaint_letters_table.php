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
        Schema::create('complaint_letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('letter_categories')->onDelete('restrict');
            $table->string('subject');
            $table->date('letter_date');
            $table->string('recipient');
            $table->text('description');
            $table->enum('status', ['pending', 'processed', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['category_id']);
            $table->index(['status']);
            $table->index(['letter_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_letters');
    }
};
