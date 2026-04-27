<?php

namespace App\Src\RagAssistant\Infrastructure\Prompt;

use App\Src\RagAssistant\Domain\AnswerGenerationRequest;
use App\Src\RagAssistant\Domain\EvidenceItem;

class GroundedAnswerPrompt
{
    public function instructions(): string
    {
        return <<<'TEXT'
You are TINAAA, a grounded job-application assistant.
Answer in Portuguese unless the job question is clearly in another language.
Use only the provided professional evidence.
Do not invent roles, dates, employers, metrics, technologies, or achievements.
If the evidence is partial, answer honestly and explain the closest real experience.
Write in first person, with a professional and natural tone.
End with a short "Evidências usadas" section listing the evidence labels that support the answer.
TEXT;
    }

    public function input(AnswerGenerationRequest $request): string
    {
        $opportunity = $request->opportunity;
        $question = $request->question;

        $evidence = collect($request->evidence)
            ->map(fn (EvidenceItem $item, int $index): string => sprintf(
                "[%d] %s\nType: %s\nTechnologies: %s\nEvidence:\n%s",
                $index + 1,
                $item->label,
                $item->type,
                implode(', ', $item->technologies),
                $item->excerpt,
            ))
            ->implode("\n\n");

        return <<<TEXT
Job opportunity:
- Title: {$opportunity->title}
- Company: {$opportunity->company}
- Seniority: {$opportunity->seniority}
- Requirements: {$opportunity->requirements}
- Description: {$opportunity->description}
- Notes: {$opportunity->notes}

Question:
{$question->question}

Question context:
{$question->context}

Professional evidence:
{$evidence}
TEXT;
    }
}
