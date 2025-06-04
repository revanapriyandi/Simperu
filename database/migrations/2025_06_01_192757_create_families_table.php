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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('kk_number', 20)->unique();
            $table->string('head_of_family');
            $table->string('wife_name')->nullable();
            $table->string('house_block', 10);
            $table->string('phone_1', 20)->nullable();
            $table->string('phone_2', 20)->nullable();
            $table->enum('house_status', ['owner', 'tenant', 'family'])->default('owner');
            $table->integer('family_members_count')->default(1);
            $table->string('license_plate_1', 15)->nullable();
            $table->string('license_plate_2', 15)->nullable();
            $table->enum('status', ['active', 'inactive', 'moved'])->default('active');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['kk_number']);
            $table->index(['house_block']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
