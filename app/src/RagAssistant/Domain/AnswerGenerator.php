<?php

namespace App\Src\RagAssistant\Domain;

interface AnswerGenerator
{
    public function generate(AnswerGenerationRequest $request): AnswerGenerationResult;
}
