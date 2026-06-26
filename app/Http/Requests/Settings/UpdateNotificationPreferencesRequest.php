<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'weekly_email_enabled' => ['required', 'boolean'],
            'weekly_day_of_week' => ['required', 'integer', 'between:1,7'],
            'weekly_hour' => ['required', 'integer', 'between:8,21'],
            'monthly_email_enabled' => ['required', 'boolean'],
            'monthly_day_of_month' => ['required', 'integer', 'between:1,28'],
            'monthly_hour' => ['required', 'integer', 'between:8,21'],
            'conversation_email_enabled' => ['required', 'boolean'],
            'conversation_in_app_enabled' => ['required', 'boolean'],
            'conversation_hour' => ['required', 'integer', 'between:8,21'],
            'timezone' => ['required', 'timezone'],
            'quiet_mode_enabled' => ['required', 'boolean'],
        ];
    }
}
