@php
    use Illuminate\Support\Str;
@endphp

@extends(auth()->user()->role === 'admin' ? 'layouts.app' : 'layouts.agent')

@section('title', 'Customers')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
            <h5 class="mb-0">Customers</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFacebookUserModal">
                <i class="fa fa-plus"></i> Add Customer
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form class="mb-3" method="GET" action="{{ auth()->user()->role === 'admin' ? route('facebook-users.index') : route('agent.facebook-users.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name or Facebook URL...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100" type="submit">Search</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Facebook URL</th>
                        <th>Generated URL</th>
                        <th>Created By</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($facebookUsers as $fbUser)
                        <tr>
                            <td>{{ $fbUser->full_name }}</td>
                            <td>
                                <a href="{{ $fbUser->facebook_url }}" target="_blank" class="text-primary">
                                    {{ Str::limit($fbUser->facebook_url, 40) }}
                                </a>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" value="{{ $fbUser->generated_url }}" readonly id="url-{{ $fbUser->id }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyUrl({{ $fbUser->id }})">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                @if($fbUser->createdBy)
                                    {{ $fbUser->createdBy->name }} ({{ ucfirst($fbUser->createdBy->role) }})
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $fbUser->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $facebookUsers->links() }}
        </div>
    </div>
</div>

<!-- Add Facebook User Modal -->
<div class="modal fade" id="addFacebookUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ auth()->user()->role === 'admin' ? route('facebook-users.store') : route('agent.facebook-users.store') }}">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Facebook URL <span class="text-danger">*</span></label>
                        <input type="url" name="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror" 
                               value="{{ old('facebook_url') }}" placeholder="https://facebook.com/profile/..." required>
                        @error('facebook_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name') }}" placeholder="John Doe" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Form Type <span class="text-danger">*</span></label>
                        <div class="mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="form_type" id="form_type_charity" value="charity" {{ old('form_type', 'charity') === 'charity' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="form_type_charity">
                                    Charity
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="form_type" id="form_type_loan" value="loan" {{ old('form_type') === 'loan' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="form_type_loan">
                                    Loan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="form_type" id="form_type_grant" value="grant" {{ old('form_type') === 'grant' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="form_type_grant">
                                    Grant
                                </label>
                            </div>
                        </div>
                        @error('form_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate URL</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyUrl(id) {
    const input = document.getElementById('url-' + id);
    input.select();
    document.execCommand('copy');
    
    // Show feedback
    const btn = input.nextElementSibling;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-check"></i>';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-outline-secondary');
    
    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-secondary');
    }, 2000);
}

// Show modal with errors if validation fails
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('addFacebookUserModal'));
        modal.show();
    });
@endif
</script>
@endpush
@endsection

