<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertOk();
});

test('users can authenticate using the login screen with captcha', function () {
    $user = User::factory()->create();

    $response = $this
        ->withSession(captchaSession('login'))
        ->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'captcha_answer' => '2',
        ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('couple.create', absolute: false));
});

test('users cannot authenticate with an invalid captcha answer', function () {
    $user = User::factory()->create();

    $response = $this
        ->withSession(captchaSession('login'))
        ->from('/login')
        ->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'captcha_answer' => '7',
        ]);

    $this->assertGuest();
    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('captcha_answer');
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this
        ->withSession(captchaSession('login'))
        ->from('/login')
        ->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'captcha_answer' => '2',
        ]);

    $this->assertGuest();
    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('email');
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect(route('login'));
});
