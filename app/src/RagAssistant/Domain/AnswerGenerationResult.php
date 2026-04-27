<?php

namespace App\Src\RagAssistant\Domain;

readonly class AnswerGenerationResult
{
    /**
     * @param  list<array{id: string, label: string, excerpt: string}>  $citations
     */
    public function __construct(
        public string $answer,
        public array $citations,
        public string $provider,
        public ?string $model,
    ) {}
}
