<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->role === 'admin', 403);

        $total = FormSubmission::count();
        $today = FormSubmission::whereDate('created_at', Carbon::today())->count();
        $week = FormSubmission::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        return view('dashboard', [
            'total' => $total,
            'today' => $today,
            'week' => $week,
        ]);
    }
}

