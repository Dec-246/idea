<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;

class UpdateIdea
{
    public function handle(array $attributes, Idea $idea)
    {
        $data = collect($attributes)->only([
            'title', 'description', 'status', 'links',
        ])->toArray(); //grab attributes we care about

        // potentially upload an image, if one is provided
        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        DB::transaction(function () use ($idea, $data, $attributes) {
            // update idea with new data
            $idea->update($data);

            // clear out steps
            $idea->steps()->delete();

            // rebuild with new steps provided
            $idea->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
