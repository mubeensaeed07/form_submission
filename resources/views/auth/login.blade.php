<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('xhtml/assets/images/favicon.avif') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/style.css') }}">
    <style>
        body { background: linear-gradient(135deg, #e6f0ff, #f7fbff); }
        .auth-card { max-width: 420px; border: 0; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-6 text-center mb-4">
            <h3 class="fw-bold text-primary">Charity Assistance Admin</h3>
            <p class="text-muted mb-0">Sign in to manage submissions</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7">
            <div class="card auth-card">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center">Admin Login</h4>
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        @error('email')
                            <div class="alert alert-danger py-2">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

