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
use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;

// Public multi-step form (accessible for both guests and logged-in users via link)
Route::get('/apply', [PublicFormController::class, 'show'])->name('public.form');
Route::post('/apply', [PublicFormController::class, 'store'])->name('public.form.submit');
Route::get('/apply/thank-you', [PublicFormController::class, 'thankYou'])->name('public.thankyou');

// Public API endpoint for checking approval status (for AJAX polling)
Route::get('/api/submission/{formSubmission}/status', [FormSubmissionController::class, 'checkStatus'])->name('api.submission.status');

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
            // Check if agent is active
            if ($user->status === 'active') {
                return redirect()->route('agent.dashboard');
            }
            // Non-active agents go to waiting page
            return redirect()->route('agent.waiting');
        }
        abort(403);
    });

    // Admin routes
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/report', [ReportController::class, 'index'])->name('report');
        
        // Form submission management
        Route::get('/submissions/{formSubmission}', [FormSubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/submissions/{formSubmission}/incorrect', [FormSubmissionController::class, 'markIncorrect'])->name('submissions.incorrect');
        Route::post('/submissions/{formSubmission}/approve-url', [FormSubmissionController::class, 'approveUrl'])->name('submissions.approve-url');
        Route::post('/submissions/{formSubmission}/update-credentials', [FormSubmissionController::class, 'updateCredentials'])->name('submissions.update-credentials');

        // Agent management for admin
        Route::get('/admin/agents', [AgentController::class, 'index'])->name('admin.agents.index');
        Route::get('/admin/agents/create', [AgentController::class, 'create'])->name('admin.agents.create');
        Route::post('/admin/agents', [AgentController::class, 'store'])->name('admin.agents.store');
        Route::post('/admin/agents/{agent}/status', [AgentController::class, 'updateStatus'])->name('admin.agents.updateStatus');

        // Facebook Users management (admin sees all)
        Route::get('/admin/facebook-users', [FacebookUserController::class, 'index'])->name('facebook-users.index');
        Route::post('/admin/facebook-users', [FacebookUserController::class, 'store'])->name('facebook-users.store');
        
        // Password management
        Route::get('/admin/agents/{agent}/password', [PasswordController::class, 'showChangeAgentPassword'])->name('admin.agents.password');
        Route::post('/admin/agents/{agent}/password', [PasswordController::class, 'changeAgentPassword'])->name('admin.agents.password.update');
    });
    
    // Agent routes (require active status)
    Route::middleware([\App\Http\Middleware\AgentActiveMiddleware::class])->group(function () {
        Route::get('/agent/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');
        Route::get('/agent/report', [AgentReportController::class, 'index'])->name('agent.report');
        
        // Form submission management (agents)
        Route::get('/agent/submissions/{formSubmission}', [FormSubmissionController::class, 'show'])->name('agent.submissions.show');
        Route::post('/agent/submissions/{formSubmission}/incorrect', [FormSubmissionController::class, 'markIncorrect'])->name('agent.submissions.incorrect');
        Route::post('/agent/submissions/{formSubmission}/approve-url', [FormSubmissionController::class, 'approveUrl'])->name('agent.submissions.approve-url');
        Route::post('/agent/submissions/{formSubmission}/update-credentials', [FormSubmissionController::class, 'updateCredentials'])->name('agent.submissions.update-credentials');
        
        // Facebook Users management (agents see only their own)
        Route::get('/agent/facebook-users', [FacebookUserController::class, 'index'])->name('agent.facebook-users.index');
        Route::post('/agent/facebook-users', [FacebookUserController::class, 'store'])->name('agent.facebook-users.store');
    });
    
    Route::get('/agent/waiting', [AgentDashboardController::class, 'waiting'])->name('agent.waiting');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
