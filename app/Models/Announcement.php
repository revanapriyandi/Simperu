<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'is_active',
        'publish_date',
        'expire_date',
        'send_telegram',
        'telegram_sent_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'send_telegram' => 'boolean',
        'publish_date' => 'datetime',
        'expire_date' => 'datetime',
        'telegram_sent_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('publish_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expire_date')
                    ->orWhere('expire_date', '>', now());
            });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('publish_date', '<=', now());
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'info' => 'Informasi',
            'urgent' => 'Penting',
            'event' => 'Acara',
            'financial' => 'Keuangan',
            default => $this->type,
        };
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expire_date && $this->expire_date < now();
    }
}
