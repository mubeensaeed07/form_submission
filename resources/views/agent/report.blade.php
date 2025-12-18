@extends('layouts.agent')

@section('title', 'My Submissions')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
            <h5 class="mb-0">My Submissions</h5>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Assistance Amount</th>
                        <th>Types</th>
                        <th>Submitted</th>
                        <th>Facebook User</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $submission)
                        <tr>
                            <td>{{ $submission->first_name }} {{ $submission->last_name }}</td>
                            <td>{{ $submission->email }}</td>
                            <td>{{ $submission->phone }}</td>
                            <td>{{ $submission->assistance_amount ? '$'.number_format($submission->assistance_amount) : '-' }}</td>
                            <td>
                                @if (is_array($submission->assistance_types))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($submission->assistance_types as $type)
                                            <span class="badge bg-light text-dark">{{ $type }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $submission->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @if($submission->facebookUser)
                                    <span class="badge bg-info">{{ $submission->facebookUser->full_name }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $submission->city }} {{ $submission->state }}</td>
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
@endsection


