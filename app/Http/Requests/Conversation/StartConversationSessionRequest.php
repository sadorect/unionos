<?php

namespace App\Http\Requests\Conversation;

use App\Domains\Conversation\Services\ConversationPackService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StartConversationSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pack_type' => [
                'required',
                'string',
                Rule::in(app(ConversationPackService::class)->activeSlugs()),
            ],
        ];
    }
}
