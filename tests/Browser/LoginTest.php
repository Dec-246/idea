<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('logs a user in', function () {
    $user = User::factory()->create(['password' => 'password123!@#']);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password123!@#')
        ->click('@login-button')
        ->assertPathIs('/');

    $this->assertAuthenticated();
});

it('logs a user out', function () {
    // create user
    $user = User::factory()->create();

    // set user as authenticated
    $this->actingAs($user);

    // if visit home page, and press log out, then should be signed out
    visit('/')
        ->click('Log out');

        // user now becomes guest
    $this->assertGuest();
});
