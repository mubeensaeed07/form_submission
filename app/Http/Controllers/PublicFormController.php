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
        $formType = $request->query('form', 'charity'); // Default to charity if not provided
        
        // Validate form type
        if (!in_array($formType, ['charity', 'loan', 'grant'])) {
            $formType = 'charity';
        }
        
        $referrer = $refId ? User::find($refId) : null;
        $facebookUser = $fbId ? FacebookUser::find($fbId) : null;
        
        // If FacebookUser exists and has form_type, use that; otherwise use URL parameter
        if ($facebookUser && $facebookUser->form_type) {
            $formType = $facebookUser->form_type;
        }

        return view('public-form', [
            'referrer' => $referrer,
            'facebookUser' => $facebookUser,
            'formType' => $formType,
        ]);
    }

    public function store(Request $request)
    {
        $refId = $request->input('referrer_id');
        $fbId = $request->input('facebook_user_id');
        $referrer = $refId ? User::find($refId) : null;
        $facebookUser = $fbId ? FacebookUser::find($fbId) : null;

        $formType = $request->input('form_type', 'charity');
        
        // Validate form type
        if (!in_array($formType, ['charity', 'loan', 'grant'])) {
            $formType = 'charity';
        }
        
        try {
            $validated = $request->validate([
                'form_type' => ['required', 'in:charity,loan,grant'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['required', 'string', 'max:50'],
                'dob' => ['required', 'date'],
                'household_size' => ['required', 'integer', 'min:1', 'max:30'],
                'dependents' => ['required', 'integer', 'min:0', 'max:30'],
                'family_members' => ['nullable', 'array'],
                'family_members.*.name' => ['required_with:family_members', 'string', 'max:255'],
                'family_members.*.age' => ['required_with:family_members', 'integer', 'min:0', 'max:120'],
                'family_members.*.relationship' => ['required_with:family_members', 'string', 'max:255'],
                'employment_status' => ['required', 'string', 'max:255'],
                'employer_name' => ['required', 'string', 'max:255'],
                'monthly_income' => ['nullable', 'integer', 'min:0'],
                'assistance_amount' => ['nullable', 'integer', 'min:0'],
                'loan_amount' => ['nullable', 'integer', 'min:0'],
                'loan_purpose' => ['nullable', 'string', 'max:255'],
                'loan_term' => ['nullable', 'integer', 'min:1'],
                'grant_amount' => ['nullable', 'integer', 'min:0'],
                'grant_purpose' => ['nullable', 'string', 'max:255'],
                'grant_category' => ['nullable', 'string', 'max:255'],
                'assistance_types' => ['nullable', 'array'],
                'assistance_types.*' => ['nullable', 'string', 'max:255'],
                'assistance_description' => ['required', 'string'],
                'ssn' => ['required', 'string', 'max:50', 'regex:/^\d{3}-\d{2}-\d{4}$/'],
                'street' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'state' => ['required', 'string', 'max:255'],
                'zip' => ['required', 'string', 'max:50'],
                'consent' => ['required', 'accepted'],
            ], [
                'ssn.regex' => 'The SSN must be in the format XXX-XX-XXXX.',
                'consent.required' => 'You must certify that all information provided is accurate and complete.',
                'consent.accepted' => 'You must certify that all information provided is accurate and complete.',
            ]);

            // Conditional validation based on form type
            if ($formType === 'charity') {
                $request->validate([
                    'assistance_types' => ['required', 'array', 'min:1'],
                ], [
                    'assistance_types.required' => 'Please select at least one type of assistance needed.',
                    'assistance_types.min' => 'Please select at least one type of assistance needed.',
                ]);
            } elseif ($formType === 'loan') {
                $request->validate([
                    'loan_purpose' => ['required', 'string', 'max:255'],
                    'loan_term' => ['required', 'integer', 'min:1'],
                ]);
            } elseif ($formType === 'grant') {
                $request->validate([
                    'grant_category' => ['required', 'string', 'max:255'],
                    'grant_purpose' => ['required', 'string', 'max:255'],
                ]);
            }

            $validated['consent'] = $request->boolean('consent');
            $validated['form_type'] = $formType;

            if ($referrer) {
                $validated['submitted_by_id'] = $referrer->id;
                $validated['submitted_by_role'] = $referrer->role;
            }

            if ($facebookUser) {
                $validated['facebook_user_id'] = $facebookUser->id;
                // Ensure form_type matches FacebookUser's form_type
                if ($facebookUser->form_type) {
                    $validated['form_type'] = $facebookUser->form_type;
                }
            }

            $submission = FormSubmission::create($validated);

            return redirect()->route('public.thankyou', ['id' => $submission->id]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirect back with input and errors, preserving the form URL parameters
            return redirect()
                ->route('public.form', [
                    'ref' => $refId,
                    'fb' => $fbId,
                    'form' => $formType
                ])
                ->withInput()
                ->withErrors($e->validator);
        }
    }

    public function thankYou(Request $request): View
    {
        $submissionId = $request->query('id');
        return view('public-thankyou', ['submissionId' => $submissionId]);
    }
}
