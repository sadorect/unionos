<?php

namespace App\Http\Controllers\CheckIn;

use App\Actions\CheckIn\UpsertWeeklyCheckIn;
use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\CheckIn\Services\WeeklyCheckInService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckIn\StoreWeeklyCheckInRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyCheckInController extends Controller
{
    public function __construct(
        private readonly WeeklyCheckInService $checkIns,
        private readonly UpsertWeeklyCheckIn $upsertWeeklyCheckIn,
    ) {
    }

    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();
        $couple = $this->checkIns->activeCoupleFor($user);
        $checkIn = $this->checkIns->currentFor($user);

        if ($checkIn !== null) {
            $this->authorize('update', $checkIn);
        } else {
            $this->authorize('create', WeeklyCheckIn::class);
        }

        return Inertia::render('CheckIn/WeeklyForm', [
            'week' => $this->weekPayload(),
            'checkIn' => $checkIn ? $this->checkInPayload($checkIn) : null,
            'isEditing' => $checkIn !== null,
            'privacyNote' => 'Your written responses stay private.',
            'coupleId' => $couple?->getKey(),
        ]);
    }

    public function store(StoreWeeklyCheckInRequest $request): RedirectResponse
    {
        $user = $request->user();
        $couple = $this->checkIns->activeCoupleFor($user);
        $existing = $this->checkIns->currentFor($user);

        if ($existing !== null) {
            $this->authorize('update', $existing);
        } else {
            $this->authorize('create', WeeklyCheckIn::class);
        }

        $this->upsertWeeklyCheckIn->handle($user, $couple, $request->validated());

        return redirect()->route('checkins.weekly.submitted')
            ->with('success', 'This week\'s check-in has been saved.');
    }

    public function submitted(Request $request): Response|RedirectResponse
    {
        $checkIn = $this->checkIns->currentFor($request->user());

        if ($checkIn === null) {
            return redirect()->route('checkins.weekly.show');
        }

        $this->authorize('view', $checkIn);

        return Inertia::render('CheckIn/WeeklySubmitted', [
            'week' => $this->weekPayload(),
            'submittedAt' => $checkIn->updated_at?->toIso8601String(),
            'canEdit' => $this->checkIns->isEditable($checkIn),
        ]);
    }

    public function locked(Request $request): Response|RedirectResponse
    {
        $checkIn = $this->checkIns->currentFor($request->user());

        if ($checkIn !== null && $this->checkIns->isEditable($checkIn)) {
            return redirect()->route('checkins.weekly.show');
        }

        if ($checkIn !== null) {
            $this->authorize('view', $checkIn);
        } else {
            $this->authorize('viewAny', WeeklyCheckIn::class);
        }

        return Inertia::render('State/WeekClosed', [
            'week' => $this->weekPayload(),
        ]);
    }

    public function history(Request $request): Response
    {
        $this->authorize('viewAny', WeeklyCheckIn::class);

        $history = $this->checkIns->historyFor($request->user());

        return Inertia::render('CheckIn/WeeklyHistory', [
            'trendCards' => $this->checkIns->trendCardsFor($request->user()),
            'history' => [
                'data' => $history->getCollection()->map(fn (WeeklyCheckIn $checkIn) => $this->historyItemPayload($checkIn))->all(),
                'links' => $history->linkCollection()->toArray(),
            ],
            'hasCurrentWeek' => $this->checkIns->currentFor($request->user()) !== null,
        ]);
    }

    private function weekPayload(): array
    {
        $start = $this->checkIns->currentWeekStart();
        $end = $this->checkIns->currentWeekEnd();

        return [
            'label' => 'Week of '.$this->checkIns->weekLabel($start),
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
        ];
    }

    private function checkInPayload(WeeklyCheckIn $checkIn): array
    {
        return [
            'emotionalAvailability' => $checkIn->emotional_availability,
            'communicationEffort' => $checkIn->communication_effort,
            'stressLevel' => $checkIn->stress_level,
            'appreciationText' => $checkIn->appreciation_text ?? '',
            'struggleText' => $checkIn->struggle_text ?? '',
            'intentionText' => $checkIn->intention_text ?? '',
        ];
    }

    private function historyItemPayload(WeeklyCheckIn $checkIn): array
    {
        return [
            'id' => $checkIn->getKey(),
            'weekLabel' => 'Week of '.$this->checkIns->weekLabel($checkIn->week_start_date),
            'savedAt' => $checkIn->updated_at?->toIso8601String(),
            'emotionalAvailability' => $checkIn->emotional_availability,
            'communicationEffort' => $checkIn->communication_effort,
            'stressLevel' => $checkIn->stress_level,
            'appreciationText' => $checkIn->appreciation_text,
            'struggleText' => $checkIn->struggle_text,
            'intentionText' => $checkIn->intention_text,
        ];
    }
}
