<?php

namespace App\Domains\Notification\Services;

use App\Domains\Notification\Models\NotificationPreference;
use App\Models\User;

class NotificationPreferenceService
{
    public function forUser(User $user): NotificationPreference
    {
        return $user->notificationPreference()->firstOrCreate([], [
            'weekly_email_enabled' => true,
            'weekly_day_of_week' => 1,
            'weekly_hour' => 18,
            'monthly_email_enabled' => true,
            'monthly_day_of_month' => 1,
            'monthly_hour' => 18,
            'conversation_email_enabled' => false,
            'conversation_in_app_enabled' => true,
            'conversation_hour' => 18,
            'timezone' => 'UTC',
            'quiet_mode_enabled' => false,
        ]);
    }

    public function update(User $user, array $attributes): NotificationPreference
    {
        $preference = $this->forUser($user);
        $preference->fill($attributes)->save();

        return $preference->refresh();
    }

    public function payload(NotificationPreference $preference): array
    {
        return [
            'weeklyEmailEnabled' => $preference->weekly_email_enabled,
            'weeklyDayOfWeek' => $preference->weekly_day_of_week,
            'weeklyHour' => $preference->weekly_hour,
            'monthlyEmailEnabled' => $preference->monthly_email_enabled,
            'monthlyDayOfMonth' => $preference->monthly_day_of_month,
            'monthlyHour' => $preference->monthly_hour,
            'conversationEmailEnabled' => $preference->conversation_email_enabled,
            'conversationInAppEnabled' => $preference->conversation_in_app_enabled,
            'conversationHour' => $preference->conversation_hour,
            'timezone' => $preference->timezone,
            'quietModeEnabled' => $preference->quiet_mode_enabled,
        ];
    }
}
