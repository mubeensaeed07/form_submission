<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = $request->user();

            // Admin goes to admin dashboard
            if ($user->role === 'admin') {
                return redirect()->intended(route('dashboard'));
            }

            // Agent flow
            if ($user->role === 'agent') {
                // First or pending login -> pending approval
                if (in_array($user->status, ['invited', 'pending'], true)) {
                    $user->status = 'pending';
                    $user->save();
                    return redirect()->route('agent.waiting');
                }

                // Approved and active
                if ($user->status === 'active') {
                    return redirect()->intended(route('agent.dashboard'));
                }

                // Disabled or declined
                if (in_array($user->status, ['disabled', 'declined'], true)) {
                    Auth::logout();
                    return back()
                        ->withInput($request->only('email'))
                        ->withErrors(['email' => 'Your account is not active. Please contact the administrator.']);
                }
            }

            // Fallback: logout and show error
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Unable to login with this account.']);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

