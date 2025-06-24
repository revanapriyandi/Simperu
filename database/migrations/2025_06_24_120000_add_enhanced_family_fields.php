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
        Schema::table('families', function (Blueprint $table) {
            // Emergency contact information
            $table->string('emergency_contact', 20)->nullable()->after('phone_2');
            $table->string('emergency_contact_relation', 50)->nullable()->after('emergency_contact');
            
            // Vehicle type information
            $table->string('vehicle_1_type', 20)->nullable()->after('license_plate_1');
            $table->string('vehicle_2_type', 20)->nullable()->after('license_plate_2');
            
            // Address information
            $table->text('address')->nullable()->after('house_block');
            
            // Update house_status enum to match context requirements
            $table->string('house_status', 50)->default('Milik Sendiri')->change();
        });

        Schema::table('family_members', function (Blueprint $table) {
            // Add missing fields for family members
            $table->string('education', 50)->nullable()->after('occupation');
            $table->string('phone', 20)->nullable()->after('education');
            $table->text('notes')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('families', function (Blueprint $table) {
            $table->dropColumn([
                'emergency_contact',
                'emergency_contact_relation',
                'vehicle_1_type',
                'vehicle_2_type',
                'address'
            ]);
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn([
                'education',
                'phone',
                'notes'
            ]);
        });
    }
};
