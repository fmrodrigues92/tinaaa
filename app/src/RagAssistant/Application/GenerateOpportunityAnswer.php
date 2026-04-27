<?php

namespace App\Src\RagAssistant\Application;

use App\Models\OpportunityQuestion;
use App\Models\User;
use App\Src\RagAssistant\Domain\AnswerGenerationRequest;
use App\Src\RagAssistant\Domain\AnswerGenerationResult;
use App\Src\RagAssistant\Domain\AnswerGenerator;
use Illuminate\Support\Carbon;

class GenerateOpportunityAnswer
{
    public function __construct(
        private readonly ProfileEvidenceRetriever $retriever,
        private readonly AnswerGenerator $generator,
    ) {}

    public function handle(User $user, OpportunityQuestion $question): AnswerGenerationResult
    {
        $question->loadMissing('opportunity');

        abort_unless($question->opportunity->user_id === $user->id, 404);

        $evidence = $this->retriever->forQuestion($user, $question);

        $result = $this->generator->generate(new AnswerGenerationRequest(
            opportunity: $question->opportunity,
            question: $question,
            evidence: $evidence,
        ));

        $question->forceFill([
            'generated_answer' => $result->answer,
            'answer_citations' => $result->citations,
            'answer_provider' => $result->provider,
            'answer_model' => $result->model,
            'answered_at' => Carbon::now(),
            'status' => 'answered',
        ])->save();

        return $result;
    }
}
