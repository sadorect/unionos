<?php

namespace App\Http\Requests\CheckIn;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeeklyCheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'emotional_availability' => ['required', 'integer', 'between:1,5'],
            'communication_effort' => ['required', 'integer', 'between:1,5'],
            'stress_level' => ['required', 'integer', 'between:1,5'],
            'appreciation_text' => ['nullable', 'string', 'max:500'],
            'struggle_text' => ['nullable', 'string', 'max:500'],
            'intention_text' => ['nullable', 'string', 'max:500'],
        ];
    }
}
