<?php

namespace App\Src\RagAssistant\Domain;

readonly class EvidenceItem
{
    /**
     * @param  list<string>  $technologies
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $label,
        public string $excerpt,
        public array $technologies = [],
        public int $score = 0,
    ) {}

    /**
     * @return array{id: string, label: string, excerpt: string}
     */
    public function citation(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'excerpt' => str($this->excerpt)->limit(280)->toString(),
        ];
    }
}
