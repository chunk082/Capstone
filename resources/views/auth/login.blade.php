@extends('layouts.auth')

@section('title', 'Employee Login')

@section('auth-body-class', 'bg-body-secondary')

@section('brand-class', 'text-primary')

@section('content')

<h5 class="fw-semibold text-center mb-2">
    Employee Portal
</h5>

<p class="text-center text-muted small mb-4">
    Sign in to access your dashboard
</p>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label small">Email</label>
        <input type="email" name="email" class="form-control" required autofocus>
    </div>

    <div class="mb-3">
        <label class="form-label small">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label small" for="remember">
            Remember me
        </label>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('password.request') }}" class="small text-decoration-none">
            Forgot password?
        </a>

        <button class="btn btn-dark">
            Log In
        </button>
    </div>

</form>

@endsection