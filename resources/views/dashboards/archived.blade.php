@extends('layouts.app')

@section('title', 'Archived Products')

@section('styles')
<style>
    .dashboard-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--neutral-50) 0%, var(--neutral-100) 100%);
    }

    .navbar {
        background: white;
        border-bottom: 1px solid var(--neutral-200);
        padding: 1rem 0;
        box-shadow: var(--shadow-sm);
    }

    .navbar-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .brand-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-icon svg {
        width: 24px;
        height: 24px;
        stroke: white;
    }

    .brand-text {
        font-size: 1.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: white;
        color: var(--neutral-700);
        border: 2px solid var(--neutral-200);
        border-radius: 0.75rem;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .back-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .back-btn svg {
        width: 20px;
        height: 20px;
    }

    .dashboard-content {
        max-width: 1600px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        color: var(--neutral-800);
    }
    .page-subtitle {
        color: var(--neutral-500);
        font-size: 0.95rem;
        margin-top: 0.35rem;
    }

    .inventory-card {
        background: white;
        border-radius: 1.25rem;
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .inventory-table {
        width: 100%;
        border-collapse: collapse;
    }

    .inventory-table thead {
        background: linear-gradient(135deg, var(--neutral-50) 0%, var(--neutral-100) 100%);
    }

    .inventory-table th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 700;
        color: var(--neutral-700);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--neutral-200);
    }

    .inventory-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--neutral-100);
        color: var(--neutral-700);
    }

    .inventory-table tbody tr {
        transition: all 0.2s;
        opacity: 0.7;
    }

    .inventory-table tbody tr:hover {
        background: var(--neutral-50);
        opacity: 1;
    }

    .product-name {
        font-weight: 600;
        color: var(--neutral-800);
        margin-bottom: 0.25rem;
    }

    .product-sku {
        font-size: 0.85rem;
        color: var(--neutral-500);
    }

    .product-price {
        font-weight: 700;
        color: var(--neutral-600);
        font-size: 1.1rem;
    }

    .archived-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(100, 116, 139, 0.1);
        color: var(--neutral-600);
    }

    .archived-badge svg {
        width: 16px;
        height: 16px;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--neutral-100);
        color: var(--neutral-600);
    }

    .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .btn-icon.restore:hover {
        background: rgba(5, 150, 105, 0.1);
        color: var(--accent);
    }

    .btn-icon svg {
        width: 18px;
        height: 18px;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--neutral-100) 0%, var(--neutral-200) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon svg {
        width: 40px;
        height: 40px;
        stroke: var(--neutral-400);
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--neutral-800);
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--neutral-600);
    }
</style>
@endsection

@section('content')
<div class="dashboard-wrapper" data-live-logs-root data-refresh-seconds="30">
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <div class="brand-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="brand-text">BuildWise</span>
            </div>


                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="back-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Inventory
                </a>

        </div>
    </nav>

    <div class="dashboard-content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Archived Materials</h1>
                <div class="page-subtitle">Philippines Time: <span data-ph-now></span></div>
            </div>
        </div>

        <div class="inventory-card">
            @if($products->count() > 0)
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Last Stock</th>
                            <th>Archived Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-sku">SKU: {{ $product->sku }}</div>
                            </td>
                            <td>{{ $product->category ?? 'Uncategorized' }}</td>
                            <td>
                                <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                            </td>
                            <td>{{ $product->quantity }} units</td>
                            <td>
                                <span class="archived-badge">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                    <span data-log-timestamp="{{ $product->deleted_at?->toIso8601String() }}">
                                        {{ $product->deleted_at->timezone(config('app.timezone'))->format('M d, Y h:i A') }}
                                    </span>
                                </span>
                            </td>
                            <td>
                                <button class="btn-icon restore" onclick="restoreProduct({{ $product->id }}, '{{ $product->name }}')" title="Restore product">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($products->hasPages())
                    <div class="pagination">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="empty-title">No Archived Materials</h3>
                    <p class="empty-text">You haven't archived any materials yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function restoreProduct(id, name) {
        if (confirm(`Restore "${name}" back to active inventory?`)) {
            fetch(`/products/${id}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while restoring the product.');
            });
        }
    }
</script>
@endsection
