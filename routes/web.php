<?php

declare(strict_types=1);

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;

// visit homepage, redirected to ideas page
Route::redirect('/', '/ideas');

// requires authentication to access ideas screen - redirected to login page
Route::get('/ideas', [IdeaController::class, 'index'])->name('idea.index')->middleware('auth');
Route::post('/ideas', [IdeaController::class, 'store'])->name('idea.store')->middleware('auth');

Route::get('/ideas/{idea}', [IdeaController::class, 'show'])
    ->name('idea.show')
    ->middleware('auth')
    // can the user work with the given idea? - check if user is the owner of the idea.
    // If yes, allow access to the idea. If not, deny access.
    // important so wrong users can't access others idea IDs through URL manipulation.
    ->can('workWith', 'idea'); // can:middleware. workWith: ability name. idea: model name.

Route::patch('/ideas/{idea}', [IdeaController::class, 'update'])->name('idea.update')->middleware('auth')->can('workWith', 'idea');

Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])
    ->name('idea.destroy')
    ->middleware('auth')
    ->can('workWith', 'idea');

Route::delete('/ideas/{idea}/image', [IdeaImageController::class, 'destroy'])
    ->name('idea.image.destroy')
    ->middleware('auth');

Route::patch('/steps/{step}', [StepController::class, 'update'])
    ->name('step.update')
    ->middleware('auth');

Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/login', [SessionsController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [SessionsController::class, 'store'])->middleware('guest');

Route::post('/logout', [SessionsController::class, 'destroy'])->middleware('auth');

Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware(['auth']);
Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update')->middleware(['auth']);
