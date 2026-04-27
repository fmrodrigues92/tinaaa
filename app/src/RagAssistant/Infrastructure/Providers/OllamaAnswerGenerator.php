<?php

namespace App\Src\RagAssistant\Infrastructure\Providers;

use App\Src\RagAssistant\Domain\AnswerGenerationRequest;
use App\Src\RagAssistant\Domain\AnswerGenerationResult;
use App\Src\RagAssistant\Domain\AnswerGenerator;
use App\Src\RagAssistant\Infrastructure\Prompt\GroundedAnswerPrompt;
use Illuminate\Http\Client\Factory as HttpFactory;
use RuntimeException;

class OllamaAnswerGenerator implements AnswerGenerator
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly GroundedAnswerPrompt $prompt,
    ) {}

    public function generate(AnswerGenerationRequest $request): AnswerGenerationResult
    {
        $baseUrl = rtrim((string) config('tinaaa.ai.ollama.base_url'), '/');
        $model = (string) config('tinaaa.ai.ollama.model');

        $response = $this->http
            ->acceptJson()
            ->asJson()
            ->timeout(120)
            ->post("{$baseUrl}/api/generate", [
                'model' => $model,
                'stream' => false,
                'prompt' => $this->prompt->instructions()."\n\n".$this->prompt->input($request),
            ])
            ->throw()
            ->json();

        if (! isset($response['response']) || ! is_string($response['response'])) {
            throw new RuntimeException('Ollama response did not include text output.');
        }

        return new AnswerGenerationResult(
            answer: $response['response'],
            citations: collect($request->evidence)->take(5)->map->citation()->values()->all(),
            provider: 'ollama',
            model: $model,
        );
    }
}
