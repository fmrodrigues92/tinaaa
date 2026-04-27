<?php

namespace App\Src\Opportunity\Application;

use App\Models\Opportunity;
use App\Models\User;

class OpportunityOverviewService
{
    /**
     * @return array<string, mixed>
     */
    public function forUser(User $user): array
    {
        $opportunities = Opportunity::query()
            ->whereBelongsTo($user)
            ->withCount('questions')
            ->with(['questions' => fn ($query) => $query->latest()])
            ->latest()
            ->get()
            ->map(fn (Opportunity $opportunity): array => [
                'id' => $opportunity->id,
                'title' => $opportunity->title,
                'company' => $opportunity->company,
                'seniority' => $opportunity->seniority,
                'offered_salary' => $opportunity->offered_salary,
                'expected_salary' => $opportunity->expected_salary,
                'status' => $opportunity->status,
                'description' => $opportunity->description,
                'requirements' => $opportunity->requirements,
                'notes' => $opportunity->notes,
                'questions_count' => $opportunity->questions_count,
                'questions' => $opportunity->questions->map(fn ($question): array => [
                    'id' => $question->id,
                    'question' => $question->question,
                    'context' => $question->context,
                    'generated_answer' => $question->generated_answer,
                    'answer_citations' => $question->answer_citations ?? [],
                    'answer_provider' => $question->answer_provider,
                    'answer_model' => $question->answer_model,
                    'answered_at' => $question->answered_at?->toDateTimeString(),
                    'status' => $question->status,
                ]),
            ]);

        return [
            'opportunities' => $opportunities,
        ];
    }
}
