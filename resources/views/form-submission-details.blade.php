@extends(auth()->user()->role === 'admin' ? 'layouts.app' : 'layouts.agent')

@section('title', 'Form Submission Details')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Form Submission Details</h5>
            <a href="{{ auth()->user()->role === 'admin' ? route('report') : route('agent.report') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to Report
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Status Badge -->
        <div class="mb-3">
            @if($formSubmission->status === 'approved')
                <span class="badge bg-success fs-6">Status: Approved</span>
            @elseif($formSubmission->status === 'incorrect')
                <span class="badge bg-danger fs-6">Status: Incorrect Details</span>
            @else
                <span class="badge bg-warning fs-6">Status: Pending</span>
            @endif
        </div>

        <div class="row g-4">
            <!-- Personal Information -->
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Personal Information</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $formSubmission->first_name }} {{ $formSubmission->last_name }}</p>
                        <p><strong>Email:</strong> {{ $formSubmission->email }}</p>
                        <p><strong>Phone:</strong> {{ $formSubmission->phone ?? '-' }}</p>
                        <p><strong>Date of Birth:</strong> {{ $formSubmission->dob ? $formSubmission->dob->format('Y-m-d') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Family & Financial Information -->
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Family & Financial Information</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Household Size:</strong> {{ $formSubmission->household_size ?? '-' }}</p>
                        <p><strong>Dependents:</strong> {{ $formSubmission->dependents ?? '-' }}</p>
                        <p><strong>Employment Status:</strong> {{ $formSubmission->employment_status ?? '-' }}</p>
                        <p><strong>Employer Name:</strong> {{ $formSubmission->employer_name ?? '-' }}</p>
                        <p><strong>Monthly Income:</strong> {{ $formSubmission->monthly_income ? '$'.number_format($formSubmission->monthly_income) : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Family Members -->
            @if($formSubmission->family_members && count($formSubmission->family_members) > 0)
            <div class="col-12">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Family Members</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Relationship</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formSubmission->family_members as $member)
                                        <tr>
                                            <td>{{ $member['name'] ?? '-' }}</td>
                                            <td>{{ $member['age'] ?? '-' }}</td>
                                            <td>{{ $member['relationship'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Assistance Details -->
            <div class="col-12">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Assistance Details</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Assistance Amount Requested:</strong> {{ $formSubmission->assistance_amount ? '$'.number_format($formSubmission->assistance_amount) : '-' }}</p>
                        <p><strong>Type of Assistance Needed:</strong></p>
                        @if(is_array($formSubmission->assistance_types) && count($formSubmission->assistance_types) > 0)
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($formSubmission->assistance_types as $type)
                                    <span class="badge bg-primary">{{ $type }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">-</p>
                        @endif
                        <p><strong>Description:</strong></p>
                        <p class="text-muted">{{ $formSubmission->assistance_description ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Verification & Address -->
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Verification & Address</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>SSN:</strong> {{ $formSubmission->ssn ?? '-' }}</p>
                        <p><strong>Street:</strong> {{ $formSubmission->street ?? '-' }}</p>
                        <p><strong>City:</strong> {{ $formSubmission->city ?? '-' }}</p>
                        <p><strong>State:</strong> {{ $formSubmission->state ?? '-' }}</p>
                        <p><strong>ZIP Code:</strong> {{ $formSubmission->zip ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Submission Info -->
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Submission Information</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Submitted By:</strong> 
                            @if($formSubmission->submittedBy)
                                {{ $formSubmission->submittedBy->name }} ({{ ucfirst($formSubmission->submittedBy->role) }})
                            @else
                                Public
                            @endif
                        </p>
                        @if($formSubmission->facebookUser)
                            <p><strong>Customer:</strong> <span class="badge bg-info">{{ $formSubmission->facebookUser->full_name }}</span></p>
                        @endif
                        <p><strong>Submitted At:</strong> {{ $formSubmission->created_at->format('Y-m-d H:i:s') }}</p>
                        @if($formSubmission->approvedBy)
                            <p><strong>Approved By:</strong> {{ $formSubmission->approvedBy->name }} ({{ ucfirst($formSubmission->approvedBy->role) }})</p>
                            <p><strong>Approved At:</strong> {{ $formSubmission->approved_at->format('Y-m-d H:i:s') }}</p>
                        @endif
                        @if($formSubmission->approved_url)
                            <p><strong>Approved URL:</strong> <a href="{{ $formSubmission->approved_url }}" target="_blank">{{ $formSubmission->approved_url }}</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 pt-4 border-top">
            <div class="d-flex gap-2 justify-content-end">
                @if($formSubmission->status !== 'incorrect')
                <form method="POST" action="{{ auth()->user()->role === 'admin' ? route('submissions.incorrect', $formSubmission) : route('agent.submissions.incorrect', $formSubmission) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to mark this form as incorrect?')">
                        <i class="fa fa-times"></i> Incorrect Details
                    </button>
                </form>
                @endif

                @if($formSubmission->status !== 'approved')
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveUrlModal">
                    <i class="fa fa-check"></i> Approve URL
                </button>
                @else
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveUrlModal">
                    <i class="fa fa-edit"></i> Update URL
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approve URL Modal -->
<div class="modal fade" id="approveUrlModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve URL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ auth()->user()->role === 'admin' ? route('submissions.approve-url', $formSubmission) : route('agent.submissions.approve-url', $formSubmission) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Redirect URL <span class="text-danger">*</span></label>
                        <input type="url" name="approved_url" class="form-control @error('approved_url') is-invalid @enderror" 
                               value="{{ old('approved_url', $formSubmission->approved_url) }}" 
                               placeholder="https://example.com" required>
                        @error('approved_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Enter the URL where the user should be redirected after approval.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save URL</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-show modal if there are errors
@if($errors->has('approved_url'))
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('approveUrlModal'));
        modal.show();
    });
@endif
</script>
@endpush
@endsection

