<?php

namespace App\Http\Requests\CheckIn;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonthlyCoupleCheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'felt_heard' => ['required', 'integer', 'between:1,5'],
            'felt_appreciated' => ['required', 'integer', 'between:1,5'],
            'conflict_safety' => ['required', 'integer', 'between:1,5'],
            'emotional_closeness' => ['required', 'integer', 'between:1,5'],
        ];
    }
}
