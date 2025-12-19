@extends('layouts.app')

@section('title', 'Change Agent Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card card-soft">
            <div class="card-body">
                <h5 class="mb-4">Change Password for {{ $agent->name }}</h5>

                @if(session('success'))
                    <div class="alert alert-success py-2">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.agents.password.update', $agent) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required minlength="8">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required minlength="8">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

