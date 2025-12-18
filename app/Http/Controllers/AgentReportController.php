<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgentReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->role === 'agent', 403);

        $query = FormSubmission::with(['submittedBy', 'facebookUser'])
            ->where('submitted_by_id', $user->id)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
        }

        if ($request->filled('assistance_type')) {
            $query->whereJsonContains('assistance_types', $request->input('assistance_type'));
        }

        $submissions = $query->paginate(10)->withQueryString();

        return view('agent.report', [
            'submissions' => $submissions,
            'filters' => $request->only(['search', 'date_from', 'date_to', 'assistance_type']),
        ]);
    }
}
