<?php

namespace App\Http\Controllers\Couple;

use App\Actions\Couple\CreateCouple;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Services\CoupleAccessService;
use App\Domains\Couple\Services\CoupleStateService;
use App\Domains\Notification\Services\NextStepNudgeService;
use App\Domains\Notification\Services\NotificationPreferenceService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CoupleController extends Controller
{
    public function __construct(
        private readonly CoupleStateService $state,
        private readonly CoupleAccessService $access,
        private readonly CreateCouple $createCouple,
        private readonly NextStepNudgeService $nudges,
        private readonly NotificationPreferenceService $preferences,
    ) {
    }

    public function home(Request $request): RedirectResponse
    {
        return redirect($this->state->redirectPathFor($request->user()));
    }

    public function create(Request $request): Response|RedirectResponse
    {
        if (! $this->state->canCreateCouple($request->user())) {
            return redirect($this->state->redirectPathFor($request->user()));
        }

        $this->authorize('create', Couple::class);

        return Inertia::render('Couple/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Couple::class);
        $this->createCouple->handle($request->user());

        return redirect()->route('couple.pending')
            ->with('success', 'Your couple space has been created.');
    }

    public function pending(Request $request): Response|RedirectResponse
    {
        $couple = $this->state->current($request->user());

        if ($couple?->status !== CoupleStatus::Pending) {
            return redirect($this->state->redirectPathFor($request->user()));
        }

        $this->authorize('view', $couple);

        $invite = $this->access->currentInvite($couple);

        return Inertia::render('Couple/Pending', [
            'invite' => $invite ? $this->invitePayload($invite) : null,
        ]);
    }

    public function show(Request $request): Response|RedirectResponse
    {
        $couple = $this->state->current($request->user());

        if ($couple?->status !== CoupleStatus::Active) {
            return redirect($this->state->redirectPathFor($request->user()));
        }

        $this->authorize('view', $couple);

        return Inertia::render('Couple/ActiveHome', [
            'linkedAt' => optional($couple->updated_at)->toDateString(),
            'nextStep' => $this->nudges->forUser($request->user()),
            'notificationsPaused' => $this->preferences->forUser($request->user())->quiet_mode_enabled,
        ]);
    }

    public function dissolved(Request $request): Response|RedirectResponse
    {
        $couple = $this->state->current($request->user());

        if ($couple?->status !== CoupleStatus::Dissolved) {
            return redirect($this->state->redirectPathFor($request->user()));
        }

        $this->authorize('view', $couple);

        return Inertia::render('Couple/Dissolved');
    }

    private function invitePayload(object $invite): array
    {
        return [
            'email' => $invite->email,
            'emailMasked' => $this->maskEmail($invite->email),
            'expiresAt' => $invite->expires_at->toIso8601String(),
            'link' => route('invite.accept.show', $invite->token, false),
        ];
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);

        return substr($local, 0, 1).str_repeat('*', max(strlen($local) - 1, 2)).'@'.$domain;
    }
}
