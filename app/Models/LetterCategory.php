<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterCategory extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function complaintLetters(): HasMany
    {
        return $this->hasMany(ComplaintLetter::class, 'category_id');
    }
}
