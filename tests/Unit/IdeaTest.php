<?php

use App\Models\Idea;
use App\Models\User;
use Ramsey\Collection\Collection;

test('it belongs to a user', function () {
    $idea = Idea::factory()->create();

    expect($idea->user)->toBeInstanceOf(User::class);
});

test('it can have steps', function () {
    $idea = Idea::factory()->create();

    // will get empty collection
    expect($idea->steps)->toBeEmpty();

    $idea->steps()->create([
        'description' => 'Do the thing',
    ]);

    // check number of steps - refresh ideas - fresh relationship with ideas
    expect($idea->fresh()->steps)->toHaveCount(1);

});
