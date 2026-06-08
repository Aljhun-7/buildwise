@extends('layouts.app')

@section('title', 'Admin Dashboard')

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
        max-width: 1400px;
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

    .navbar-menu {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }

    .user-details {
        text-align: right;
    }

    .user-name {
        font-weight: 600;
        color: var(--neutral-800);
        font-size: 0.95rem;
    }

    .user-role {
        font-size: 0.85rem;
        color: var(--neutral-500);
    }

    .badge-admin {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .logout-btn {
        background: none;
        border: 2px solid var(--neutral-200);
        color: var(--neutral-700);
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
    }

    .logout-btn:hover {
        border-color: var(--error);
        color: var(--error);
        background: rgba(220, 38, 38, 0.05);
    }

    .dashboard-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }

    .welcome-section {
        background: white;
        border-radius: 1.5rem;
        padding: 3rem;
        box-shadow: var(--shadow-lg);
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(217, 119, 6, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .welcome-title {
        font-size: 2.5rem;
        color: var(--neutral-800);
        margin-bottom: 0.75rem;
        position: relative;
    }

    .welcome-subtitle {
        font-size: 1.1rem;
        color: var(--neutral-600);
        margin-bottom: 2rem;
        position: relative;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-light);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.primary {
        background: linear-gradient(135deg, rgba(30, 58, 95, 0.1) 0%, rgba(45, 90, 140, 0.15) 100%);
    }

    .stat-icon.secondary {
        background: linear-gradient(135deg, rgba(217, 119, 6, 0.1) 0%, rgba(180, 83, 9, 0.15) 100%);
    }

    .stat-icon.success {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.15) 100%);
    }

    .stat-icon svg {
        width: 28px;
        height: 28px;
    }

    .stat-icon.primary svg {
        stroke: var(--primary);
    }

    .stat-icon.secondary svg {
        stroke: var(--secondary);
    }

    .stat-icon.success svg {
        stroke: var(--accent);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--neutral-800);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--neutral-600);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .quick-actions {
        background: white;
        border-radius: 1.5rem;
        padding: 2.5rem;
        box-shadow: var(--shadow-md);
    }

    .section-title {
        font-size: 1.75rem;
        color: var(--neutral-800);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title svg {
        width: 32px;
        height: 32px;
        stroke: var(--primary);
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
    }

    .action-btn {
        background: linear-gradient(135deg, var(--neutral-50) 0%, white 100%);
        border: 2px solid var(--neutral-200);
        border-radius: 1rem;
        padding: 1.75rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: block;
    }

    .action-btn:hover {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(30, 58, 95, 0.05) 0%, white 100%);
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-icon svg {
        width: 28px;
        height: 28px;
        stroke: white;
    }

    .action-title {
        font-weight: 700;
        color: var(--neutral-800);
        margin-bottom: 0.5rem;
        font-size: 1.05rem;
    }

    .action-desc {
        font-size: 0.85rem;
        color: var(--neutral-600);
        line-height: 1.4;
    }

    @media (max-width: 768px) {
        .navbar-menu {
            gap: 1rem;
        }

        .user-details {
            display: none;
        }

        .dashboard-content {
            padding: 2rem 1rem;
        }

        .welcome-section {
            padding: 2rem 1.5rem;
        }

        .welcome-title {
            font-size: 1.75rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-wrapper">
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

            <div class="navbar-menu">
                <div class="user-info">
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">
                            <span class="badge-admin">Administrator</span>
                        </div>
                    </div>
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="dashboard-content">
        <div class="welcome-section">
            <h1 class="welcome-title">Hi, {{ Auth::user()->name }}! 👋</h1>
            <p class="welcome-subtitle">
                You're logged in as an Administrator. Here's your inventory management command center.
            </p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon primary">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Total Products</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon secondary">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">₱0.00</div>
                <div class="stat-label">Total Value</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon success">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Low Stock Alerts</div>
            </div>
        </div>

        <div class="quick-actions">
            <h2 class="section-title">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Quick Actions
            </h2>

            <div class="actions-grid">
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="action-title">Add Product</div>
                    <div class="action-desc">Register new inventory items</div>
                </a>

                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="action-title">Manage Users</div>
                    <div class="action-desc">Add or edit user accounts</div>
                </a>

                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="action-title">View Reports</div>
                    <div class="action-desc">Generate analytics & insights</div>
                </a>

                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="action-title">Settings</div>
                    <div class="action-desc">Configure system preferences</div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
