@extends('layouts.app')

@section('title', 'Sales Reports')
@section('body-class', 'has-sidebar')

@section('styles')
<style>
    .dashboard-layout { display: flex; min-height: 100vh; background: var(--neutral-50); overflow-x: hidden; }
    .sidebar { width: 280px; background: white; border-right: 1px solid var(--neutral-200); position: fixed; left: 0; top: 0; height: 100vh; overflow-y: auto; z-index: 1000; transition: transform 0.3s ease; }
    .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--neutral-200); background: linear-gradient(145deg, var(--primary) 0%, var(--primary-light) 100%); }
    .sidebar-brand { display: flex; align-items: center; gap: 0.75rem; color: white; }
    .brand-avatar { width: 44px; height: 44px; border-radius: 999px; object-fit: cover; border: 2px solid rgba(255,255,255,0.55); }
    .brand-avatar-placeholder { width: 44px; height: 44px; border-radius: 999px; background: rgba(255, 255, 255, 0.25); color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; border: 2px solid rgba(255,255,255,0.4); }
    .brand-meta { display: flex; flex-direction: column; line-height: 1.15; }
    .brand-text { font-size: 1.2rem; font-weight: 800; }
    .brand-subtext { font-size: 0.78rem; opacity: 0.9; font-weight: 600; }
    .sidebar-nav { padding: 1rem 0; }
    .nav-section { margin-bottom: 0.5rem; }
    .nav-section-title { padding: 0.75rem 1.5rem; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--neutral-500); }
    .nav-item { display: flex; align-items: center; padding: 0.9rem 1.5rem; color: var(--neutral-700); text-decoration: none; transition: all 0.2s; position: relative; }
    .nav-item:hover { background: var(--neutral-50); color: var(--primary); }
    .nav-item.active { background: rgba(30, 58, 95, 0.1); color: var(--primary); font-weight: 600; }
    .nav-item.active::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--primary); }
    .nav-item svg { width: 20px; height: 20px; margin-right: 0.75rem; }

    .main-content { margin-left: 280px; flex: 1; min-width: 0; padding: 2rem; }
    .hero { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #2d5a8c 100%); color: white; border-radius: 1.2rem; padding: 1.5rem 1.7rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-lg); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .hero-title { font-size: 1.9rem; margin-bottom: 0.25rem; }
    .hero-subtitle { opacity: 0.9; font-size: 0.95rem; }
    .filters { display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap; }
    .filters select { border: 1px solid rgba(255, 255, 255, 0.35); background: rgba(255, 255, 255, 0.12); color: white; border-radius: 0.7rem; padding: 0.6rem 0.7rem; }
    .filters select option { color: #111827; }
    .print-btn { border: 1px solid rgba(255, 255, 255, 0.45); background: rgba(255, 255, 255, 0.2); color: white; border-radius: 0.7rem; padding: 0.58rem 0.9rem; font-weight: 700; cursor: pointer; }
    .print-btn:hover { background: rgba(255, 255, 255, 0.3); }

    .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card { background: white; border-radius: 1rem; padding: 1.1rem; box-shadow: var(--shadow-sm); border: 1px solid var(--neutral-100); }
    .stat-label { font-size: 0.82rem; color: var(--neutral-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.65rem; font-weight: 800; color: var(--neutral-800); }

    .charts-grid { display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: 1rem; margin-bottom: 1rem; }
    .tables-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1rem; }
    .card { background: white; border-radius: 1rem; border: 1px solid var(--neutral-100); box-shadow: var(--shadow-sm); padding: 1.15rem; min-width: 0; }
    .card h2 { font-size: 1.08rem; color: var(--neutral-800); margin-bottom: 0.9rem; }
    .chart-wrap { height: clamp(240px, 40vh, 340px); min-width: 0; }

    .table-wrap { width: 100%; overflow-x: hidden; }
    .data-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .data-table th { text-align: left; padding: 0.72rem; font-size: 0.78rem; text-transform: uppercase; color: var(--neutral-500); background: var(--neutral-50); border-bottom: 2px solid var(--neutral-200); letter-spacing: 0.04em; }
    .data-table td { padding: 0.72rem; border-bottom: 1px solid var(--neutral-100); color: var(--neutral-700); font-size: 0.91rem; word-break: break-word; }
    .muted { color: var(--neutral-500); }
    .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 0.45rem; font-size: 0.75rem; font-weight: 700; background: rgba(30, 58, 95, 0.12); color: var(--primary); }
    .modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); z-index: 2000; align-items: center; justify-content: center; padding: 1rem; }
    .modal.active { display: flex; }
    .modal-content { background: white; border-radius: 1rem; width: min(520px, 100%); max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); }
    .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: space-between; }
    .modal-title { font-size: 1.2rem; font-weight: 700; color: var(--neutral-800); }
    .modal-close { width: 36px; height: 36px; border: none; background: var(--neutral-100); border-radius: 0.5rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; color: var(--neutral-600); }
    .modal-close:hover { background: var(--neutral-200); color: var(--neutral-800); }
    .modal-body { padding: 1.25rem 1.5rem; }
    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; }
    .modal-btn { border: 1px solid var(--neutral-200); background: white; color: var(--neutral-700); border-radius: 0.65rem; padding: 0.45rem 0.9rem; font-weight: 600; cursor: pointer; }
    .modal-btn.primary { background: var(--primary); color: white; border-color: var(--primary); }
    .modal-btn:hover { background: var(--neutral-100); }
    .modal-btn.primary:hover { background: var(--primary-dark); }
    .logout-card { display: flex; gap: 1rem; align-items: center; background: var(--neutral-50); padding: 1rem; border-radius: 0.85rem; border: 1px solid var(--neutral-200); }
    .logout-icon { width: 48px; height: 48px; border-radius: 0.8rem; background: rgba(30, 58, 95, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; }
    .logout-title { font-size: 1.05rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 0.2rem; }
    .logout-note { color: var(--neutral-600); font-size: 0.9rem; }

    .mobile-header { display: none; padding: 1rem; background: white; border-bottom: 1px solid var(--neutral-200); position: sticky; top: 0; z-index: 900; }
    .mobile-header-content { display: flex; align-items: center; justify-content: space-between; }
    .sidebar-toggle { width: 40px; height: 40px; border: none; background: var(--neutral-100); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.45); z-index: 999; }
    .sidebar-overlay.active { display: block; }

    @media (max-width: 1080px) {
        .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .charts-grid { grid-template-columns: 1fr; }
        .tables-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.active { transform: translateX(0); }
        .main-content { margin-left: 0; padding: 1rem; }
        .mobile-header { display: block; }
        .stats-grid { grid-template-columns: 1fr; }
        .hero { padding: 1.1rem 1rem; }
        .hero-title { font-size: 1.45rem; }
        .filters { width: 100%; }
        .filters select { width: 100%; }
        .data-table th, .data-table td { padding: 0.55rem; font-size: 0.8rem; }
    }
</style>
@endsection

@section('content')
<div class="dashboard-layout">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                @if(auth()->user()->profile_picture_url)
                    <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" class="brand-avatar">
                @else
                    <div class="brand-avatar-placeholder">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <div class="brand-meta">
                    <span class="brand-text">BuildWise</span>
                    <span class="brand-subtext">
                        {{ auth()->user()->name }}
                        @if(auth()->user()->isAdmin())
                            • Admin Dashboard
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <a href="{{ route('dashboard.overview') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('user.dashboard') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span>All Materials</span>
                </a>
                <a href="{{ route('dashboard.sales') }}" class="nav-item active">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span>Sales Reports</span>
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.staff-audit') }}" class="nav-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-5 6H2v-2a4 4 0 014-4h1m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 4a3 3 0 10-6 0 3 3 0 006 0z" /></svg>
                        <span>Staff Audit Logs</span>
                    </a>
                @endif
                <a href="{{ route('products.archived') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    <span>Archived</span>
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                <a href="{{ route('account.settings') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A11.956 11.956 0 0112 15.75c2.675 0 5.146.876 7.121 2.054M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Account Settings</span>
                </a>
                @if($isAdmin)
                    <a href="{{ route('admin.login-logs') }}" class="nav-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m0 0l3-3m-3 3l3 3m6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-6" /></svg>
                        <span>Login Logs</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="width: 100%;">
                    @csrf
                    <button type="button" class="nav-item" style="width: 100%; border: none; background: none; text-align: left;" onclick="openLogoutModal()">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main-content">
        <div class="mobile-header">
            <div class="mobile-header-content">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <span style="font-weight: 700;">Sales Reports</span>
                <div style="width: 40px;"></div>
            </div>
        </div>

        <section class="hero">
            <div>
                <h1 class="hero-title">Sales Dashboard</h1>
                <p class="hero-subtitle">Track orders, monitor performance, and review transactions by staff.</p>
            </div>
            <div class="filters">
                <select id="periodSelect">
                    <option value="7" {{ (int)$period === 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ (int)$period === 30 ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="90" {{ (int)$period === 90 ? 'selected' : '' }}>Last 90 Days</option>
                </select>
                @if($isAdmin)
                    <select id="staffSelect">
                        <option value="">All Staff</option>
                        @foreach($staffUsers as $staff)
                            <option value="{{ $staff->id }}" {{ (string)$userId === (string)$staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                        @endforeach
                    </select>
                @else
                    <span class="badge" style="background: rgba(255,255,255,0.18); color: white;">Your Sales Only</span>
                @endif
                <button type="button" class="print-btn" onclick="printReport()">Print Report</button>
            </div>
        </section>

        <section class="stats-grid">
            <article class="stat-card">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">PHP {{ number_format($totalSales, 2) }}</div>
            </article>
            <article class="stat-card">
                <div class="stat-label">Items Sold</div>
                <div class="stat-value">{{ number_format($totalItemsSold) }}</div>
            </article>
            <article class="stat-card">
                <div class="stat-label">Orders</div>
                <div class="stat-value">{{ number_format($transactionCount) }}</div>
            </article>
            <article class="stat-card">
                <div class="stat-label">Average Order</div>
                <div class="stat-value">PHP {{ number_format($averageTransaction, 2) }}</div>
            </article>
        </section>

        <section class="charts-grid">
            <article class="card">
                <h2>Sales Trend</h2>
                <div class="chart-wrap"><canvas id="salesChart"></canvas></div>
            </article>
            <article class="card">
                <h2>Sales by Category</h2>
                <div class="chart-wrap"><canvas id="categoryChart"></canvas></div>
            </article>
        </section>

        <section class="card" style="margin-bottom: 1rem;">
            <h2>Orders by Staff</h2>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Orders</th>
                            <th>Items</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesByStaff as $staff)
                            <tr>
                                <td>{{ $staff->user_name }}</td>
                                <td>{{ $staff->orders_count }}</td>
                                <td>{{ $staff->total_items }}</td>
                                <td>PHP {{ number_format($staff->total_revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="muted">No staff sales data for this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="tables-grid">
            <article class="card">
                <h2>Top Selling Materials</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td>{{ $item->total_quantity }}</td>
                                    <td>PHP {{ number_format($item->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="muted">No sales data available.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="card">
                <h2>Recent Orders</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Staff</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td>
                                        <span class="badge">{{ $sale->order_number ?? ('SALE-' . $sale->id) }}</span>
                                        <div class="muted" style="font-size: 0.78rem;">{{ $sale->sale_date->format('M d, Y h:i A') }}</div>
                                    </td>
                                    <td>{{ $sale->product->name ?? 'N/A' }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                    <td>PHP {{ number_format($sale->total_price, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="muted">No recent transactions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </main>
</div>

<div class="modal" id="logoutModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Confirm Logout</div>
            <button class="modal-close" onclick="closeLogoutModal()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="logout-card">
                <div class="logout-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <div>
                    <div class="logout-title">Ready to sign out?</div>
                    <div class="logout-note">You are signed in as {{ $isAdmin ? 'Administrator' : 'Staff' }}. You will need to log in again to continue.</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn" onclick="closeLogoutModal()">Cancel</button>
            <button type="button" class="modal-btn primary" onclick="document.getElementById('logoutForm').submit()">Logout</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script src="{{ asset('js/inventory-v2.js') }}"></script>
<script>
    function applyFilters() {
        const period = document.getElementById('periodSelect').value;
        const staffSelect = document.getElementById('staffSelect');
        const userId = staffSelect ? staffSelect.value : '';
        const params = new URLSearchParams();
        params.set('period', period);
        if (userId) {
            params.set('user_id', userId);
        }
        window.location.href = `{{ route('dashboard.sales') }}?${params.toString()}`;
    }

    function printReport() {
        const period = document.getElementById('periodSelect').value;
        const staffSelect = document.getElementById('staffSelect');
        const userId = staffSelect ? staffSelect.value : '';
        const params = new URLSearchParams();
        params.set('period', period);
        if (userId) {
            params.set('user_id', userId);
        }
        window.open(`{{ route('dashboard.sales.print') }}?${params.toString()}`, '_blank');
    }

    document.getElementById('periodSelect').addEventListener('change', applyFilters);
    const staffSelect = document.getElementById('staffSelect');
    if (staffSelect) {
        staffSelect.addEventListener('change', applyFilters);
    }

    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($salesByDay->pluck('date')),
            datasets: [{
                label: 'Daily Revenue',
                data: @json($salesByDay->pluck('total')),
                borderColor: 'rgba(30, 58, 95, 1)',
                backgroundColor: 'rgba(30, 58, 95, 0.12)',
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true } },
            scales: { y: { beginAtZero: true } }
        }
    });

    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($salesByCategory->pluck('category')),
            datasets: [{
                data: @json($salesByCategory->pluck('total')),
                backgroundColor: [
                    'rgba(30, 58, 95, 0.85)',
                    'rgba(217, 119, 6, 0.85)',
                    'rgba(5, 150, 105, 0.85)',
                    'rgba(220, 38, 38, 0.85)',
                    'rgba(99, 102, 241, 0.85)'
                ]
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection


