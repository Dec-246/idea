<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;

class IdeaPolicy
{
    /**
     * Determine whether the user can work with the idea.
     */

    // User $user is always logged in user
    // Idea $idea is user who created the idea.
    public function workWith(User $user, Idea $idea): bool
    {
        // work out whether current user is the owner of the idea.
        // if matches, user authorised to make changes
        return $idea->user->is($user);
    }
}
