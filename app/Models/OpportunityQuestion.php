<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'opportunity_id',
    'question',
    'context',
    'generated_answer',
    'answer_citations',
    'answer_provider',
    'answer_model',
    'answered_at',
    'status',
])]
class OpportunityQuestion extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'answer_citations' => 'array',
            'answered_at' => 'datetime',
        ];
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }
}
