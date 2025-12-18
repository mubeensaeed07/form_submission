<?php

namespace App\Http\Controllers;

use App\Models\FacebookUser;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicFormController extends Controller
{
    public function show(Request $request): View
    {
        $refId = $request->query('ref');
        $fbId = $request->query('fb');
        $referrer = $refId ? User::find($refId) : null;
        $facebookUser = $fbId ? FacebookUser::find($fbId) : null;

        return view('public-form', [
            'referrer' => $referrer,
            'facebookUser' => $facebookUser,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $refId = $request->input('referrer_id');
        $fbId = $request->input('facebook_user_id');
        $referrer = $refId ? User::find($refId) : null;
        $facebookUser = $fbId ? FacebookUser::find($fbId) : null;

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

        if ($referrer) {
            $validated['submitted_by_id'] = $referrer->id;
            $validated['submitted_by_role'] = $referrer->role;
        }

        if ($facebookUser) {
            $validated['facebook_user_id'] = $facebookUser->id;
        }

        FormSubmission::create($validated);

        return redirect()->route('public.thankyou');
    }

    public function thankYou(): View
    {
        return view('public-thankyou');
    }
}
