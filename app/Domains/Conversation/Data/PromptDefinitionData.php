<?php

namespace App\Domains\Conversation\Data;

final readonly class PromptDefinitionData
{
    public function __construct(
        public string $key,
        public string $label,
        public string $speaker,
        public string $turnLabel,
        public string $text,
        public string $instruction,
        public ?int $timerSeconds,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            key: (string) $attributes['key'],
            label: (string) ($attributes['label'] ?? ''),
            speaker: (string) $attributes['speaker'],
            turnLabel: (string) $attributes['turn_label'],
            text: (string) $attributes['text'],
            instruction: (string) ($attributes['instruction'] ?? ''),
            timerSeconds: isset($attributes['timer_seconds']) ? (int) $attributes['timer_seconds'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'speaker' => $this->speaker,
            'turnLabel' => $this->turnLabel,
            'text' => $this->text,
            'instruction' => $this->instruction,
            'timerSeconds' => $this->timerSeconds,
        ];
    }
}
