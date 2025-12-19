<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Approval | Form Submission</title>
    <link rel="stylesheet" href="{{ asset('xhtml/assets/icons/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/style.css') }}">
    <style>
        body {
            background: #f4f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .waiting-container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }
        .card-soft {
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            border: 0;
            border-radius: 16px;
        }
    </style>
</head>
<body>
    <div class="waiting-container">
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
</body>
</html>


