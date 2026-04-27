<?php

namespace App\Src\RagAssistant\Infrastructure\Providers;

use App\Src\RagAssistant\Domain\AnswerGenerationRequest;
use App\Src\RagAssistant\Domain\AnswerGenerationResult;
use App\Src\RagAssistant\Domain\AnswerGenerator;
use App\Src\RagAssistant\Infrastructure\Prompt\GroundedAnswerPrompt;
use Illuminate\Http\Client\Factory as HttpFactory;
use RuntimeException;

class OpenAiAnswerGenerator implements AnswerGenerator
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly GroundedAnswerPrompt $prompt,
    ) {}

    public function generate(AnswerGenerationRequest $request): AnswerGenerationResult
    {
        $apiKey = config('services.openai.key');

        if (! is_string($apiKey) || $apiKey === '') {
            throw new RuntimeException('OPENAI_API_KEY is not configured.');
        }

        $model = (string) config('tinaaa.ai.default_model');
        $baseUrl = rtrim((string) config('services.openai.base_url'), '/');
        $payload = [
            'model' => $model,
            'instructions' => $this->prompt->instructions(),
            'input' => $this->prompt->input($request),
        ];

        $response = $this->http
            ->withToken($apiKey)
            ->acceptJson()
            ->asJson()
            ->timeout(60)
            ->post("{$baseUrl}/responses", $payload)
            ->throw()
            ->json();

        return new AnswerGenerationResult(
            answer: $this->extractText($response),
            citations: collect($request->evidence)->take(5)->map->citation()->values()->all(),
            provider: 'openai',
            model: $model,
        );
    }

    /**
     * @param  array<string, mixed>  $response
     */
    private function extractText(array $response): string
    {
        if (isset($response['output_text']) && is_string($response['output_text'])) {
            return $response['output_text'];
        }

        $text = collect($response['output'] ?? [])
            ->flatMap(fn (array $item): array => $item['content'] ?? [])
            ->pluck('text')
            ->filter()
            ->implode("\n");

        if ($text === '') {
            throw new RuntimeException('OpenAI response did not include text output.');
        }

        return $text;
    }
}
