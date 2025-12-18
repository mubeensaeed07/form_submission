<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Carbon\Carbon;
use Illuminate\View\View;

class AgentDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'agent', 403);

        $base = FormSubmission::where('submitted_by_id', $user->id);
        $total = (clone $base)->count();
        $today = (clone $base)->whereDate('created_at', Carbon::today())->count();
        $week = (clone $base)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        return view('agent.dashboard', compact('total', 'today', 'week'));
    }

    public function waiting(): View
    {
        return view('agent.waiting');
    }
}


