<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertOk();
});

test('new users can register with captcha protection', function () {
    $response = $this
        ->withSession(captchaSession('register'))
        ->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'captcha_answer' => '2',
        ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('couple.create', absolute: false));
});

test('registration fails when captcha answer is invalid', function () {
    $response = $this
        ->withSession(captchaSession('register'))
        ->from('/register')
        ->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'captcha_answer' => '9',
        ]);

    $this->assertGuest();
    $response->assertRedirect('/register');
    $response->assertSessionHasErrors('captcha_answer');
});
