@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('body-class', 'has-sidebar')

@section('styles')
<style>
    /* Sidebar and Layout Styles - Keep all existing styles */
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
    /* Category Pills */
    .category-pills-bar { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--neutral-200); }
    .category-pill { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s; border: 2px solid var(--neutral-200); background: white; color: var(--neutral-600); white-space: nowrap; }
    .category-pill:hover { border-color: var(--primary); color: var(--primary); background: rgba(30, 58, 95, 0.05); }
    .category-pill.active { background: var(--primary); border-color: var(--primary); color: white; }
    .pill-count { font-size: 0.7rem; background: rgba(255,255,255,0.25); padding: 0.1rem 0.45rem; border-radius: 999px; font-weight: 700; }
    .category-pill:not(.active) .pill-count { background: var(--neutral-100); color: var(--neutral-500); }
    .main-content { margin-left: 280px; flex: 1; padding: 2rem; }
    .content-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap; }
    .page-title { font-size: 2rem; color: var(--neutral-800); }
    .header-actions { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }

    /* Header inline search */
    .header-search-box { position: relative; display: flex; align-items: center; width: clamp(240px, 32vw, 360px); }
    .header-search-box:focus-within .header-search-icon { color: var(--primary); }
    .header-search-icon { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--neutral-400); pointer-events: none; z-index: 1; }
    .header-search-icon svg { width: 16px; height: 16px; }
    .header-search-input { height: 42px; width: 100%; padding: 0 2.35rem 0 2.5rem !important; border: 2px solid var(--neutral-200); border-radius: 0.75rem; font-size: 0.9rem; font-family: 'DM Sans', sans-serif; background: white; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; color: var(--neutral-800); }
    .header-search-input::placeholder { color: var(--neutral-400); }
    .header-search-input:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.1); }
    .header-search-clear { position: absolute; right: 0.6rem; top: 50%; transform: translateY(-50%); width: 22px; height: 22px; border: none; background: var(--neutral-300); color: white; border-radius: 50%; cursor: pointer; align-items: center; justify-content: center; padding: 0; transition: background 0.2s; }
    .header-search-clear:hover { background: var(--neutral-500); }
    .header-search-clear svg { width: 11px; height: 11px; }

    /* Product Grid */
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; }
    .product-card { background: white; border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow-sm); transition: all 0.3s; position: relative; }
    .product-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
    .product-image { width: 100%; height: 200px; object-fit: cover; background: var(--neutral-100); display: flex; align-items: center; justify-content: center; position: relative; }
    .product-image img { width: 100%; height: 100%; object-fit: cover; }
    .product-placeholder { color: var(--neutral-300); }
    .product-placeholder svg { width: 60px; height: 60px; }
    .stock-badge { position: absolute; top: 0.75rem; right: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; gap: 0.25rem; backdrop-filter: blur(10px); }
    .stock-badge svg { width: 14px; height: 14px; }
    .stock-badge.in-stock { background: rgba(5, 150, 105, 0.9); color: white; }
    .stock-badge.low-stock { background: rgba(217, 119, 6, 0.9); color: white; }
    .stock-badge.out-stock { background: rgba(220, 38, 38, 0.9); color: white; }
    .product-info { padding: 1.25rem; }
    .product-category { font-size: 0.75rem; color: var(--neutral-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .product-name { font-size: 1.1rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 0.5rem; }
    .product-sku { font-size: 0.85rem; color: var(--neutral-500); margin-bottom: 0.75rem; }
    .product-price { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 1rem; }
    .product-stock { font-size: 0.9rem; color: var(--neutral-600); margin-bottom: 1rem; }
    .product-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }
    .action-btn { padding: 0.5rem; border: none; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; background: var(--neutral-100); color: var(--neutral-600); }
    .action-btn:hover { transform: translateY(-2px); }
    .action-btn svg { width: 18px; height: 18px; }
    .action-btn.sell { background: rgba(5, 150, 105, 0.1); color: var(--accent); }
    .action-btn.sell:hover { background: var(--accent); color: white; }
    .action-btn.edit { background: rgba(30, 58, 95, 0.1); color: var(--primary); }
    .action-btn.edit:hover { background: var(--primary); color: white; }
    .action-btn.delete { background: rgba(220, 38, 38, 0.1); color: var(--error); }
    .action-btn.delete:hover { background: var(--error); color: white; }

    /* Modal Styles */
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .modal.active { display: flex; }
    .modal-content { background: white; border-radius: 1.25rem; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .modal-header { padding: 2rem; border-bottom: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: space-between; }
    .modal-title { font-size: 1.5rem; font-weight: 700; color: var(--neutral-800); }
    .modal-close { width: 36px; height: 36px; border: none; background: var(--neutral-100); border-radius: 0.5rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; color: var(--neutral-600); }
    .modal-close:hover { background: var(--neutral-200); color: var(--neutral-800); }
    .modal-close svg { width: 20px; height: 20px; }
    .modal-body { padding: 2rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group.full-width { grid-column: 1 / -1; }
    label { display: block; font-weight: 600; color: var(--neutral-700); margin-bottom: 0.5rem; font-size: 0.9rem; }
    input[type="text"], input[type="number"], textarea, select { width: 100%; padding: 0.875rem 1rem; border: 2px solid var(--neutral-200); border-radius: 0.75rem; font-size: 0.95rem; font-family: 'DM Sans', sans-serif; transition: all 0.3s ease; background: var(--neutral-50); }
    textarea { min-height: 100px; resize: vertical; }
    input:focus, textarea:focus, select:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.1); }
    .modal-footer { padding: 1.5rem 2rem; border-top: 1px solid var(--neutral-200); display: flex; gap: 1rem; justify-content: flex-end; }
    .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 0.75rem; font-size: 0.95rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; }
    .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(30, 58, 95, 0.4); }
    .btn-secondary { background: white; color: var(--neutral-700); border: 2px solid var(--neutral-200); }
    .btn-secondary:hover { border-color: var(--primary); color: var(--primary); }
    .btn svg { width: 20px; height: 20px; }
    .logout-card { display: flex; gap: 1rem; align-items: center; background: var(--neutral-50); padding: 1rem; border-radius: 0.85rem; border: 1px solid var(--neutral-200); }
    .logout-icon { width: 48px; height: 48px; border-radius: 0.8rem; background: rgba(30, 58, 95, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; }
    .logout-title { font-size: 1.05rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 0.2rem; }
    .logout-note { color: var(--neutral-600); font-size: 0.9rem; }

    /* Image Upload */
    .image-preview-container { position: relative; width: 200px; height: 200px; border: 2px dashed var(--neutral-300); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; overflow: hidden; background: var(--neutral-50); cursor: pointer; transition: all 0.3s; }
    .image-preview-container:hover { border-color: var(--primary); background: rgba(30, 58, 95, 0.05); }
    .image-preview-container.has-image { border-style: solid; border-color: var(--primary); }
    .image-preview { width: 100%; height: 100%; object-fit: cover; display: none; }
    .image-preview.active { display: block; }
    .image-placeholder { text-align: center; color: var(--neutral-500); }
    .image-placeholder svg { width: 48px; height: 48px; margin-bottom: 0.5rem; stroke: var(--neutral-400); }
    .image-remove-btn { position: absolute; top: 0.5rem; right: 0.5rem; width: 32px; height: 32px; background: var(--error); color: white; border: none; border-radius: 50%; display: none; align-items: center; justify-content: center; cursor: pointer; }
    .image-remove-btn.active { display: flex; }
    .image-remove-btn svg { width: 16px; height: 16px; }
    .file-input-hidden { display: none; }

    /* Empty State */
    .empty-state { text-align: center; padding: 4rem 2rem; background: white; border-radius: 1rem; margin-top: 2rem; }
    .empty-icon { width: 80px; height: 80px; margin: 0 auto 1.5rem; background: var(--neutral-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .empty-icon svg { width: 40px; height: 40px; stroke: var(--neutral-400); }

    /* Mobile */
    .mobile-header { display: none; padding: 1rem; background: white; border-bottom: 1px solid var(--neutral-200); position: sticky; top: 0; z-index: 999; }
    .mobile-header-content { display: flex; align-items: center; justify-content: space-between; }
    .sidebar-toggle { width: 40px; height: 40px; border: none; background: var(--neutral-100); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 999; }
    .sidebar-overlay.active { display: block; }
    .pagination-wrap { display: flex; justify-content: center; }
    .pagination-wrap nav[role="navigation"] { width: 100%; display: flex; justify-content: center; }
    .pagination-wrap nav[role="navigation"] svg { width: 1.25rem; height: 1.25rem; }
    .pagination-wrap nav[role="navigation"] [class*="inline-flex"] { display: inline-flex; }
    .pagination-wrap nav[role="navigation"] [class*="items-center"] { align-items: center; }
    .pagination-wrap nav[role="navigation"] [class*="justify-between"] { justify-content: space-between; }
    .pagination-wrap nav[role="navigation"] [class*="justify-center"] { justify-content: center; }
    .pagination-wrap nav[role="navigation"] a,
    .pagination-wrap nav[role="navigation"] span { font-size: 0.9rem; }
    .pagination-wrap nav[role="navigation"] a { color: var(--primary); text-decoration: none; }
    .pagination-wrap nav[role="navigation"] a:hover { text-decoration: underline; }

    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.active { transform: translateX(0); }
        .main-content { margin-left: 0; padding: 1rem; }
        .mobile-header { display: block; }
        .product-grid { grid-template-columns: 1fr; }
        .form-grid { grid-template-columns: 1fr; }
        .category-pills-bar { gap: 0.375rem; }
        .category-pill { font-size: 0.75rem; padding: 0.35rem 0.75rem; }
        .header-search-box { width: 100%; }
        .header-search-input { width: 100%; }
        .content-header { flex-direction: column; align-items: flex-start; }
        .header-actions { width: 100%; justify-content: flex-end; }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="inventory-layout">
    <!-- Sidebar (same as before) -->
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
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="nav-item-text">Dashboard</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="nav-item active">
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
                <a href="{{ route('admin.staff-audit') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-5 6H2v-2a4 4 0 014-4h1m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 4a3 3 0 10-6 0 3 3 0 006 0z" />
                    </svg>
                    <span class="nav-item-text">Staff Audit Logs</span>
                </a>
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
                <a href="{{ route('admin.login-logs') }}" class="nav-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m0 0l3-3m-3 3l3 3m6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-6" />
                    </svg>
                    <span class="nav-item-text">Login Logs</span>
                </a>
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
                <span style="font-weight: 700; color: var(--neutral-800);">BuildWise</span>
                <div style="width: 40px;"></div>
            </div>
        </div>

        <div class="content-header">
            <h1 class="page-title">
                @if(request('category') && request('category') != 'all')
                    {{ request('category') }}
                @else
                    All Materials
                @endif
            </h1>
            <div class="header-actions">
                <div class="header-search-box">
                    <div class="header-search-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" class="header-search-input" placeholder="Search materials.." value="{{ $search ?? '' }}" onkeydown="handleSearch(event)" id="headerSearchInput">
                    <button type="button" class="header-search-clear" id="headerSearchClear" onclick="clearSearch()" style="{{ ($search ?? '') ? 'display:flex' : 'display:none' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Material
                </button>
            </div>
        </div>

        <div id="lowStockBanner"></div>

        <!-- Category Pills -->
        <div class="category-pills-bar">
            <div class="category-pill {{ !request('category') || request('category') == 'all' ? 'active' : '' }}" onclick="filterByCategory('all')">
                All Materials
                <span class="pill-count">{{ $products->total() }}</span>
            </div>
            @foreach($categories as $cat)
                <div class="category-pill {{ request('category') == $cat ? 'active' : '' }}" onclick="filterByCategory('{{ $cat }}')">
                    {{ $cat }}
                    <span class="pill-count">{{ $categoryCounts[$cat] ?? 0 }}</span>
                </div>
            @endforeach
        </div>

        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card" data-stock-status="{{ $product->quantity <= 0 ? 'out' : ($product->quantity < 10 ? 'low' : 'in') }}">
                        <div class="product-image">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                            @else
                                <div class="product-placeholder">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            @endif

                            @if($product->quantity <= 0)
                                <span class="stock-badge out-stock">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    OUT OF STOCK
                                </span>
                            @elseif($product->quantity < 10)
                                <span class="stock-badge low-stock">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    LOW STOCK
                                </span>
                            @else
                                <span class="stock-badge in-stock">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    IN STOCK
                                </span>
                            @endif
                        </div>

                        <div class="product-info">
                            <div class="product-category">{{ $product->category ?? 'Uncategorized' }}</div>
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-sku">SKU: {{ $product->sku }}</div>
                            <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                            <div class="product-stock">Stock: {{ $product->quantity }} units</div>

                            <div class="product-actions">
                                <button class="action-btn edit" title="Edit Product" onclick='openEditModal(@json($product))'>
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button class="action-btn delete" title="Archive Product" onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="pagination-wrap" style="margin-top: 2rem;">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 0.5rem;">No Products Found</h3>
                <p style="color: var(--neutral-600); margin-bottom: 2rem;">
                    @if(request('search'))
                        No materials match your search criteria.
                    @elseif(request('category') && request('category') != 'all')
                        No materials in this category yet.
                    @else
                        Start by adding your first material.
                    @endif
                </p>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Your First Material
                </button>
            </div>
        @endif
    </main>
</div>

<!-- Add Product Modal -->
<div class="modal" id="addModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Material</h2>
            <button class="modal-close" onclick="closeAddModal()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="addForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group full-width">
                    <label>Material Image</label>
                    <div class="image-preview-container" onclick="document.getElementById('add_image').click()">
                        <img id="add_image_preview" class="image-preview" alt="Preview">
                        <div class="image-placeholder">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div class="image-placeholder-text">Click to upload image</div>
                            <div class="image-placeholder-subtext">PNG, JPG, GIF up to 2MB</div>
                        </div>
                        <button type="button" class="image-remove-btn" onclick="removeImage(event, 'add')">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <input type="file" id="add_image" name="image" class="file-input-hidden" accept="image/*" onchange="previewImage(event, 'add')">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="add_name">Material Name <span style="color: red">*</span></label>
                        <input type="text" id="add_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="add_sku">SKU <span style="color: red">*</span></label>
                        <input type="text" id="add_sku" name="sku" required>
                    </div>
                    <div class="form-group">
                        <label for="add_category">Category <span style="color: red">*</span></label>
                        <select id="add_category" name="category" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_price">Price (₱) <span style="color: red">*</span></label>
                        <input type="number" id="add_price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="add_quantity">Quantity <span style="color: red">*</span></label>
                        <input type="number" id="add_quantity" name="quantity" min="0" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="add_description">Description</label>
                        <textarea id="add_description" name="description"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="addSubmitBtn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Material
                </button>
            </div>
        </form>
    </div>
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
                    <div class="logout-note">You are signed in as an Administrator. You will need to log in again to continue.</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeLogoutModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('logoutForm').submit()">Logout</button>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Material</h2>
            <button class="modal-close" onclick="closeEditModal()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group full-width">
                    <label>Material Image</label>
                    <div class="image-preview-container" onclick="document.getElementById('edit_image').click()">
                        <img id="edit_image_preview" class="image-preview" alt="Preview">
                        <div class="image-placeholder">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div class="image-placeholder-text">Click to upload image</div>
                        </div>
                        <button type="button" class="image-remove-btn" onclick="removeImage(event, 'edit')">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <input type="file" id="edit_image" name="image" class="file-input-hidden" accept="image/*" onchange="previewImage(event, 'edit')">
                    <input type="hidden" id="edit_remove_image" name="remove_image" value="0">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_name">Material Name <span style="color: red">*</span></label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_sku">SKU <span style="color: red">*</span></label>
                        <input type="text" id="edit_sku" name="sku" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_category">Category <span style="color: red">*</span></label>
                        <select id="edit_category" name="category" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_price">Price (₱) <span style="color: red">*</span></label>
                        <input type="number" id="edit_price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_quantity">Quantity <span style="color: red">*</span></label>
                        <input type="number" id="edit_quantity" name="quantity" min="0" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/inventory-v2.js') }}"></script>
<script>
    async function parseApiResponse(response) {
        const text = await response.text();
        let data = null;
        try {
            data = text ? JSON.parse(text) : null;
        } catch (e) {
            data = null;
        }
        return { response, data };
    }

    function filterByCategory(category) {
        const url = new URL(window.location);
        if (category === 'all') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category);
        }
        window.location.href = url.toString();
    }

    function applySearch(searchValue) {
        const url = new URL(window.location);
        url.searchParams.delete('page');

        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }

        window.location.href = url.toString();
    }

    function handleSearch(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            applySearch(event.target.value.trim());
        }
    }

    function clearSearch() {
        applySearch('');
    }

    // Show/hide clear button and search as user types (debounced)
    let searchDebounceTimer;
    document.getElementById('headerSearchInput').addEventListener('input', function() {
        const clearBtn = document.getElementById('headerSearchClear');
        clearBtn.style.display = this.value ? 'flex' : 'none';

        clearTimeout(searchDebounceTimer);
        const value = this.value.trim();

        if (value === '') {
            applySearch('');
            return;
        }

        searchDebounceTimer = setTimeout(() => applySearch(value), 350);
    });

    function openAddModal() {
        document.getElementById('addModal').classList.add('active');
        document.getElementById('addForm').reset();
        removeImage(new Event('click'), 'add');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.remove('active');
    }

    // AJAX Add Product with image preview in card
    document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = document.getElementById('addSubmitBtn');
        const formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:18px;height:18px;animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Saving...';

        fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(parseApiResponse)
        .then(({ response, data }) => {
            if (data && data.success) {
                closeAddModal();
                form.reset();
                removeImage(new Event('click'), 'add');
                injectProductCard(data.product);
                showToast('Product added successfully!', 'success');
            } else if (response.redirected || response.ok) {
                // Fallback when backend returns redirect/HTML instead of JSON.
                location.reload();
            } else {
                const errs = data && data.errors ? Object.values(data.errors).flat().join('\n') : ((data && data.message) || 'Error adding product.');
                alert(errs);
            }
        })
        .catch(() => alert('An error occurred. Please try again.'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> Add Product';
        });
    });

    function injectProductCard(product) {
        const grid = document.querySelector('.product-grid');
        const emptyState = document.querySelector('.empty-state');

        // If empty state is showing, replace it with a grid
        if (emptyState) {
            const newGrid = document.createElement('div');
            newGrid.className = 'product-grid';
            emptyState.replaceWith(newGrid);
        }

        const targetGrid = document.querySelector('.product-grid');
        if (!targetGrid) return;

        const qty = parseInt(product.quantity);
        let stockClass = qty <= 0 ? 'out-stock' : (qty < 10 ? 'low-stock' : 'in-stock');
        let stockLabel = qty <= 0 ? 'OUT OF STOCK' : (qty < 10 ? 'LOW STOCK' : 'IN STOCK');
        let stockIcon = qty <= 0
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
            : (qty < 10
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />');

        const imageHtml = product.image_path
            ? `<img src="/storage/${product.image_path}" alt="${escHtml(product.name)}">`
            : `<div class="product-placeholder"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg></div>`;

        const price = parseFloat(product.price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const productJson = JSON.stringify(product).replace(/'/g, "\\'");

        const card = document.createElement('div');
        card.className = 'product-card';
        card.style.animation = 'fadeInUp 0.4s ease forwards';
        card.dataset.stockStatus = qty <= 0 ? 'out' : (qty < 10 ? 'low' : 'in');
        card.innerHTML = `
            <div class="product-image">
                ${imageHtml}
                <span class="stock-badge ${stockClass}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">${stockIcon}</svg>
                    ${stockLabel}
                </span>
            </div>
            <div class="product-info">
                <div class="product-category">${escHtml(product.category || 'Uncategorized')}</div>
                <div class="product-name">${escHtml(product.name)}</div>
                <div class="product-sku">SKU: ${escHtml(product.sku)}</div>
                <div class="product-price">₱${price}</div>
                <div class="product-stock">Stock: ${qty} units</div>
                <div class="product-actions">
                    <button class="action-btn edit" title="Edit Product" onclick='openEditModal(${JSON.stringify(product)})'>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                    <button class="action-btn delete" title="Archive Product" onclick="deleteProduct(${product.id}, '${escHtml(product.name).replace(/'/g, "\\'")}')">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    </button>
                </div>
            </div>`;

        targetGrid.prepend(card);
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.style.cssText = `position:fixed;bottom:2rem;right:2rem;background:${type==='success'?'#059669':'#dc2626'};color:white;padding:1rem 1.5rem;border-radius:0.75rem;font-weight:600;z-index:9999;box-shadow:0 10px 25px rgba(0,0,0,0.2);animation:fadeInUp 0.3s ease`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }

    function openEditModal(product) {
        document.getElementById('editModal').classList.add('active');
        document.getElementById('editForm').action = `/products/${product.id}`;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_sku').value = product.sku;
        document.getElementById('edit_category').value = product.category || '';
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_quantity').value = product.quantity;
        document.getElementById('edit_description').value = product.description || '';
        document.getElementById('edit_remove_image').value = '0';

        const preview = document.getElementById('edit_image_preview');
        const container = preview.parentElement;
        const placeholder = container.querySelector('.image-placeholder');
        const removeBtn = container.querySelector('.image-remove-btn');

        if (product.image_path) {
            preview.src = `/storage/${product.image_path}`;
            preview.classList.add('active');
            placeholder.style.display = 'none';
            removeBtn.classList.add('active');
            container.classList.add('has-image');
        } else {
            preview.src = '';
            preview.classList.remove('active');
            placeholder.style.display = 'block';
            removeBtn.classList.remove('active');
            container.classList.remove('has-image');
        }
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
    }

    function previewImage(event, type) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must be less than 2MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(`${type}_image_preview`);
                const container = preview.parentElement;
                const placeholder = container.querySelector('.image-placeholder');
                const removeBtn = container.querySelector('.image-remove-btn');

                preview.src = e.target.result;
                preview.classList.add('active');
                placeholder.style.display = 'none';
                removeBtn.classList.add('active');
                container.classList.add('has-image');
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage(event, type) {
        event.stopPropagation();

        const preview = document.getElementById(`${type}_image_preview`);
        const container = preview.parentElement;
        const placeholder = container.querySelector('.image-placeholder');
        const removeBtn = container.querySelector('.image-remove-btn');
        const fileInput = document.getElementById(`${type}_image`);

        preview.src = '';
        preview.classList.remove('active');
        placeholder.style.display = 'block';
        removeBtn.classList.remove('active');
        container.classList.remove('has-image');
        fileInput.value = '';

        if (type === 'edit') {
            document.getElementById('edit_remove_image').value = '1';
        }
    }

    function deleteProduct(id, name) {
        if (confirm(`Are you sure you want to archive "${name}"?`)) {
            fetch(`/products/${id}`, {
                method: 'DELETE',
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
                alert('An error occurred while archiving the product.');
            });
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
</script>
@endsection

