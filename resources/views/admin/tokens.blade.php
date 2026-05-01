@extends('layouts.admin')

@section('title', 'Token Management')
@section('header', 'Token Management')

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0">Grant Tokens</h6>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('admin.tokens.grant') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Select User</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Choose User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} (Balance: {{ $user->tokenWallet->balance ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Amount</label>
                        <input type="number"
                               name="amount"
                               class="form-control"
                               placeholder="e.g. 50"
                               min="1"
                               required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Reason</label>
                        <input type="text"
                               name="reason"
                               class="form-control"
                               placeholder="Performance / Attendance / Hype"
                               required>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            Grant Tokens
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
    <div class="px-4 py-4 border-bottom bg-white">
        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h4 class="mb-1 fw-bold">Token Activity</h4>
                <div class="text-muted small">
                    Audit log of all token grants and adjustments
                </div>
            </div>

            <a href="{{ route('admin.tokens.export') }}"
               class="btn btn-sm btn-outline-dark rounded-pill px-4">
                <i class="bi bi-download me-2"></i>
                Export CSV
            </a>

        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="border-bottom">
                    <tr class="text-uppercase small text-muted">
                        <th class="px-4 py-3">User</th>
                        <th class="py-3">Granted By</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Reason</th>
                        <th class="py-3 text-end pe-4">Date</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($logs as $log)
                    <tr class="border-bottom">

                        {{-- User --}}
                        <td class="px-4 py-3">
                            <div class="fw-semibold">
                                {{ $log->user->name ?? 'N/A' }}
                            </div>
                            <div class="text-muted small">
                                ID: #{{ $log->user_id }}
                            </div>
                        </td>

                        {{-- Granted By --}}
                        <td class="py-3">
                            <span class="text-dark fw-medium">
                                {{ $log->grantedBy->name ?? 'System' }}
                            </span>
                        </td>

                        {{-- Amount --}}
                        <td class="py-3">
                            <span class="badge bg-success-subtle text-success fw-semibold px-3 py-2 rounded-pill">
                                +{{ $log->amount }}
                            </span>
                        </td>

                        {{-- Reason --}}
                        <td class="py-3 text-muted">
                            {{ $log->reason }}
                        </td>

                        {{-- Date --}}
                        <td class="py-3 text-end text-muted small pe-4">
                            {{ $log->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            No token activity found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

        @if($logs->hasPages())
            <div class="card-footer bg-white">
                {{ $logs->links() }}
            </div>
        @endif

    </div>
</div>

@endsection