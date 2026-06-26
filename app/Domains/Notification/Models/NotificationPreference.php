<?php

namespace App\Domains\Notification\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'weekly_email_enabled',
        'weekly_day_of_week',
        'weekly_hour',
        'monthly_email_enabled',
        'monthly_day_of_month',
        'monthly_hour',
        'conversation_email_enabled',
        'conversation_in_app_enabled',
        'conversation_hour',
        'timezone',
        'quiet_mode_enabled',
    ];

    protected function casts(): array
    {
        return [
            'weekly_email_enabled' => 'bool',
            'monthly_email_enabled' => 'bool',
            'conversation_email_enabled' => 'bool',
            'conversation_in_app_enabled' => 'bool',
            'quiet_mode_enabled' => 'bool',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
