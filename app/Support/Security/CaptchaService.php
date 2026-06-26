<?php

namespace App\Support\Security;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CaptchaService
{
    public function issue(Request $request, string $form, bool $refresh = false): array
    {
        $key = $this->sessionKey($form);
        $challenge = $request->session()->get($key);

        if (! $refresh && is_array($challenge) && isset($challenge['question'], $challenge['answer'])) {
            return $challenge;
        }

        $left = random_int((int) config('unionos.captcha.min'), (int) config('unionos.captcha.max'));
        $right = random_int((int) config('unionos.captcha.min'), (int) config('unionos.captcha.max'));

        $challenge = [
            'question' => sprintf('What is %d + %d?', $left, $right),
            'answer' => (string) ($left + $right),
        ];

        $request->session()->put($key, $challenge);

        return $challenge;
    }

    public function validate(Request $request, string $form, mixed $answer): void
    {
        $challenge = $request->session()->get($this->sessionKey($form));

        if (
            ! is_array($challenge)
            || ! isset($challenge['answer'])
            || trim((string) $answer) === ''
            || ! hash_equals((string) $challenge['answer'], trim((string) $answer))
        ) {
            $this->issue($request, $form, true);

            throw ValidationException::withMessages([
                'captcha_answer' => 'The provided details could not be verified.',
            ]);
        }

        $request->session()->forget($this->sessionKey($form));
    }

    private function sessionKey(string $form): string
    {
        return 'unionos.captcha.'.$form;
    }
}
