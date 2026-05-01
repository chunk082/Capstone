@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Admin Dashboard')

@section('content')

<div class="container-fluid">

    {{-- KPI Row --}}
    <div class="row g-4 mb-4">

        {{-- Total Users --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Users</div>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalUsers ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Total Tokens Issued --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Tokens Issued</div>
                    <h3 class="fw-bold mt-2 mb-0 text-success">
                        {{ $totalTokens ?? 0 }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Active Staff --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">Active Staff</div>
                    <h3 class="fw-bold mt-2 mb-0">
                        {{ $activeStaff ?? 0 }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Today’s Grants --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">Tokens Granted Today</div>
                    <h3 class="fw-bold mt-2 mb-0 text-primary">
                        {{ $todayTokens ?? 0 }}
                    </h3>
                </div>
            </div>
        </div>

    </div>


    {{-- Recent Token Activity --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="px-4 py-4 border-bottom">
            <h5 class="fw-bold mb-1">Recent Token Activity</h5>
            <small class="text-muted">
                Latest token grants across the system
            </small>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="border-bottom text-uppercase small text-muted">
                        <tr>
                            <th class="px-4 py-3">User</th>
                            <th>Granted By</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th class="text-end pe-4">Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($recentLogs as $log)
                        <tr class="border-bottom">

                            <td class="px-4 py-3">
                                <div class="fw-semibold">
                                    {{ $log->user->name ?? 'N/A' }}
                                </div>
                                <div class="text-muted small">
                                    ID: #{{ $log->user_id }}
                                </div>
                            </td>

                            <td>
                                {{ $log->grantedBy->name ?? 'System' }}
                            </td>

                            <td>
                                <span class="badge bg-success-subtle text-success fw-semibold px-3 py-2 rounded-pill">
                                    +{{ $log->amount }}
                                </span>
                            </td>

                            <td class="text-muted">
                                {{ $log->reason }}
                            </td>

                            <td class="text-end text-muted small pe-4">
                                {{ $log->created_at->format('M d, Y H:i') }}
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No recent activity.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection