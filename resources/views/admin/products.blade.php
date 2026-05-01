@extends('layouts.admin')

@section('title', 'Products')
@section('header', 'Product Management')

@section('content')

<div class="container-fluid">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">Products</h4>
            <small class="text-muted">Manage inventory, pricing and availability</small>
        </div>

        <form method="GET" action="#" class="d-flex gap-2">

            <select name="status" class="form-select form-select-sm" style="width:160px;">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control form-control-sm"
                   placeholder="Search product..."
                   style="width:220px;">

            <button type="submit" class="btn btn-outline-secondary btn-sm">
                Filter
            </button>

            <button type="button"
                    class="btn btn-primary btn-sm shadow-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#createProductModal">
                + Add Product
            </button>

        </form>

    </div>


    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="table-responsive">

            <table class="table align-middle table-hover mb-0">

                <thead class="bg-light border-bottom">
                    <tr class="text-uppercase small text-muted">
                        <th class="px-4 py-3">Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($products as $product)

                    <tr class="border-bottom">

                        {{-- PRODUCT INFO --}}
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:64px; height:64px;" class="rounded overflow-hidden border bg-light flex-shrink-0">
                                    @if(!empty($product->image_url))
                                        <img src="{{ $product->image_url }}"
                                             alt="{{ $product->name }}"
                                             class="w-100 h-100"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted small">
                                            No image
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <div class="text-muted small">ID: #{{ $product->id }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- PRICE --}}
                        <td class="fw-semibold">
                            ${{ number_format($product->token_cost ?? 0, 2) }}
                        </td>

                        {{-- STOCK --}}
                        <td>
                            @if($product->stock > 20)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                    {{ $product->stock }} In Stock
                                </span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">
                                    {{ $product->stock }} Low
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                                    Out of Stock
                                </span>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($product->is_active)
                                <span class="badge rounded-pill px-3 py-2 bg-success-subtle text-success border border-success-subtle">
                                    Active
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-2 bg-danger-subtle text-danger border border-danger-subtle">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        {{-- CREATED --}}
                        <td class="text-muted small">
                            {{ $product->created_at?->format('Y-m-d') }}
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-end pe-4">

                            <div class="d-flex justify-content-end gap-2">

                                <a href="{{ route('admin.products.show', $product->id) }}"
                                   class="btn btn-sm btn-light border shadow-sm">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="btn btn-sm btn-light border text-primary shadow-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.products.destroy', $product->id) }}"
                                      onsubmit="return confirm('Delete this product?')"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-light border text-danger shadow-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            No products found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($products->hasPages())
        <div class="p-3 border-top">
            {{ $products->withQueryString()->links() }}
        </div>
        @endif

    </div>

</div>



{{-- CREATE PRODUCT MODAL --}}
<div class="modal fade" id="createProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">

            <form method="POST" action="{{ route('admin.products.store') }}">
                @csrf

                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Token Cost</label>
                            <input type="number" name="token_cost" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Image URL (optional)</label>
                            <input type="url"
                                   name="image_url"
                                   class="form-control"
                                   placeholder="Leave blank to auto-fetch from Pexels">
                            <small class="text-muted">
                                If empty, the system will try to fetch an image by product name.
                            </small>
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Create Product
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
