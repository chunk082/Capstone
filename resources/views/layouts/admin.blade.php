<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-size: 14px;
        }

        .sidebar {
            width: 260px;
            flex: 0 0 260px;
            min-height: 100vh;
        }

        .nav-pills .nav-link {
            border-radius: 8px;
            white-space: nowrap;
            transition: all 0.15s ease-in-out;
        }

        .main-panel {
            min-width: 0;
        }

        .nav-pills .nav-link:hover {
            transform: translateX(3px);
        }

        .nav-pills .nav-link.active {
            background-color: var(--bs-primary);
        }

        .topbar {
            height: 64px;
        }
    </style>
</head>

<body class="bg-light">

<div class="d-flex">

    {{-- Sidebar --}}
    <div class="sidebar bg-white border-end p-4">

        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}"
           class="d-flex align-items-center mb-4 text-decoration-none">
            <span class="fs-5 fw-semibold text-dark">TokenReward</span>
        </a>

        <hr>

        {{-- Navigation --}}
        <ul class="nav nav-pills flex-column gap-1">

            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active text-white' : 'link-dark' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.orders') }}"
                   class="nav-link {{ request()->routeIs('admin.orders*') ? 'active text-white' : 'link-dark' }}">
                    <i class="bi bi-box-seam me-2"></i> Orders
                </a>
            </li>

            <li>
                <a href="{{ route('admin.products.index') }}"
                   class="nav-link {{ request()->routeIs('admin.products*') ? 'active text-white' : 'link-dark' }}">
                    <i class="bi bi-grid me-2"></i> Products
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users') }}"
                   class="nav-link {{ request()->routeIs('admin.users*') ? 'active text-white' : 'link-dark' }}">
                    <i class="bi bi-people me-2"></i> Users
                </a>
            </li>

            <li>
                <a href="{{ route('admin.tokens') }}"
                   class="nav-link {{ request()->routeIs('admin.tokens*') ? 'active text-white' : 'link-dark' }}">
                    <i class="bi bi-currency-dollar me-2"></i> Tokens
                </a>
            </li>

        </ul>

    </div>

    {{-- Main Content Area --}}
    <div class="main-panel flex-grow-1 d-flex flex-column">

        {{-- Topbar --}}
        <div class="topbar bg-white border-bottom px-4 d-flex align-items-center justify-content-between">

            <h6 class="mb-0 fw-semibold">
                @yield('header')
            </h6>

            <div class="dropdown">

                <button class="btn d-flex align-items-center gap-2 border-0 shadow-none"
                        data-bs-toggle="dropdown">

                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                         style="width: 34px; height: 34px;">
                        {{ strtoupper(substr(auth()->guard('admin')->user()->name, 0, 1)) }}
                    </div>

                    <div class="text-start d-none d-md-block">
                        <div class="fw-semibold small">
                            {{ auth()->guard('admin')->user()->name }}
                        </div>
                        <div class="text-muted" style="font-size: 11px;">
                            Administrator
                        </div>
                    </div>

                    <i class="bi bi-chevron-down small text-muted"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                    <li>
                        <span class="dropdown-item-text small text-muted">
                            {{ auth()->guard('admin')->user()->email }}
                        </span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item" href="{{ url('/') }}">
                            <i class="bi bi-house-door me-2"></i>
                            Homepage
                        </a>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>

        {{-- Page Content --}}
        <div class="p-4">
            @yield('content')
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
