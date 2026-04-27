<?php

namespace App\Src\RagAssistant\Infrastructure\Providers;

use App\Src\RagAssistant\Domain\AnswerGenerationRequest;
use App\Src\RagAssistant\Domain\AnswerGenerationResult;
use App\Src\RagAssistant\Domain\AnswerGenerator;

class ExtractiveAnswerGenerator implements AnswerGenerator
{
    public function generate(AnswerGenerationRequest $request): AnswerGenerationResult
    {
        $topEvidence = collect($request->evidence)->take(3);

        $answer = $topEvidence->isEmpty()
            ? 'Ainda não encontrei evidências profissionais suficientes para responder essa pergunta com segurança. O ideal é cadastrar experiências, projetos e resultados mais específicos antes de usar esta resposta.'
            : $this->answerFromEvidence($request);

        return new AnswerGenerationResult(
            answer: $answer,
            citations: collect($request->evidence)->take(5)->map->citation()->values()->all(),
            provider: 'extractive',
            model: null,
        );
    }

    private function answerFromEvidence(AnswerGenerationRequest $request): string
    {
        $evidence = collect($request->evidence)->take(3);
        $labels = $evidence->pluck('label')->implode('; ');
        $summary = $evidence
            ->map(fn ($item): string => str($item->excerpt)->squish()->limit(420)->toString())
            ->implode("\n\n");

        return <<<TEXT
Com base nas minhas experiências reais, eu responderia destacando os pontos que têm maior aderência à pergunta "{$request->question->question}".

Minha trajetória inclui evidências diretamente relacionadas em {$labels}. Nesses contextos, trabalhei com problemas reais, decisões técnicas e entregas que ajudam a sustentar a resposta sem recorrer a afirmações genéricas.

Resumo das evidências mais relevantes:
{$summary}

Evidências usadas:
- {$labels}
TEXT;
    }
}
