<?php

namespace App\Src\RagAssistant\Infrastructure\Providers;

use App\Src\RagAssistant\Domain\AnswerGenerationRequest;
use App\Src\RagAssistant\Domain\AnswerGenerationResult;
use App\Src\RagAssistant\Domain\AnswerGenerator;
use InvalidArgumentException;

class ConfiguredAnswerGenerator implements AnswerGenerator
{
    public function __construct(
        private readonly ExtractiveAnswerGenerator $extractive,
        private readonly OpenAiAnswerGenerator $openAi,
        private readonly OllamaAnswerGenerator $ollama,
    ) {}

    public function generate(AnswerGenerationRequest $request): AnswerGenerationResult
    {
        return match (config('tinaaa.ai.provider')) {
            'extractive' => $this->extractive->generate($request),
            'openai' => $this->openAi->generate($request),
            'ollama' => $this->ollama->generate($request),
            default => throw new InvalidArgumentException('Unsupported TINAAA_AI_PROVIDER value.'),
        };
    }
}
