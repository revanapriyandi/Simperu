<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    protected $fillable = [
        'kk_number',
        'head_of_family',
        'wife_name',
        'house_block',
        'address',
        'phone_1',
        'phone_2',
        'emergency_contact',
        'emergency_contact_relation',
        'house_status',
        'family_members_count',
        'license_plate_1',
        'license_plate_2',
        'vehicle_1_type',
        'vehicle_2_type',
        'status',
        'user_id',
    ];

    protected $casts = [
        'family_members_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function paymentSubmissions(): HasMany
    {
        return $this->hasMany(PaymentSubmission::class);
    }
}
