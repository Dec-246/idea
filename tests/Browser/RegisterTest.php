<?php

use Illuminate\Support\Facades\Auth;

it('registers a user', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password123!@#')
        ->click('Create Account')
        ->assertPathIs('/ideas');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

it('requires valid email address', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'not-an-email')
        ->fill('password', 'password123!@#')
        ->click('Create Account')
        ->assertSee('The email field must be a valid email address.');

    $this->assertGuest();
});
