@extends('layouts.auth')

@section('title', 'Admin Login')

@section('auth-body-class', 'bg-dark')

@section('brand-class', 'text-white')

@section('card-class', 'bg-body')

@section('content')

<h5 class="fw-semibold text-center mb-2">
    Admin Sign In
</h5>

<p class="text-center text-muted small mb-4">
    Authorized personnel only
</p>

<form method="POST" action="{{ route('admin.login.submit') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label small">Email</label>
        <input type="email" name="email" class="form-control" required autofocus>
    </div>

    <div class="mb-3">
        <label class="form-label small">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="d-grid mt-3">
        <button class="btn btn-primary">
            Sign In
        </button>
    </div>

</form>

@endsection