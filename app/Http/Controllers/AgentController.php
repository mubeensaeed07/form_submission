<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function index(): View
    {
        $agents = User::where('role', 'agent')->orderByDesc('created_at')->paginate(10);

        return view('admin.agents.index', compact('agents'));
    }

    public function create(): View
    {
        return view('admin.agents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // auto-hashed by cast
            'role' => 'agent',
            'status' => 'invited',
        ]);

        return redirect()->route('admin.agents.index')
            ->with('status', 'Agent created. Share the credentials with the agent.');
    }

    public function updateStatus(Request $request, User $agent): RedirectResponse
    {
        abort_unless($agent->role === 'agent', 404);

        $data = $request->validate([
            'action' => ['required', 'in:approve,decline,enable,disable'],
        ]);

        switch ($data['action']) {
            case 'approve':
                $agent->status = 'active';
                break;
            case 'decline':
                $agent->status = 'declined';
                break;
            case 'enable':
                $agent->status = 'active';
                break;
            case 'disable':
                $agent->status = 'disabled';
                break;
        }

        $agent->save();

        return redirect()->route('admin.agents.index')
            ->with('status', 'Agent status updated.');
    }
}


