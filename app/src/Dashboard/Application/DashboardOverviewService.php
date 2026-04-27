<?php

namespace App\Src\Dashboard\Application;

use App\Models\Opportunity;
use App\Models\OpportunityQuestion;
use App\Models\ProfessionalExperience;
use App\Models\ProfessionalProject;
use App\Models\User;

class DashboardOverviewService
{
    /**
     * @return array<string, mixed>
     */
    public function forUser(User $user): array
    {
        $recentOpportunities = Opportunity::query()
            ->whereBelongsTo($user)
            ->withCount('questions')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Opportunity $opportunity): array => [
                'id' => $opportunity->id,
                'title' => $opportunity->title,
                'company' => $opportunity->company,
                'status' => $opportunity->status,
                'questions_count' => $opportunity->questions_count,
            ]);

        return [
            'stats' => [
                'experiences' => ProfessionalExperience::query()->whereBelongsTo($user)->count(),
                'projects' => ProfessionalProject::query()->whereBelongsTo($user)->count(),
                'opportunities' => Opportunity::query()->whereBelongsTo($user)->count(),
                'pendingQuestions' => OpportunityQuestion::query()
                    ->whereRelation('opportunity', 'user_id', $user->id)
                    ->where('status', 'pending')
                    ->count(),
            ],
            'recentOpportunities' => $recentOpportunities,
        ];
    }
}
