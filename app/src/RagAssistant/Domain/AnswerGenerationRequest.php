<?php

namespace App\Src\RagAssistant\Domain;

use App\Models\Opportunity;
use App\Models\OpportunityQuestion;

readonly class AnswerGenerationRequest
{
    /**
     * @param  list<EvidenceItem>  $evidence
     */
    public function __construct(
        public Opportunity $opportunity,
        public OpportunityQuestion $question,
        public array $evidence,
    ) {}
}
