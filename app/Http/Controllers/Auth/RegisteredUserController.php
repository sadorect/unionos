<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Couple\Services\CoupleStateService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Support\Security\CaptchaService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly CaptchaService $captcha,
        private readonly CoupleStateService $coupleState,
    ) {
    }

    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Register', [
            'inviteToken' => $request->query('invite', $request->session()->get('invite_token')),
            'captchaQuestion' => $this->captcha->issue($request, 'register')['question'],
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        if ($request->filled('invite_token')) {
            $request->session()->flash('invite_token', $request->string('invite_token')->toString());
        }

        $this->captcha->validate($request, 'register', $request->input('captcha_answer'));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $inviteToken = $request->string('invite_token')->toString() ?: (string) $request->session()->pull('invite_token');

        if ($inviteToken !== '') {
            return redirect()->route('invite.accept.show', $inviteToken);
        }

        return redirect($this->coupleState->redirectPathFor($user));
    }
}
