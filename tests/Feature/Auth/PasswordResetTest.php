<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertOk();
});

test('reset password link can be requested with captcha', function () {
    Notification::fake();

    $user = User::factory()->create();

    $response = $this
        ->withSession(captchaSession('forgot_password'))
        ->post('/forgot-password', [
            'email' => $user->email,
            'captcha_answer' => '2',
        ]);

    $response->assertSessionHas('status', 'If an account matches that email, a reset link has been sent.');
    Notification::assertSentTo($user, ResetPassword::class);
});

test('forgot password keeps the response generic for unknown emails', function () {
    Notification::fake();

    $response = $this
        ->withSession(captchaSession('forgot_password'))
        ->post('/forgot-password', [
            'email' => 'missing@example.com',
            'captcha_answer' => '2',
        ]);

    $response->assertSessionHas('status', 'If an account matches that email, a reset link has been sent.');
    Notification::assertNothingSent();
});

test('password can be reset with a valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this
        ->withSession(captchaSession('forgot_password'))
        ->post('/forgot-password', [
            'email' => $user->email,
            'captcha_answer' => '2',
        ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});
