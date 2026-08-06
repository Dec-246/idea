<?php

use App\Models\User;

// testing happy path

it('creates a new idea', function () {
    $this->actingAs($user = User::factory()->create());

    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'My first idea')
        ->click('@button-status-completed')
        ->fill('description', 'This is my first idea')
        ->fill('@new-link', 'https://example.com')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://example.org')
        ->click('@submit-new-link-button')
        ->click('Create Idea')
        ->assertPathIs('/ideas');

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'My first idea',
        'status' => 'completed',
        'description' => 'This is my first idea',
        'links' => ['https://example.com', 'https://example.org'],
    ]);
});
