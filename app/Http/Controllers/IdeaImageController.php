<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IdeaImageController extends Controller
{
    // accept idea that houses image that should be deleted
    public function destroy(Idea $idea)
    {
        // authorize user to delete image
        Gate::authorize('workWith', $idea);

        // delete image from local storage
        Storage::disk('public')->delete($idea->image_path);

        $idea->update(['image_path' => null]);

        return back();
    }
}
