<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordController extends Controller
{
    // Agent password change
    public function showChangePassword(): View
    {
        return view('agent.change-password');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = $request->password;
        $user->save();

        return redirect()->route('agent.dashboard')
            ->with('success', 'Password changed successfully.');
    }

    // Admin changing agent password
    public function showChangeAgentPassword(User $agent): View
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        abort_unless($agent->role === 'agent', 404);

        return view('admin.agents.change-password', compact('agent'));
    }

    public function changeAgentPassword(Request $request, User $agent): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        abort_unless($agent->role === 'agent', 404);

        $request->validate([
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $agent->password = $request->password;
        $agent->save();

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent password changed successfully.');
    }
}

