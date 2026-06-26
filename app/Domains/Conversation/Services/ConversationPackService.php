<?php

namespace App\Domains\Conversation\Services;

use App\Domains\Conversation\Data\ConversationPackData;
use App\Domains\Conversation\Models\ConversationSession;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class ConversationPackService
{
    public function all(): array
    {
        return array_map(
            fn (ConversationPackData $pack) => $pack->toSummaryArray(),
            $this->allActive(),
        );
    }

    public function find(string $slug): array
    {
        $pack = $this->active($slug);

        if ($pack === null) {
            throw new InvalidArgumentException('Unknown conversation pack.');
        }

        return $pack->toPreviewArray();
    }

    public function allActive(): array
    {
        $packs = [];

        foreach (array_keys(config('unionos_content.conversation_packs', [])) as $slug) {
            $pack = $this->active($slug);

            if ($pack !== null) {
                $packs[] = $pack;
            }
        }

        return $packs;
    }

    public function active(string $slug): ?ConversationPackData
    {
        $activeVersion = Arr::get(config('unionos_content.conversation_packs', []), "{$slug}.active_version");

        if ($activeVersion === null) {
            return null;
        }

        return $this->version($slug, (int) $activeVersion);
    }

    public function version(string $slug, int $version): ?ConversationPackData
    {
        $attributes = Arr::get(config('unionos_content.conversation_packs', []), "{$slug}.versions.{$version}");

        if (! is_array($attributes)) {
            return null;
        }

        return ConversationPackData::fromArray([
            'slug' => $attributes['slug'],
            'version' => $attributes['version'],
            'is_active' => $attributes['is_active'] ?? false,
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'purpose_tag' => $attributes['purpose_tag'] ?? '',
            'duration' => $attributes['duration'],
            'prompts' => array_map(
                fn (array $prompt) => $this->decoratePrompt($prompt),
                $attributes['prompts'] ?? [],
            ),
            'closing_prompt' => $this->decoratePrompt($attributes['closing_prompt']),
        ]);
    }

    public function forSession(ConversationSession $session): ?ConversationPackData
    {
        return $this->version($session->pack_type, $session->pack_version);
    }

    public function activeSlugs(): array
    {
        return array_values(array_map(
            fn (ConversationPackData $pack) => $pack->slug,
            $this->allActive(),
        ));
    }

    public function sessionUsesNonActiveVersion(ConversationSession $session): bool
    {
        $active = $this->active($session->pack_type);

        return $active !== null && $active->version !== $session->pack_version;
    }

    public function titleFor(string $slug, ?int $version = null): string
    {
        $pack = $version === null
            ? $this->active($slug)
            : $this->version($slug, $version);

        return $pack?->title ?? $slug;
    }

    public function preview(string $slug): ?array
    {
        return $this->active($slug)?->toPreviewArray();
    }

    public function toneRules(): array
    {
        return config('unionos_content.tone_rules', []);
    }

    private function decoratePrompt(array $prompt): array
    {
        return array_merge($prompt, [
            'turn_label' => match ($prompt['speaker']) {
                'slot_1' => 'Partner A',
                'slot_2' => 'Partner B',
                default => 'Both',
            },
        ]);
    }
}
