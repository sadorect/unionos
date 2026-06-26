<?php

namespace App\Domains\Conversation\Services;

use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;
use Illuminate\Support\Collection;

class ConversationFlowService
{
    public function __construct(
        private readonly CoupleStateService $state,
        private readonly ConversationPackService $packs,
    ) {
    }

    public function activeCoupleFor(User $user): ?Couple
    {
        $couple = $this->state->current($user);

        if ($couple?->status !== CoupleStatus::Active) {
            return null;
        }

        return $couple;
    }

    public function belongsToUsersActiveCouple(User $user, ConversationSession $session): bool
    {
        $couple = $this->activeCoupleFor($user);

        return $couple !== null && $session->couple_id === $couple->id;
    }

    public function sessionHistoryFor(User $user, int $limit = 6): Collection
    {
        $couple = $this->activeCoupleFor($user);

        if ($couple === null) {
            return collect();
        }

        return ConversationSession::query()
            ->where('couple_id', $couple->id)
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function steps(ConversationSession $session): array
    {
        $pack = $this->packs->forSession($session);

        if ($pack === null) {
            return [];
        }

        $members = $this->membersBySlot($session->couple);
        $steps = [];

        foreach ($pack->prompts as $prompt) {
            foreach ($this->expandPrompt($prompt, $members) as $expanded) {
                $steps[] = array_merge($expanded, [
                    'index' => count($steps),
                    'kind' => 'prompt',
                ]);
            }
        }

        foreach ($this->expandPrompt($pack->closingPrompt, $members) as $expanded) {
            $steps[] = array_merge($expanded, [
                'index' => count($steps),
                'kind' => 'closing',
            ]);
        }

        return $steps;
    }

    public function currentStep(ConversationSession $session): ?array
    {
        $steps = $this->steps($session);
        $completed = $session->turns()->count();

        return $steps[$completed] ?? null;
    }

    public function completedStepCount(ConversationSession $session): int
    {
        return $session->turns()->count();
    }

    public function totalStepCount(ConversationSession $session): int
    {
        return count($this->steps($session));
    }

    public function nextTurnUserId(ConversationSession $session): ?int
    {
        return $this->currentStep($session)['user_id'] ?? null;
    }

    public function hasStarted(ConversationSession $session): bool
    {
        return $session->ground_rules_confirmed_at !== null;
    }

    public function isClosingStep(ConversationSession $session): bool
    {
        return ($this->currentStep($session)['kind'] ?? null) === 'closing';
    }

    public function isParticipantsTurn(ConversationSession $session, User $user): bool
    {
        return $session->current_turn_user_id === $user->id;
    }

    private function membersBySlot(Couple $couple): array
    {
        return CoupleMembership::query()
            ->where('couple_id', $couple->id)
            ->where('is_active', true)
            ->orderBy('slot')
            ->with('user')
            ->get()
            ->mapWithKeys(fn (CoupleMembership $membership) => [
                "slot_{$membership->slot}" => $membership->user,
            ])
            ->all();
    }

    private function expandPrompt(object $prompt, array $members): array
    {
        if ($prompt->speaker === 'both') {
            return [
                $this->resolvedPrompt($prompt, 'slot_1', $members['slot_1']),
                $this->resolvedPrompt($prompt, 'slot_2', $members['slot_2']),
            ];
        }

        return [
            $this->resolvedPrompt($prompt, $prompt->speaker, $members[$prompt->speaker]),
        ];
    }

    private function resolvedPrompt(object $prompt, string $speaker, User $user): array
    {
        return [
            'prompt_key' => $prompt->key.'.'.$speaker,
            'prompt_label' => $prompt->label,
            'prompt_text' => $prompt->text,
            'instruction' => $prompt->instruction,
            'timer_seconds' => $prompt->timerSeconds,
            'speaker' => $speaker,
            'turn_label' => $prompt->turnLabel,
            'user_id' => $user->id,
            'user_name' => $user->name,
        ];
    }
}
