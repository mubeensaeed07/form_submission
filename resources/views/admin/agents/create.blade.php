@extends('layouts.app')

@section('title', 'Add Agent')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card card-soft">
            <div class="card-body">
                <h5 class="mb-3">Add New Agent</h5>

                <p class="text-muted">Create an agent account. Share the email and password with the agent so they can login.</p>

                <form method="POST" action="{{ route('admin.agents.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" value="{{ old('password') }}" class="form-control" required>
                        <small class="text-muted">You will send this password to the agent.</small>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Agent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


