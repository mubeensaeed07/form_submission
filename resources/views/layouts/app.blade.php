<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Form Submission</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('xhtml/assets/images/favicon.avif') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/icons/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/vendor/metismenu/dist/metisMenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/style.css') }}">

    <style>
        body { background: #f4f7fb; }
        .content-body { min-height: 100vh; }
        .brand-logo-text { font-weight: 700; color: #0b5ed7; }
        .card-soft { box-shadow: 0 8px 20px rgba(0,0,0,0.05); border: 0; border-radius: 16px; }
        .step-badge { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
    </style>
    @stack('styles')
</head>
<body>
<div id="main-wrapper">
    <div class="nav-header">
        <a href="{{ route('dashboard') }}" class="brand-logo d-flex align-items-center gap-2">
            <span class="brand-logo-text">Form Submission</span>
        </a>
        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span><span class="line"></span><span class="line"></span>
            </div>
        </div>
    </div>

    <div class="header">
        <div class="header-content">
            <nav class="navbar navbar-expand">
                <div class="collapse navbar-collapse justify-content-between">
                    <div class="header-left">
                        <div class="dashboard_bar">
                            @yield('title', 'Dashboard')
                        </div>
                    </div>
                    <div class="header-right">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="deznav">
        <div class="deznav-scroll">
            <ul class="metismenu" id="menu">
                <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="ai-icon" aria-label="Dashboard">
                        <i class="flaticon-381-networking"></i><span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('report') ? 'mm-active' : '' }}">
                    <a href="{{ route('report') }}" class="ai-icon" aria-label="Report">
                        <i class="flaticon-381-list"></i><span class="nav-text">Report</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.agents.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.agents.index') }}" class="ai-icon" aria-label="Agents">
                        <i class="fa fa-users"></i><span class="nav-text">Agents</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('facebook-users.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('facebook-users.index') }}" class="ai-icon" aria-label="Customers">
                        <i class="fa fa-facebook"></i><span class="nav-text">Customers</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="content-body">
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('xhtml/assets/vendor/global/global.min.js') }}"></script>
<script src="{{ asset('xhtml/assets/vendor/metismenu/dist/metisMenu.min.js') }}"></script>
<script src="{{ asset('xhtml/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('xhtml/assets/vendor/deznav/deznav.min.js') }}"></script>
<script src="{{ asset('xhtml/assets/js/custom.min.js') }}"></script>
<script src="{{ asset('xhtml/assets/js/deznav-init.js') }}"></script>
@stack('scripts')
</body>
</html>

