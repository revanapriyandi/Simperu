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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['info', 'urgent', 'event', 'financial'])->default('info');
            $table->boolean('is_active')->default(true);
            $table->datetime('publish_date');
            $table->datetime('expire_date')->nullable();
            $table->boolean('send_telegram')->default(false);
            $table->timestamp('telegram_sent_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index(['type']);
            $table->index(['is_active']);
            $table->index(['publish_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
