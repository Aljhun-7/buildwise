@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('body-class', 'has-sidebar')

@section('styles')
<style>
    /* Copy all sidebar styles from inventory.blade.php */
    .inventory-layout { display: flex; min-height: 100vh; background: var(--neutral-50); }
    .sidebar { width: 280px; background: white; border-right: 1px solid var(--neutral-200); position: fixed; left: 0; top: 0; height: 100vh; overflow-y: auto; z-index: 1000; transition: transform 0.3s ease; }
    .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--neutral-200); background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); }
    .sidebar-brand { display: flex; align-items: center; gap: 0.75rem; color: white; }
    .brand-avatar { width: 44px; height: 44px; border-radius: 999px; object-fit: cover; border: 2px solid rgba(255,255,255,0.55); }
    .brand-avatar-placeholder { width: 44px; height: 44px; border-radius: 999px; background: rgba(255, 255, 255, 0.25); color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; border: 2px solid rgba(255,255,255,0.4); }
    .brand-meta { display: flex; flex-direction: column; line-height: 1.15; }
    .brand-text { font-size: 1.2rem; font-weight: 800; }
    .brand-subtext { font-size: 0.78rem; opacity: 0.9; font-weight: 600; }
    .sidebar-search { padding: 1rem 1.5rem; border-bottom: 1px solid var(--neutral-200); }
    .search-box { position: relative; }
    .search-box input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border: 2px solid var(--neutral-200); border-radius: 0.75rem; font-size: 0.9rem; transition: all 0.3s; }
    .search-box input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.1); }
    .search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--neutral-400); }
    .search-icon svg { width: 18px; height: 18px; }
    .sidebar-nav { padding: 1rem 0; }
    .nav-section { margin-bottom: 0.5rem; }
    .nav-section-title { padding: 0.75rem 1.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--neutral-500); }
    .nav-item { display: flex; align-items: center; padding: 0.875rem 1.5rem; color: var(--neutral-700); text-decoration: none; transition: all 0.2s; position: relative; cursor: pointer; }
    .nav-item:hover { background: var(--neutral-50); color: var(--primary); }
    .nav-item.active { background: rgba(30, 58, 95, 0.1); color: var(--primary); font-weight: 600; }
    .nav-item.active::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--primary); }
    .nav-item svg { width: 20px; height: 20px; margin-right: 0.75rem; }
    .nav-item-text { flex: 1; }
    .category-dropdown { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .category-dropdown.active { max-height: 600px; }
    .category-item { padding: 0.75rem 1.5rem 0.75rem 3.5rem; color: var(--neutral-600); font-size: 0.9rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: space-between; }
    .category-item:hover { background: var(--neutral-50); color: var(--primary); }
    .category-count { background: var(--neutral-200); color: var(--neutral-600); padding: 0.125rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; }
    .dropdown-arrow { margin-left: auto; transition: transform 0.3s; }
    .dropdown-arrow svg { width: 16px; height: 16px; }
    .main-content { margin-left: 280px; flex: 1; padding: 2rem; }
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #2d5a8c 100%); border-radius: 1rem; color: #fff; padding: 1.35rem 1.5rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-lg); }
    .page-hero-title { font-size: 1.7rem; margin-bottom: 0.2rem; }
    .page-hero-sub { opacity: 0.9; font-size: 0.95rem; }
    .page-hero-live { margin-top: 0.65rem; font-size: 0.88rem; opacity: 0.95; }

    /* Stats Cards */
    .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow-sm); transition: transform 0.3s; }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-icon { width: 50px; height: 50px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1rem; }
    .stat-icon.blue { background: rgba(30, 58, 95, 0.1); color: var(--primary); }
    .stat-icon.yellow { background: rgba(217, 119, 6, 0.1); color: var(--warning); }
    .stat-icon.red { background: rgba(220, 38, 38, 0.1); color: var(--error); }
    .stat-icon.green { background: rgba(5, 150, 105, 0.1); color: var(--accent); }
    .stat-value { font-size: 2rem; font-weight: 800; color: var(--neutral-800); margin-bottom: 0.5rem; }
    .stat-label { color: var(--neutral-600); font-size: 0.9rem; margin-bottom: 0.5rem; }
    .stat-link { color: var(--primary); text-decoration: none; font-size: 0.85rem; font-weight: 600; }
    .stat-link:hover { text-decoration: underline; }

    /* Section Cards */
    .section-card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: var(--shadow-sm); margin-bottom: 1.5rem; }
    .section-card h2 { font-size: 1.25rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 1.5rem; }

    /* Low Stock Table */
    .low-stock-table { width: 100%; }
    .low-stock-row { display: flex; align-items: center; justify-content: space-between; padding: 1rem; border-bottom: 1px solid var(--neutral-100); }
    .low-stock-row:hover { background: var(--neutral-50); }
    .product-info-small { flex: 1; }
    .product-name-small { font-weight: 600; color: var(--neutral-800); }
    .product-sku-small { font-size: 0.85rem; color: var(--neutral-500); }
    .stock-warning { font-weight: 700; color: var(--warning); }

    /* Activity Timeline */
    .activity-timeline { display: flex; flex-direction: column; gap: 1rem; }
    .activity-item { display: flex; gap: 1rem; padding: 1rem; background: var(--neutral-50); border-radius: 0.75rem; transition: all 0.2s; }
    .activity-item:hover { background: var(--neutral-100); }
    .activity-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .activity-icon.created { background: rgba(5, 150, 105, 0.2); color: var(--accent); }
    .activity-icon.updated { background: rgba(30, 58, 95, 0.2); color: var(--primary); }
    .activity-icon.archived { background: rgba(217, 119, 6, 0.2); color: var(--warning); }
    .activity-icon.restored { background: rgba(16, 185, 129, 0.2); color: var(--accent-light); }
    .activity-icon.sold { background: rgba(5, 150, 105, 0.2); color: var(--accent); }
    .activity-icon.login { background: rgba(30, 58, 95, 0.2); color: var(--primary); }
    .activity-icon svg { width: 20px; height: 20px; }
    .activity-details { flex: 1; }
    .activity-title { font-weight: 700; color: var(--neutral-800); margin-bottom: 0.25rem; }
    .activity-description { color: var(--neutral-600); font-size: 0.9rem; line-height: 1.5; margin-bottom: 0.5rem; }
    .activity-meta { display: flex; align-items: center; gap: 1rem; font-size: 0.85rem; color: var(--neutral-500); }
    .activity-extra { margin-top: 1rem; display: none; }
    .activity-extra.active { display: flex; flex-direction: column; gap: 1rem; }
    .activity-toggle { margin-top: 1rem; background: var(--neutral-100); border: 1px solid var(--neutral-300); color: var(--neutral-700); border-radius: 0.6rem; padding: 0.55rem 0.9rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; }
    .activity-toggle:hover { background: var(--neutral-200); }
    .section-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .section-actions { display: inline-flex; align-items: center; gap: 0.5rem; }
    .action-btn-small { border: 1px solid var(--neutral-200); background: white; color: var(--neutral-700); border-radius: 0.65rem; padding: 0.45rem 0.85rem; font-weight: 600; cursor: pointer; }
    .action-btn-small.primary { background: var(--primary); color: white; border-color: var(--primary); }
    .action-btn-small:hover { background: var(--neutral-100); }
    .action-btn-small.primary:hover { background: var(--primary-dark); }

    /* Modal */
    .modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); z-index: 2000; align-items: center; justify-content: center; padding: 1rem; }
    .modal.active { display: flex; }
    .modal-content { background: white; border-radius: 1rem; width: min(900px, 100%); max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); }
    .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: space-between; }
    .modal-title { font-size: 1.2rem; font-weight: 700; color: var(--neutral-800); }
    .modal-close { width: 36px; height: 36px; border: none; background: var(--neutral-100); border-radius: 0.5rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; color: var(--neutral-600); }
    .modal-close:hover { background: var(--neutral-200); color: var(--neutral-800); }
    .modal-body { padding: 1.25rem 1.5rem; }
    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; }
    .log-table { width: 100%; border-collapse: collapse; }
    .log-table th { text-align: left; font-size: 0.8rem; color: var(--neutral-500); text-transform: uppercase; letter-spacing: 0.06em; padding: 0.75rem; border-bottom: 1px solid var(--neutral-200); }
    .log-table td { padding: 0.75rem; border-bottom: 1px solid var(--neutral-100); font-size: 0.92rem; color: var(--neutral-700); vertical-align: top; }
    .log-empty { text-align: center; color: var(--neutral-500); padding: 1.5rem; }
    .logout-card { display: flex; gap: 1rem; align-items: center; background: var(--neutral-50); padding: 1rem; border-radius: 0.85rem; border: 1px solid var(--neutral-200); }
    .logout-icon { width: 48px; height: 48px; border-radius: 0.8rem; background: rgba(30, 58, 95, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; }
    .logout-title { font-size: 1.05rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 0.2rem; }
    .logout-note { color: var(--neutral-600); font-size: 0.9rem; }

    /* Chart Container */
    .chart-container { position: relative; height: 300px; }

    /* Mobile */
    .mobile-header { display: none; padding: 1rem; background: white; border-bottom: 1px solid var(--neutral-200); position: sticky; top: 0; z-index: 999; }
    .mobile-header-content { display: flex; align-items: center; justify-content: space-between; }
    .sidebar-toggle { width: 40px; height: 40px; border: none; background: var(--neutral-100); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 999; }
    .sidebar-overlay.active { display: block; }

    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.active { transform: translateX(0); }
        .main-content { margin-left: 0; padding: 1rem; }
        .mobile-header { display: block; }
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="inventory-layout" data-live-logs-root data-refresh-seconds="30">
    <!-- Sidebar -->
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
                <a href="{{ route('dashboard.overview') }}" class="nav-item active">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="nav-item-text">Dashboard</span>
                </a>
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="nav-item-text">All Materials</span>
                </a>
                <a href="{{ route('dashboard.sales') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="nav-item-text">Sales Reports</span>
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.staff-audit') }}" class="nav-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-5 6H2v-2a4 4 0 014-4h1m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 4a3 3 0 10-6 0 3 3 0 006 0z" />
                        </svg>
                        <span class="nav-item-text">Staff Audit Logs</span>
                    </a>
                @endif
                <a href="{{ route('products.archived') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    <span class="nav-item-text">Archived</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                <a href="{{ route('account.settings') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A11.956 11.956 0 0112 15.75c2.675 0 5.146.876 7.121 2.054M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="nav-item-text">Account Settings</span>
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.login-logs') }}" class="nav-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m0 0l3-3m-3 3l3 3m6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-6" />
                        </svg>
                        <span class="nav-item-text">Login Logs</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: inline; width: 100%;">
                    @csrf
                    <button type="button" class="nav-item" style="width: 100%; background: none; border: none; text-align: left;" onclick="openLogoutModal()">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="nav-item-text">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="mobile-header">
            <div class="mobile-header-content">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span style="font-weight: 700; color: var(--neutral-800);">Dashboard</span>
                <div style="width: 40px;"></div>
            </div>
        </div>

        <section class="page-hero">
            <h1 class="page-hero-title">Dashboard Overview</h1>
            <p class="page-hero-sub">Real-time inventory health, activity, and stock alerts in one view.</p>
            <div class="page-hero-live">Philippines Time: <span data-ph-now></span></div>
        </section>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Materials</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon yellow">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="stat-value">{{ $lowStockCount }}</div>
                <div class="stat-label">Low Stock Items</div>
                <a href="#low-stock" class="stat-link">View Details →</a>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="stat-value">{{ $outOfStockCount }}</div>
                <div class="stat-label">Out of Stock</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-value">₱{{ number_format($totalValue, 2) }}</div>
                <div class="stat-label">Total Inventory Value</div>
            </div>
        </div>

        <!-- Low Stock Products -->
        @if($lowStockProducts->count() > 0)
        <div class="section-card" id="low-stock">
            <h2>⚠️ Low Stock Alert ({{ $lowStockProducts->count() }} items)</h2>
            <div class="low-stock-table">
                @foreach($lowStockProducts as $product)
                <div class="low-stock-row">
                    <div class="product-info-small">
                        <div class="product-name-small">{{ $product->name }}</div>
                        <div class="product-sku-small">SKU: {{ $product->sku }}</div>
                    </div>
                    <div class="stock-warning">{{ $product->quantity }} units left</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        <div class="section-card">
            <div class="section-header">
                <h2>Recent Activity</h2>
                <div class="section-actions">
                    <button type="button" class="action-btn-small" onclick="openModal('activityModal')">View All</button>
                    <button type="button" class="action-btn-small primary" onclick="printModal('activityPrint', 'Recent Activity')">Print</button>
                </div>
            </div>
            <div class="activity-timeline">
                @forelse($recentLogsPreview as $log)
                <div class="activity-item">
                    <div class="activity-icon {{ $log->icon_action }}">
                        @if($log->icon_action == 'created')
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        @elseif($log->icon_action == 'updated')
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        @elseif($log->icon_action == 'archived')
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        @elseif($log->icon_action == 'restored')
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        @elseif($log->icon_action == 'login')
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m0 0l3-3m-3 3l3 3m6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-6" />
                            </svg>
                        @else
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                    <div class="activity-details">
                        <div class="activity-title">{{ $log->title }}</div>
                        <div class="activity-description">{{ $log->description }}</div>
                        <div class="activity-meta">
                            <span>{{ $log->user_name }}</span>
                            <span data-log-timestamp="{{ $log->happened_at?->toIso8601String() }}">{{ $log->happened_at->timezone(config('app.timezone'))->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p style="text-align: center; color: var(--neutral-500); padding: 2rem;">No activity yet</p>
                @endforelse
            </div>
        </div>

        <!-- Login Logs -->
        <div class="section-card">
            <div class="section-header">
                <h2>Login Logs</h2>
                <div class="section-actions">
                    <button type="button" class="action-btn-small" onclick="openModal('loginModal')">View All</button>
                    <button type="button" class="action-btn-small primary" onclick="printModal('loginPrint', 'Login Logs')">Print</button>
                </div>
            </div>
            <table class="log-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loginLogsPreview as $log)
                    <tr>
                        <td>{{ $log->user->name ?? 'Unknown User' }}</td>
                        <td>{{ $log->ip_address ?? '—' }}</td>
                        <td><span data-log-timestamp="{{ optional($log->logged_in_at ?? $log->created_at)?->toIso8601String() }}">{{ ($log->logged_in_at ?? $log->created_at)->timezone(config('app.timezone'))->format('M d, Y h:i A') }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="log-empty">No login logs yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Products by Category -->
        <div class="section-card">
            <h2>Materials by Category</h2>
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </main>
</div>

<div class="modal" id="logoutModal">
    <div class="modal-content" style="max-width: 520px;">
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
                    <div class="logout-note">
                        You are signed in as {{ auth()->user()->isAdmin() ? 'Administrator' : 'Staff' }}. You will need to log in again to continue.
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="action-btn-small" onclick="closeLogoutModal()">Cancel</button>
            <button type="button" class="action-btn-small primary" onclick="document.getElementById('logoutForm').submit()">Logout</button>
        </div>
    </div>
</div>

<!-- Activity Modal -->
<div class="modal" id="activityModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Recent Activity</div>
            <button class="modal-close" onclick="closeModal('activityModal')">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body" id="activityPrint">
            <table class="log-table">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>User</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogsPreview->merge($recentLogsRemaining) as $log)
                    <tr>
                        <td>
                            <strong>{{ $log->title }}</strong><br>
                            <span style="color: var(--neutral-500); font-size: 0.85rem;">{{ $log->description }}</span>
                        </td>
                        <td>{{ $log->user_name }}</td>
                        <td><span data-log-timestamp="{{ $log->happened_at?->toIso8601String() }}">{{ $log->happened_at->timezone(config('app.timezone'))->format('M d, Y h:i A') }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="log-empty">No activity yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="action-btn-small" onclick="closeModal('activityModal')">Close</button>
            <button type="button" class="action-btn-small primary" onclick="printModal('activityPrint', 'Recent Activity')">Print</button>
        </div>
    </div>
</div>

<!-- Login Logs Modal -->
<div class="modal" id="loginModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Login Logs</div>
            <button class="modal-close" onclick="closeModal('loginModal')">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body" id="loginPrint">
            <table class="log-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLoginLogs as $log)
                    <tr>
                        <td>{{ $log->user->name ?? 'Unknown User' }}</td>
                        <td>{{ $log->ip_address ?? '—' }}</td>
                        <td><span data-log-timestamp="{{ optional($log->logged_in_at ?? $log->created_at)?->toIso8601String() }}">{{ ($log->logged_in_at ?? $log->created_at)->timezone(config('app.timezone'))->format('M d, Y h:i A') }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="log-empty">No login logs yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="action-btn-small" onclick="closeModal('loginModal')">Close</button>
            <button type="button" class="action-btn-small primary" onclick="printModal('loginPrint', 'Login Logs')">Print</button>
        </div>
    </div>
</div>

<script type="application/json" id="categoryChartData">
@json(['labels' => $productsByCategory->pluck('category'), 'counts' => $productsByCategory->pluck('count')])
</script>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script src="{{ asset('js/inventory-v2.js') }}"></script>
<script src="{{ asset('js/overview.js') }}"></script>
@endsection


