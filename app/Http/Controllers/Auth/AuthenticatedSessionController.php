<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Couple\Services\CoupleStateService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\Security\CaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly CaptchaService $captcha,
        private readonly CoupleStateService $coupleState,
    ) {
    }

    /**
     * Display the login view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'inviteToken' => $request->query('invite', $request->session()->get('invite_token')),
            'captchaQuestion' => $this->captcha->issue($request, 'login')['question'],
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if ($request->filled('invite_token')) {
            $request->session()->flash('invite_token', $request->string('invite_token')->toString());
        }

        $this->captcha->validate($request, 'login', $request->input('captcha_answer'));
        $request->authenticate();

        $request->session()->regenerate();

        $inviteToken = $request->string('invite_token')->toString() ?: (string) $request->session()->pull('invite_token');

        if ($inviteToken !== '') {
            return redirect()->route('invite.accept.show', $inviteToken);
        }

        return redirect()->intended($this->coupleState->redirectPathFor($request->user()));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
