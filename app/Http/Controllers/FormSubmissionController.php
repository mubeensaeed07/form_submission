<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormSubmissionController extends Controller
{
    public function create(): View
    {
        abort_unless(auth()->user()?->role === 'admin', 403);
        return view('form-submission');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        abort_unless($user?->role === 'admin', 403);
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'dob' => ['nullable', 'date'],
            'household_size' => ['nullable', 'integer', 'min:1', 'max:30'],
            'dependents' => ['nullable', 'integer', 'min:0', 'max:30'],
            'family_members' => ['nullable', 'array'],
            'family_members.*.name' => ['nullable', 'string', 'max:255'],
            'family_members.*.age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'family_members.*.relationship' => ['nullable', 'string', 'max:255'],
            'employment_status' => ['nullable', 'string', 'max:255'],
            'employer_name' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['nullable', 'integer', 'min:0'],
            'assistance_amount' => ['nullable', 'integer', 'min:0'],
            'assistance_types' => ['nullable', 'array'],
            'assistance_types.*' => ['nullable', 'string', 'max:255'],
            'assistance_description' => ['nullable', 'string'],
            'ssn' => ['nullable', 'string', 'max:50'],
            'street' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'string', 'max:50'],
            'consent' => ['sometimes', 'boolean'],
        ]);

        $validated['consent'] = $request->boolean('consent');

        // Track who submitted (admin)
        $validated['submitted_by_id'] = $user->id;
        $validated['submitted_by_role'] = $user->role;

        FormSubmission::create($validated);

        return redirect()->route('processing');
    }

    public function processing(): View
    {
        abort_unless(auth()->user()?->role === 'admin', 403);
        return view('processing');
    }
}

