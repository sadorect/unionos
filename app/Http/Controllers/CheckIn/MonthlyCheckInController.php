<?php

namespace App\Http\Controllers\CheckIn;

use App\Actions\CheckIn\SubmitMonthlyCoupleCheckIn;
use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Services\MonthlyInsightService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckIn\StoreMonthlyCoupleCheckInRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MonthlyCheckInController extends Controller
{
    public function __construct(
        private readonly MonthlyInsightService $insights,
        private readonly SubmitMonthlyCoupleCheckIn $submitMonthlyCoupleCheckIn,
    ) {
    }

    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();
        $submission = $this->insights->submissionFor($user);

        if ($submission !== null && $this->insights->isInsightReady($user)) {
            return redirect()->route('checkins.monthly.insight');
        }

        if ($submission !== null) {
            return redirect()->route('checkins.monthly.waiting');
        }

        $this->authorize('create', MonthlyCoupleCheckIn::class);

        return Inertia::render('CheckIn/MonthlyForm', [
            'month' => $this->monthPayload(),
            'privacyNote' => 'Your individual answers aren\'t shown directly.',
        ]);
    }

    public function store(StoreMonthlyCoupleCheckInRequest $request): RedirectResponse
    {
        $user = $request->user();
        $couple = $this->insights->activeCoupleFor($user);

        $this->authorize('create', MonthlyCoupleCheckIn::class);

        if ($this->insights->submissionFor($user) !== null) {
            return back()->withErrors([
                'monthly' => 'This month\'s check-in has already been submitted.',
            ]);
        }

        $this->submitMonthlyCoupleCheckIn->handle($user, $couple, $request->validated());

        if ($this->insights->isInsightReady($user)) {
            return redirect()->route('checkins.monthly.insight')
                ->with('success', 'This month\'s shared insight is ready.');
        }

        return redirect()->route('checkins.monthly.waiting')
            ->with('success', 'Your monthly check-in has been saved.');
    }

    public function waiting(Request $request): Response|RedirectResponse
    {
        $user = $request->user();
        $submission = $this->insights->submissionFor($user);

        if ($submission === null) {
            return redirect()->route('checkins.monthly.show');
        }

        if ($this->insights->isInsightReady($user)) {
            return redirect()->route('checkins.monthly.insight');
        }

        return Inertia::render('CheckIn/MonthlyWaiting', [
            'month' => $this->monthPayload(),
        ]);
    }

    public function insight(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if (! $this->insights->isInsightReady($user)) {
            return redirect()->route('checkins.monthly.waiting');
        }

        return Inertia::render('CheckIn/MonthlyInsight', [
            'month' => $this->monthPayload(),
            'cards' => $this->insights->insightCards($user),
        ]);
    }

    public function locked(Request $request): Response
    {
        $this->authorize('viewAny', MonthlyCoupleCheckIn::class);

        return Inertia::render('State/MonthClosed', [
            'month' => $this->monthPayload(),
            'hasInsight' => $this->insights->isInsightReady($request->user()),
        ]);
    }

    private function monthPayload(): array
    {
        return [
            'key' => $this->insights->currentMonth(),
            'label' => $this->insights->currentMonthLabel(),
        ];
    }
}
