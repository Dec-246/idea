<?php

use App\Models\User;
use App\Notifications\EmailChanged;
use Illuminate\Notifications\Notification;

it('requires authentication', function() {
    $this->get(route('profile.edit'))
        ->assertRedirect('/login');
});

it('edits a profile', function() {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit(route('profile.edit'))
        ->assertValue('name', $user->name)
        ->fill('name', 'New Name')

        ->assertValue('email', $user->email)
        ->fill('email', 'newemail@example.com')

        ->click('Update Account')
        ->assertSee('Profile updated!');

    // expect user has new name
    expect($user->fresh())->toMatchArray([
        'name' => 'New Name',
        'email' => 'newemail@example.com'
    ]);
});

it('notifies the original email if updated', function() {

// create user
$user = User::factory()->create();

// sign user in
    $this->actingAs($user);

    // fake notification API
    Notification::fake();

    $originalEmail = $user->email;

    // visit profile page
    visit(route('profile.edit'))
        ->assertValue('name', $user->name)
        ->fill('name', 'New Name')

        ->click('Update Account')
        ->assertSee('Profile updated!');


        Notification::assertSentOnDemand(EmailChanged::class, function (EmailChanged $notification, $routes, $notifiable) use ($originalEmail) {
            return $notifiable->routes['mail'] === $originalEmail;
        });
});
