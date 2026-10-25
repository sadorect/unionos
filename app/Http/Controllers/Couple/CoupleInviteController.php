<?php

namespace App\Http\Controllers\Couple;

use App\Actions\Couple\AcceptCoupleInvite;
use App\Actions\Couple\RegenerateCoupleInvite;
use App\Actions\Couple\SendCoupleInvite;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\CoupleInvite;
use App\Domains\Couple\Services\CoupleAccessService;
use App\Domains\Couple\Services\CoupleStateService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CoupleInviteController extends Controller
{
    public function __construct(
        private readonly CoupleStateService $state,
        private readonly CoupleAccessService $access,
        private readonly RegenerateCoupleInvite $regenerateCoupleInvite,
        private readonly SendCoupleInvite $sendCoupleInvite,
        private readonly AcceptCoupleInvite $acceptCoupleInvite,
    ) {
    }

    public function create(Request $request): Response|RedirectResponse
    {
        $couple = $this->state->current($request->user());

        if ($couple?->status !== CoupleStatus::Pending) {
            return redirect($this->state->redirectPathFor($request->user()));
        }

        Gate::authorize('create', [CoupleInvite::class, $couple]);

        $invite = $this->access->currentInvite($couple);

        return Inertia::render('Couple/Invite', [
            'currentInvite' => $invite ? $this->invitePayload($invite) : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $couple = $this->state->current($request->user());

        if ($couple === null) {
            return redirect()->route('couple.create');
        }

        Gate::authorize('create', [CoupleInvite::class, $couple]);

        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (strcasecmp($validated['email'], $request->user()->email) === 0) {
            return back()->withErrors([
                'email' => 'You cannot send an invitation to your own email address.',
            ]);
        }

        $existing = $this->access->currentInvite($couple);

        if ($existing !== null) {
            // An invite is already outstanding. If it targets the same address,
            // treat "Send" as a re-send rather than a dead-end error.
            if (strcasecmp($existing->email, $validated['email']) === 0) {
                $this->sendCoupleInvite->handle($existing);

                return redirect()->route('couple.pending')
                    ->with('success', 'Invitation re-sent.');
            }

            return back()->withErrors([
                'email' => 'An active invitation already exists. Cancel it before inviting a different email address.',
            ]);
        }

        $this->regenerateCoupleInvite->handle($couple, $validated['email']);

        return redirect()->route('couple.pending')
            ->with('success', 'Invitation sent.');
    }

    public function regenerate(Request $request): RedirectResponse
    {
        $couple = $this->state->current($request->user());
        $invite = $couple ? $this->access->currentInvite($couple) : null;

        if ($invite === null) {
            return redirect()->route('couple.invite.create');
        }

        Gate::authorize('update', $invite);
        $this->regenerateCoupleInvite->handle($invite->couple, $invite->email);

        return redirect()->route('couple.pending')
            ->with('success', 'A new invitation link has been created.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $couple = $this->state->current($request->user());
        $invite = $couple ? $this->access->currentInvite($couple) : null;

        if ($invite === null) {
            return redirect()->route('couple.pending');
        }

        Gate::authorize('delete', $invite);
        $invite->delete();

        return redirect()->route('couple.pending')
            ->with('success', 'The invitation has been cancelled.');
    }

    public function show(Request $request, string $token): Response
    {
        $invite = CoupleInvite::query()
            ->where('token', $token)
            ->first();

        if ($invite === null || ! $invite->isUsable()) {
            return Inertia::render('State/ExpiredInvite');
        }

        if (! $request->user()) {
            return Inertia::render('Couple/AcceptInvite', [
                'state' => 'guest',
                'invite' => $this->invitePayload($invite),
                'loginUrl' => route('login', ['invite' => $token], false),
                'registerUrl' => route('register', ['invite' => $token], false),
            ]);
        }

        if (! $this->state->canCreateCouple($request->user())) {
            return Inertia::render('State/AlreadyLinked');
        }

        return Inertia::render('Couple/AcceptInvite', [
            'state' => 'ready',
            'invite' => $this->invitePayload($invite),
            'acceptUrl' => route('invite.accept', $token, false),
        ]);
    }

    public function accept(Request $request, string $token): RedirectResponse
    {
        $invite = CoupleInvite::query()
            ->where('token', $token)
            ->first();

        if ($invite === null) {
            return redirect()->route('state.expired-invite');
        }

        Gate::authorize('accept', $invite);
        $this->acceptCoupleInvite->handle($request->user(), $invite);

        return redirect()->route('couple.show')
            ->with('success', 'Your couple space is now active.');
    }

    private function invitePayload(CoupleInvite $invite): array
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
