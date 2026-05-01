<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Authentication')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="@yield('auth-body-class', 'bg-body-secondary')">

<div class="min-vh-100 d-flex align-items-center justify-content-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">

                {{-- Brand --}}
                <div class="text-center mb-4">
                    <h4 class="fw-semibold @yield('brand-class', 'text-dark')">
                        @yield('brand', 'TokenReward')
                    </h4>
                </div>

                {{-- Card --}}
                <div class="card border-0 shadow-lg @yield('card-class')">
                    <div class="card-body p-4">
                        @yield('content')
                    </div>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        © {{ date('Y') }} TokenReward
                    </small>
                </div>

            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>