<?php

namespace App\Domains\Conversation\Data;

final readonly class ConversationPackData
{
    /**
     * @param  array<int, PromptDefinitionData>  $prompts
     */
    public function __construct(
        public string $slug,
        public int $version,
        public bool $isActive,
        public string $title,
        public string $description,
        public string $purposeTag,
        public string $duration,
        public array $prompts,
        public PromptDefinitionData $closingPrompt,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            slug: (string) $attributes['slug'],
            version: (int) $attributes['version'],
            isActive: (bool) ($attributes['is_active'] ?? false),
            title: (string) $attributes['title'],
            description: (string) $attributes['description'],
            purposeTag: (string) ($attributes['purpose_tag'] ?? ''),
            duration: (string) $attributes['duration'],
            prompts: array_map(
                fn (array $prompt) => PromptDefinitionData::fromArray($prompt),
                $attributes['prompts'] ?? [],
            ),
            closingPrompt: PromptDefinitionData::fromArray($attributes['closing_prompt']),
        );
    }

    public function promptCount(): int
    {
        return count($this->prompts);
    }

    public function toSummaryArray(): array
    {
        return [
            'slug' => $this->slug,
            'version' => $this->version,
            'title' => $this->title,
            'description' => $this->description,
            'purposeTag' => $this->purposeTag,
            'duration' => $this->duration,
            'promptCount' => $this->promptCount(),
        ];
    }

    public function toPreviewArray(): array
    {
        return array_merge($this->toSummaryArray(), [
            'prompts' => array_map(
                fn (PromptDefinitionData $prompt) => $prompt->toArray(),
                $this->prompts,
            ),
        ]);
    }
}
