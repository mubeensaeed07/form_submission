@extends('layouts.agent')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-soft">
            <div class="card-body">
                <p class="mb-1 text-muted">Total Submissions</p>
                <h3 class="mb-0">{{ $total }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-soft">
            <div class="card-body">
                <p class="mb-1 text-muted">Submitted Today</p>
                <h3 class="mb-0">{{ $today }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-soft">
            <div class="card-body">
                <p class="mb-1 text-muted">Submitted This Week</p>
                <h3 class="mb-0">{{ $week }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card card-soft">
            <div class="card-body">
                <h4 class="mb-2">Welcome, {{ auth()->user()->name }}</h4>
                <p class="text-muted mb-3">
                    Share your unique form link below. All submissions from that link will be counted in your report.
                </p>

                <h6 class="mb-2">Your Public Form Link</h6>
                <div class="input-group">
                    <input type="text" class="form-control" id="agentFormLink" readonly value="{{ route('public.form', ['ref' => auth()->id()]) }}">
                    <button class="btn btn-primary" type="button" onclick="copyToClipboard()">
                        <i class="fa fa-copy"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const input = document.getElementById('agentFormLink');
    input.select();
    document.execCommand('copy');
    alert('Link copied to clipboard!');
}
</script>
@endsection


