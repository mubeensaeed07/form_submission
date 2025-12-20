<?php

namespace App\Http\Controllers;

use App\Models\FacebookUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacebookUserController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $query = FacebookUser::with('createdBy')->latest();

        // Admin sees all, agents see only their own
        if ($user->role === 'agent') {
            $query->where('created_by_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('facebook_url', 'like', "%{$search}%");
            });
        }

        $facebookUsers = $query->paginate(15)->withQueryString();

        $viewName = auth()->user()->role === 'admin' ? 'facebook-users.index' : 'facebook-users.index';
        
        return view($viewName, [
            'facebookUsers' => $facebookUsers,
            'search' => $request->input('search', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'facebook_url' => ['required', 'url'],
            'full_name' => ['required', 'string', 'max:255'],
        ]);

        // Check if Facebook URL already exists
        $existing = FacebookUser::where('facebook_url', $validated['facebook_url'])->first();
        if ($existing) {
            return back()->withInput()->withErrors(['facebook_url' => 'User already exists']);
        }

        $user = auth()->user();
        $generatedUrl = route('public.form', [
            'ref' => $user->id,
            'fb' => 'PLACEHOLDER', // Will be updated after creation
        ]);

        $facebookUser = FacebookUser::create([
            'facebook_url' => $validated['facebook_url'],
            'full_name' => $validated['full_name'],
            'created_by_id' => $user->id,
            'created_by_role' => $user->role,
        ]);

        // Update generated URL with actual Facebook user ID
        $generatedUrl = route('public.form', [
            'ref' => $user->id,
            'fb' => $facebookUser->id,
        ]);

        $facebookUser->update(['generated_url' => $generatedUrl]);

        $redirectRoute = $user->role === 'admin' ? 'facebook-users.index' : 'agent.facebook-users.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Customer added and URL generated successfully!');
    }
}
