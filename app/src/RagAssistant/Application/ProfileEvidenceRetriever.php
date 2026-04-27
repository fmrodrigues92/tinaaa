<?php

namespace App\Src\RagAssistant\Application;

use App\Models\OpportunityQuestion;
use App\Models\ProfessionalExperience;
use App\Models\ProfessionalProject;
use App\Models\User;
use App\Src\RagAssistant\Domain\EvidenceItem;

class ProfileEvidenceRetriever
{
    /**
     * @return list<EvidenceItem>
     */
    public function forQuestion(User $user, OpportunityQuestion $question, int $limit = 8): array
    {
        $question->loadMissing('opportunity');

        $query = implode(' ', array_filter([
            $question->question,
            $question->context,
            $question->opportunity->title,
            $question->opportunity->company,
            $question->opportunity->seniority,
            $question->opportunity->description,
            $question->opportunity->requirements,
            $question->opportunity->notes,
        ]));

        $items = [
            ...$this->experienceEvidence($user),
            ...$this->projectEvidence($user),
        ];

        return collect($items)
            ->map(fn (EvidenceItem $item): EvidenceItem => new EvidenceItem(
                id: $item->id,
                type: $item->type,
                label: $item->label,
                excerpt: $item->excerpt,
                technologies: $item->technologies,
                score: $this->score($query, $item),
            ))
            ->sortByDesc(fn (EvidenceItem $item): array => [$item->score, strlen($item->excerpt)])
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * @return list<EvidenceItem>
     */
    private function experienceEvidence(User $user): array
    {
        return ProfessionalExperience::query()
            ->whereBelongsTo($user)
            ->latest('started_on')
            ->get()
            ->map(fn (ProfessionalExperience $experience): EvidenceItem => new EvidenceItem(
                id: "experience:{$experience->id}",
                type: 'experience',
                label: "{$experience->role} at {$experience->company}",
                excerpt: implode("\n", array_filter([
                    $experience->context,
                    $experience->responsibilities,
                    $experience->results,
                ])),
                technologies: $experience->technologies ?? [],
            ))
            ->all();
    }

    /**
     * @return list<EvidenceItem>
     */
    private function projectEvidence(User $user): array
    {
        return ProfessionalProject::query()
            ->whereBelongsTo($user)
            ->with('experience:id,role,company')
            ->latest()
            ->get()
            ->map(fn (ProfessionalProject $project): EvidenceItem => new EvidenceItem(
                id: "project:{$project->id}",
                type: 'project',
                label: $project->experience
                    ? "{$project->name} ({$project->experience->role} at {$project->experience->company})"
                    : $project->name,
                excerpt: implode("\n", array_filter([
                    $project->problem,
                    $project->solution,
                    $project->impact,
                    $project->technical_decisions,
                ])),
                technologies: $project->technologies ?? [],
            ))
            ->all();
    }

    private function score(string $query, EvidenceItem $item): int
    {
        $haystack = str($item->label.' '.$item->excerpt.' '.implode(' ', $item->technologies))->lower()->toString();
        $terms = collect(preg_split('/[^\pL\pN+#.]+/u', str($query)->lower()->toString()) ?: [])
            ->map(fn (string $term): string => trim($term))
            ->filter(fn (string $term): bool => mb_strlen($term) >= 3)
            ->unique();

        return $terms->sum(fn (string $term): int => str_contains($haystack, $term) ? 1 : 0);
    }
}
