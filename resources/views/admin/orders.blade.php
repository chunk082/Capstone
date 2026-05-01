@extends('layouts.admin')

@section('title', 'Orders')
@section('header', 'Order Management')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">Orders</h4>
            <small class="text-muted">
                Track, update and manage all customer orders
            </small>
        </div>

        {{-- FILTER + SEARCH --}}
        <form method="GET" action="{{ route('admin.orders') }}" class="d-flex gap-2">

            <select name="status"
                    class="form-select form-select-sm"
                    style="width: 160px;">
                <option value="">All Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>
                    Shipped
                </option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>
                    Completed
                </option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>
            </select>

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control form-control-sm"
                   placeholder="Search order ID / transaction..."
                   style="width: 220px;">

            <button type="submit" class="btn btn-outline-secondary btn-sm">
                Filter
            </button>

        </form>

    </div>


    {{-- ORDER TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="table-responsive">

            <table class="table align-middle table-hover mb-0">

                <thead class="border-bottom bg-light">
                    <tr class="text-uppercase small text-muted">
                        <th class="px-4 py-3">Order</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Tokens</th>
                        <th>Status</th>
                        <th>Tracking</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($orders as $order)

                    <tr class="border-bottom">

                        {{-- ORDER INFO --}}
                        <td class="px-4 py-3">
                            <div class="fw-semibold">
                                #{{ $order->id }}
                            </div>
                            <div class="text-muted small">
                                {{ $order->transaction_id }}
                            </div>
                        </td>

                        {{-- CUSTOMER --}}
                        <td>
                            {{ $order->user->name ?? 'Guest' }}
                            <div class="text-muted small">
                                {{ $order->user->email ?? '' }}
                            </div>
                        </td>

                        {{-- PRODUCT --}}
                        <td>
                            {{ $order->product->name ?? 'N/A' }}
                        </td>

                        {{-- TOKENS --}}
                        <td>
                            {{ $order->tokens_spent }} Tokens
                        </td>

                        {{-- STATUS --}}
                        <td>
                            <span class="badge rounded-pill px-3 py-2
                                {{
                                    $order->status === 'Pending' ? 'bg-warning-subtle text-warning' :
                                    ($order->status === 'Shipped' ? 'bg-primary-subtle text-primary' :
                                    ($order->status === 'Completed' ? 'bg-success-subtle text-success' :
                                    ($order->status === 'Cancelled' ? 'bg-danger-subtle text-danger' :
                                    'bg-secondary-subtle text-secondary')))
                                }}">
                                {{ $order->status }}
                            </span>
                        </td>

                        {{-- TRACKING --}}
                        <td class="small">
                            @if($order->tracking_number)
                                <span class="text-success">
                                    {{ $order->tracking_number }}
                                </span>
                            @else
                                <span class="text-muted">
                                    —
                                </span>
                            @endif
                        </td>

                        {{-- DATE --}}
                        <td class="text-muted small">
                            {{ $order->created_at->format('Y-m-d') }}
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-end pe-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-sm btn-light border shadow-sm"
                                   title="View order"
                                   aria-label="View order #{{ $order->id }}">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if($order->status !== 'Cancelled')
                                <form method="POST"
                                      action="{{ route('admin.orders.cancel', $order) }}"
                                      onsubmit="return confirm('Cancel this order?')"
                                      class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-light border text-danger shadow-sm"
                                            title="Cancel order"
                                            aria-label="Cancel order #{{ $order->id }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8"
                            class="text-center py-5 text-muted">
                            No orders found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($orders->hasPages())
        <div class="p-3 border-top">
            {{ $orders->withQueryString()->links() }}
        </div>
        @endif

    </div>

</div>

@endsection
