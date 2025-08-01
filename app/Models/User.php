<?php

namespace App\Models;

use Filament\Panel;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAvatar, MustVerifyEmail, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'nik',
        'password',
        'phone',
        'house_number',
        'kk_number',
        'role',
        'is_active',
        'telegram_chat_id',
        // Additional fields for extended registration
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'birth_date' => 'date',
            'notify_announcements' => 'boolean',
            'notify_financial' => 'boolean',
            'notify_events' => 'boolean',
            'notify_security' => 'boolean',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getFirstMediaUrl('avatar');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'resident') {
            return $this->role === 'resident';
        }

        return false;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    /**
     * Get the family associated with the user.
     */
    public function family()
    {
        return $this->hasOne(Family::class);
    }

    /**
     * Get complaint letters submitted by the user.
     */
    public function complaintLetters()
    {
        return $this->hasMany(ComplaintLetter::class);
    }

    /**
     * Get payment submissions by the user.
     */
    public function paymentSubmissions()
    {
        return $this->hasMany(PaymentSubmission::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is resident.
     */
    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    /**
     * Get user's telegram notifications.
     */
    public function telegramNotifications()
    {
        return $this->hasMany(TelegramNotification::class);
    }
}
