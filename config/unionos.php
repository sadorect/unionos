<?php

return [
    'system_token' => env('UNIONOS_SYSTEM_TOKEN'),

    'invites' => [
        'expires_after_hours' => (int) env('UNIONOS_INVITE_TTL_HOURS', 72),
    ],
    'captcha' => [
        'min' => (int) env('UNIONOS_CAPTCHA_MIN', 1),
        'max' => (int) env('UNIONOS_CAPTCHA_MAX', 9),
    ],
    'rate_limits' => [
        'auth_sensitive' => (int) env('UNIONOS_AUTH_RATE_LIMIT', 5),
    ],
    'conversations' => [
        'stale_after_hours' => (int) env('UNIONOS_CONVERSATION_STALE_HOURS', 24),
    ],
    'notifications' => [
        'allowed_start_hour' => (int) env('UNIONOS_NOTIFICATION_START_HOUR', 8),
        'allowed_end_hour' => (int) env('UNIONOS_NOTIFICATION_END_HOUR', 21),
        'cooldown_hours' => (int) env('UNIONOS_NOTIFICATION_COOLDOWN_HOURS', 24),
        'conversation_cooldown_days' => (int) env('UNIONOS_NOTIFICATION_CONVERSATION_COOLDOWN_DAYS', 7),
        'timezones' => [
            'UTC',
            'Europe/London',
            'America/New_York',
            'America/Chicago',
            'America/Denver',
            'America/Los_Angeles',
        ],
    ],
];
