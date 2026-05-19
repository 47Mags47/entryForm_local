<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSessionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SessionController
{
    public function create()
    {
        return Inertia::render('pages/session/login');
    }

    public function store(StoreSessionRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'), $request->input('remember')))
            return redirect()->route('home');

        return back()->withErrors(['form' => 'Неверный логин или пароль']);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
