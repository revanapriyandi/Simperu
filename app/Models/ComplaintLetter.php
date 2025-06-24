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
        'content',
        'priority',
        'attachments',
        'submitted_by',
        'submitted_at',
        'status',
        'admin_notes',
        'admin_response',
        'processed_by',
        'processed_at',
        'pdf_path',
        'digital_signature',
        'signature_hash',
        'signed_at',
        'signed_by',
        'approval_status',
        'approval_notes',
        'template_data',
        'barcode_path',
    ];

    protected $casts = [
        'letter_date' => 'date',
        'submitted_at' => 'datetime',
        'processed_at' => 'datetime',
        'signed_at' => 'datetime',
        'attachments' => 'array',
        'template_data' => 'array',
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
            'submitted' => 'Diajukan',
            'in_review' => 'Sedang Ditinjau',
            'in_progress' => 'Sedang Diproses',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
            default => $this->status,
        };
    }

    public function getApprovalStatusLabelAttribute(): string
    {
        return match ($this->approval_status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => $this->approval_status,
        };
    }

    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
