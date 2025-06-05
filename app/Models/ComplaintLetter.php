<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintLetter extends Model
{
    protected $fillable = [
        'letter_number',
        'user_id',
        'category_id',
        'subject',
        'letter_date',
        'recipient',
        'description',
        'status',
        'admin_notes',
        'pdf_path',
    ];

    protected $casts = [
        'letter_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LetterCategory::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'processed' => 'Diproses',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }
}
