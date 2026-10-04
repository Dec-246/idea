<?php

use App\Models\Idea;
use App\Models\User;

it('requires authentication', function () {
    $idea = Idea::factory()->create();

    $this->get(route('idea.show', $idea))->assertRedirectToRoute('login');
});

it('disallows access to ideas they did not create', function () {
    // have a user
    $user = User::factory()->create();

    // sign user in
    $this->actingAs($user);

    // have an idea user did not create
    $idea = Idea::factory()->create();

    // is user tries to visit someone else's idea, they are forbidden from doing so.
    $this->get(route('idea.show', $idea))->assertForbidden();
});
