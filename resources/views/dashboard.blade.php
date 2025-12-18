@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card card-soft">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 text-muted">Total Submissions</p>
                        <h3 class="mb-0">{{ $total }}</h3>
                    </div>
                    <div class="badge bg-primary rounded-pill p-3">
                        <i class="fa fa-list text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card card-soft">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 text-muted">Submitted Today</p>
                        <h3 class="mb-0">{{ $today }}</h3>
                    </div>
                    <div class="badge bg-success rounded-pill p-3">
                        <i class="fa fa-calendar text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card card-soft">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 text-muted">Submitted This Week</p>
                        <h3 class="mb-0">{{ $week }}</h3>
                    </div>
                    <div class="badge bg-info rounded-pill p-3">
                        <i class="fa fa-calendar-week text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-soft mt-3">
    <div class="card-body">
        <h5 class="mb-0">Quick Links</h5>
        <div class="mt-3 d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('form.show') }}">New Submission</a>
            <a class="btn btn-outline-primary" href="{{ route('report') }}">View Report</a>
        </div>

        <hr class="my-4">
        <h6 class="mb-2">Public Form Link</h6>
        <p class="text-muted small mb-2">Share this URL with people so their submissions are tracked as sent by the admin.</p>
        <div class="input-group">
            <input type="text" class="form-control" readonly value="{{ route('public.form', ['ref' => auth()->id()]) }}">
        </div>
    </div>
</div>
@endsection

