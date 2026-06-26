<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Support\Security\CaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        private readonly CaptchaService $captcha,
    ) {
    }

    /**
     * Display the password reset link request view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
            'captchaQuestion' => $this->captcha->issue($request, 'forgot_password')['question'],
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $this->captcha->validate($request, 'forgot_password', $request->input('captcha_answer'));

        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'If an account matches that email, a reset link has been sent.');
    }
}
