<?php

use App\Models\Idea;
use App\Models\User;

// testing happy path
it('shows the initial input state', function () {
    $this->actingAs($user = User::factory()->create());

    // now have an idea
    $idea = Idea::factory()->for($user)->create();

    visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->assertValue('title', $idea->title)
        ->assertValue('description', $idea->description)
        ->assertValue('status', $idea->status);
});

it('edits an existing idea', function () {
    $this->actingAs($user = User::factory()->create());

    // now have an idea
    $idea = Idea::factory()->for($user)->create();

    visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->fill('title', 'My first idea')
        ->click('@button-status-completed')
        ->fill('description', 'This is my first idea')
        ->fill('@new-link', 'https://laracasts.com')
        ->click('@submit-new-link-button')
        ->fill('@new-step', 'Do a thing')
        ->click('@submit-new-step-button')
        ->click('Update Idea')
        ->assertRoute('idea.show', [$idea]);

    expect($idea = $user->ideas()->first())->toMatchArray([
        'title' => 'My first idea',
        'status' => 'completed',
        'description' => 'This is my first idea',
        'links' => [$idea->links[0], 'https://laracasts.com'],
    ]);

    expect($idea->steps)->toHaveCount(1);
});
