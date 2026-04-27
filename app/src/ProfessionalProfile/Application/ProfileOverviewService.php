<?php

namespace App\Src\ProfessionalProfile\Application;

use App\Models\ProfessionalExperience;
use App\Models\ProfessionalProject;
use App\Models\User;

class ProfileOverviewService
{
    /**
     * @return array<string, mixed>
     */
    public function forUser(User $user): array
    {
        $experiences = ProfessionalExperience::query()
            ->whereBelongsTo($user)
            ->withCount('projects')
            ->latest('started_on')
            ->latest()
            ->get()
            ->map(fn (ProfessionalExperience $experience): array => [
                'id' => $experience->id,
                'role' => $experience->role,
                'company' => $experience->company,
                'started_on' => $experience->started_on?->toDateString(),
                'ended_on' => $experience->ended_on?->toDateString(),
                'is_current' => $experience->is_current,
                'context' => $experience->context,
                'responsibilities' => $experience->responsibilities,
                'results' => $experience->results,
                'technologies' => $experience->technologies ?? [],
                'projects_count' => $experience->projects_count,
            ]);

        $projects = ProfessionalProject::query()
            ->whereBelongsTo($user)
            ->with('experience:id,role,company')
            ->latest()
            ->get()
            ->map(fn (ProfessionalProject $project): array => [
                'id' => $project->id,
                'professional_experience_id' => $project->professional_experience_id,
                'name' => $project->name,
                'problem' => $project->problem,
                'solution' => $project->solution,
                'impact' => $project->impact,
                'technical_decisions' => $project->technical_decisions,
                'technologies' => $project->technologies ?? [],
                'experience' => $project->experience ? [
                    'role' => $project->experience->role,
                    'company' => $project->experience->company,
                ] : null,
            ]);

        return [
            'experiences' => $experiences,
            'projects' => $projects,
            'experienceOptions' => $experiences->map(fn (array $experience): array => [
                'id' => $experience['id'],
                'label' => "{$experience['role']} at {$experience['company']}",
            ])->values(),
        ];
    }
}
