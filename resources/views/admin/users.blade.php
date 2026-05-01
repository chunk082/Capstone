@extends('layouts.admin')

@section('title', 'Users')
@section('header', 'User Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Registered Users</h4>
        <small class="text-muted">Manage user roles and permissions</small>
    </div>

    <form method="GET">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control form-control-sm"
               placeholder="Search users..."
               style="width: 220px;">
    </form>
</div>

@php
    $roleBadges = [
        'admin' => 'danger',
        'hype' => 'primary',
        'employee' => 'secondary'
    ];
@endphp


<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">

            <thead class="bg-light border-bottom">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($users as $user)
                <tr>

                    <td>
                        <strong>{{ $user->name }}</strong>
                        <div class="text-muted small">ID: #{{ $user->id }}</div>
                    </td>

                    <td class="text-muted">
                        {{ $user->email }}
                    </td>

                    <td>
                        <span class="badge bg-{{ $roleBadges[$user->role] ?? 'secondary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    <td>
                        {{ $user->created_at->format('Y-m-d') }}
                    </td>

                    <td class="text-end">
                        @if(auth()->id() !== $user->id)
                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#promoteModal{{ $user->id }}">
                                Manage Role
                            </button>
                        @else
                            <span class="text-muted small">Current User</span>
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No users found.
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>


{{-- PROMOTE ROLE MODALS --}}
@foreach($users as $user)
@if(auth()->id() !== $user->id)
<div class="modal fade" id="promoteModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Manage Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p class="mb-2">
                    <strong>{{ $user->name }}</strong>
                </p>
                <p class="text-muted small mb-3">
                    {{ $user->email }}
                </p>

                <form method="POST" action="{{ route('admin.users.role', $user) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Select Role</label>
                        <select class="form-select" name="role">
                            <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>
                                Employee
                            </option>
                            <option value="hype" {{ $user->role === 'hype' ? 'selected' : '' }}>
                                Hype
                            </option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-outline-secondary btn-sm"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button class="btn btn-primary btn-sm">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
@endif
@endforeach

@endsection