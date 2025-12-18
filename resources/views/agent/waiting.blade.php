@extends('layouts.agent')

@section('title', 'Waiting for Approval')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card card-soft text-center">
            <div class="card-body p-5">
                <h4 class="fw-bold mb-3">Waiting for Admin Approval</h4>
                <p class="text-muted mb-4">
                    Your agent account has been created and is pending approval from the administrator.
                </p>
                <p class="mb-1">You will be able to access your dashboard once the admin approves your account.</p>
                <p class="text-muted mb-4">Please check back later or contact the admin if this takes too long.</p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


