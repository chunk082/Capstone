@extends('layouts.admin')

@section('title', 'Order #' . $order->id)
@section('header', 'Order Details')

@php
    $status = strtolower($order->status ?? '');
    $statusClasses = [
        'pending' => 'bg-warning-subtle text-warning border-warning-subtle',
        'shipped' => 'bg-primary-subtle text-primary border-primary-subtle',
        'completed' => 'bg-success-subtle text-success border-success-subtle',
        'cancelled' => 'bg-danger-subtle text-danger border-danger-subtle',
    ];
@endphp

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-light border mb-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Orders
            </a>

            <h4 class="mb-1 fw-bold">Order #{{ $order->id }}</h4>
            <small class="text-muted">{{ $order->transaction_id }}</small>
        </div>

        <span class="badge rounded-pill border px-3 py-2 {{ $statusClasses[$status] ?? 'bg-secondary-subtle text-secondary border-secondary-subtle' }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="row g-4">

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 px-4 pt-4">
                    <h6 class="fw-bold mb-0">Order Summary</h6>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Transaction ID</div>
                            <div class="fw-semibold">{{ $order->transaction_id }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Date Created</div>
                            <div class="fw-semibold">{{ $order->created_at?->format('Y-m-d H:i') ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Tokens Spent</div>
                            <div class="fw-semibold">{{ number_format($order->tokens_spent ?? 0) }} Tokens</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Tracking Number</div>
                            <div class="fw-semibold">
                                {{ $order->tracking_number ?: 'Not assigned' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 px-4 pt-4">
                    <h6 class="fw-bold mb-0">Product</h6>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex gap-3">
                        <div style="width:84px; height:84px;" class="rounded overflow-hidden border bg-light flex-shrink-0">
                            @if(!empty($order->product?->image_url))
                                <img src="{{ $order->product->image_url }}"
                                     alt="{{ $order->product->name }}"
                                     class="w-100 h-100"
                                     style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted small">
                                    No image
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="fw-semibold fs-6">{{ $order->product->name ?? 'N/A' }}</div>
                            <div class="text-muted small mb-2">Product ID: #{{ $order->product_id }}</div>

                            @if(!empty($order->product?->description))
                                <div class="text-muted">{{ $order->product->description }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 px-4 pt-4">
                    <h6 class="fw-bold mb-0">Customer</h6>
                </div>

                <div class="card-body p-4">
                    <div class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</div>
                    <div class="text-muted small">{{ $order->user->email ?? 'No email available' }}</div>
                    <hr>
                    <div class="text-muted small mb-1">User ID</div>
                    <div class="fw-semibold">#{{ $order->user_id }}</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 px-4 pt-4">
                    <h6 class="fw-bold mb-0">Manage Order</h6>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mb-3">
                        @csrf

                        <label class="form-label">Status</label>
                        <select name="status" class="form-select mb-3" required>
                            @foreach(['Pending', 'Shipped', 'Completed', 'Cancelled'] as $option)
                                <option value="{{ $option }}" {{ strcasecmp($order->status, $option) === 0 ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary w-100">
                            Update Status
                        </button>
                    </form>

                    <hr>

                    <form method="POST" action="{{ route('admin.orders.tracking', $order) }}" class="mb-3">
                        @csrf

                        <label class="form-label">Tracking Number</label>
                        <input type="text"
                               name="tracking_number"
                               value="{{ old('tracking_number', $order->tracking_number) }}"
                               class="form-control mb-3"
                               placeholder="Enter tracking number">

                        <button type="submit" class="btn btn-outline-primary w-100">
                            Save Tracking
                        </button>
                    </form>

                    @if($status !== 'cancelled')
                        <hr>

                        <form method="POST"
                              action="{{ route('admin.orders.cancel', $order) }}"
                              onsubmit="return confirm('Cancel this order?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
