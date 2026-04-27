<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'role',
    'company',
    'started_on',
    'ended_on',
    'is_current',
    'context',
    'responsibilities',
    'results',
    'technologies',
])]
class ProfessionalExperience extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'started_on' => 'date',
            'ended_on' => 'date',
            'is_current' => 'boolean',
            'technologies' => 'array',
        ];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(ProfessionalProject::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
