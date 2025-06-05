<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TelegramNotification extends Model
{
    protected $fillable = [
        'type',
        'reference_id',
        'message',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'announcement' => 'Pengumuman',
            'payment' => 'Pembayaran',
            'complaint' => 'Pengaduan',
            'system' => 'Sistem',
            default => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'sent' => 'Terkirim',
            'failed' => 'Gagal',
            default => $this->status,
        };
    }

    public function markAsSent(): bool
    {
        return $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'error_message' => null,
        ]);
    }

    public function markAsFailed(string $errorMessage): bool
    {
        return $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
