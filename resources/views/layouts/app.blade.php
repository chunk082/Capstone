<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'TokenReward')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            TokenReward
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                @auth
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
        {{ auth()->user()->name }}
    </a>

    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('admin.login') }}">
                Admin Panel
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item text-danger">
                    Logout
                </button>
            </form>
        </li>
    </ul>
</li>
@endauth

            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<main class="flex-fill py-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
        <small>
            © {{ date('Y') }} TokenReward — Capstone Project
        </small>
    </div>
</footer>

<!-- Bootstrap JS -->
<script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
