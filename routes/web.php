<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\AgentReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacebookUserController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public multi-step form (accessible for both guests and logged-in users via link)
Route::get('/apply', [PublicFormController::class, 'show'])->name('public.form');
Route::post('/apply', [PublicFormController::class, 'store'])->name('public.form.submit');
Route::get('/apply/thank-you', [PublicFormController::class, 'thankYou'])->name('public.thankyou');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    // Redirect root based on role
    Route::get('/', function () {
        $user = auth()->user();
        if ($user?->role === 'admin') {
            return redirect()->route('dashboard');
        }
        if ($user?->role === 'agent') {
            return redirect()->route('agent.dashboard');
        }
        abort(403);
    });

    // Admin routes
    Route::middleware([])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/form-submission', [FormSubmissionController::class, 'create'])->name('form.show');
        Route::post('/form-submission', [FormSubmissionController::class, 'store'])->name('form.store');
        Route::get('/processing', [FormSubmissionController::class, 'processing'])->name('processing');

        Route::get('/report', [ReportController::class, 'index'])->name('report');

        // Agent management for admin
        Route::get('/admin/agents', [AgentController::class, 'index'])->name('admin.agents.index');
        Route::get('/admin/agents/create', [AgentController::class, 'create'])->name('admin.agents.create');
        Route::post('/admin/agents', [AgentController::class, 'store'])->name('admin.agents.store');
        Route::post('/admin/agents/{agent}/status', [AgentController::class, 'updateStatus'])->name('admin.agents.updateStatus');

        // Facebook Users management (admin sees all)
        Route::get('/admin/facebook-users', [FacebookUserController::class, 'index'])->name('facebook-users.index');
        Route::post('/admin/facebook-users', [FacebookUserController::class, 'store'])->name('facebook-users.store');
    });

    // Agent routes
    Route::get('/agent/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');
    Route::get('/agent/report', [AgentReportController::class, 'index'])->name('agent.report');
    Route::get('/agent/waiting', [AgentDashboardController::class, 'waiting'])->name('agent.waiting');

    // Facebook Users management (agents see only their own)
    Route::get('/agent/facebook-users', [FacebookUserController::class, 'index'])->name('agent.facebook-users.index');
    Route::post('/agent/facebook-users', [FacebookUserController::class, 'store'])->name('agent.facebook-users.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
