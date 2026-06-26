<?php

namespace App\Http\Controllers\Settings;

use App\Domains\Notification\Services\NotificationPreferenceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateNotificationPreferencesRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationSettingsController extends Controller
{
    public function __construct(
        private readonly NotificationPreferenceService $preferences,
    ) {
    }

    public function show(Request $request): Response
    {
        $preference = $this->preferences->forUser($request->user());

        return Inertia::render('Settings/Notifications', [
            'preferences' => $this->preferences->payload($preference),
            'options' => [
                'weekdays' => [
                    ['value' => 1, 'label' => 'Monday'],
                    ['value' => 2, 'label' => 'Tuesday'],
                    ['value' => 3, 'label' => 'Wednesday'],
                    ['value' => 4, 'label' => 'Thursday'],
                    ['value' => 5, 'label' => 'Friday'],
                    ['value' => 6, 'label' => 'Saturday'],
                    ['value' => 7, 'label' => 'Sunday'],
                ],
                'hours' => collect(range(8, 21))->map(fn (int $hour) => [
                    'value' => $hour,
                    'label' => sprintf('%02d:00', $hour),
                ])->all(),
                'timezones' => collect(config('unionos.notifications.timezones', ['UTC']))
                    ->map(fn (string $timezone) => ['value' => $timezone, 'label' => $timezone])
                    ->all(),
            ],
        ]);
    }

    public function update(UpdateNotificationPreferencesRequest $request): RedirectResponse
    {
        $preference = $this->preferences->update($request->user(), $request->validated());

        if ($preference->quiet_mode_enabled) {
            return redirect()->route('settings.notifications.paused')
                ->with('success', 'Notification preferences updated.');
        }

        return redirect()->route('settings.notifications.show')
            ->with('success', 'Notification preferences updated.');
    }

    public function paused(): Response
    {
        return Inertia::render('State/NotificationsPaused');
    }
}
