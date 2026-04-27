<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'professional_experience_id',
    'name',
    'problem',
    'solution',
    'impact',
    'technical_decisions',
    'technologies',
])]
class ProfessionalProject extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
        ];
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(ProfessionalExperience::class, 'professional_experience_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
