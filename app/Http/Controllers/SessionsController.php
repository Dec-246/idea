<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        if (! Auth::attempt($attributes)) {
            // failed
            return back()
                ->withError(['password' => 'We were unable to authenticate using the provided credentials.'])
                ->withInput();
        }

        // if login did succeed, regenerate session
        $request->session()->regenerate();

        // direct to homepage after login
        return redirect()->intended('/')->with('success', 'You are now logged in.');
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/');
    }
}
