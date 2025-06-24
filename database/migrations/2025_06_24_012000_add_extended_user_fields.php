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
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->date('birth_date')->nullable()->after('nik');
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable()->after('birth_date');
            $table->string('occupation', 100)->nullable()->after('gender');
            
            // Housing Information
            $table->enum('house_status', ['owner', 'tenant', 'family'])->default('owner')->after('kk_number');
            
            // Emergency Contact
            $table->string('emergency_contact', 20)->nullable()->after('phone');
            $table->string('emergency_contact_relation', 50)->nullable()->after('emergency_contact');
            
            // Vehicle Information
            $table->string('vehicle_1_plate', 15)->nullable()->after('house_status');
            $table->string('vehicle_1_type', 50)->nullable()->after('vehicle_1_plate');
            $table->string('vehicle_2_plate', 15)->nullable()->after('vehicle_1_type');
            $table->string('vehicle_2_type', 50)->nullable()->after('vehicle_2_plate');
            
            // Notification Preferences
            $table->boolean('notify_announcements')->default(true)->after('telegram_chat_id');
            $table->boolean('notify_financial')->default(true)->after('notify_announcements');
            $table->boolean('notify_events')->default(true)->after('notify_financial');
            $table->boolean('notify_security')->default(true)->after('notify_events');
            
            // Additional Notes
            $table->text('additional_notes')->nullable()->after('notify_security');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'gender',
                'occupation',
                'house_status',
                'emergency_contact',
                'emergency_contact_relation',
                'vehicle_1_plate',
                'vehicle_1_type',
                'vehicle_2_plate',
                'vehicle_2_type',
                'notify_announcements',
                'notify_financial',
                'notify_events',
                'notify_security',
                'additional_notes',
            ]);
        });
    }
};
