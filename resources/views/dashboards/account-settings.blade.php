@extends('layouts.app')

@section('title', 'Account Settings')
@section('body-class', 'has-sidebar')

@section('styles')
<style>
    .inventory-layout { display: flex; min-height: 100vh; background: var(--neutral-50); }
    .sidebar { width: 280px; background: white; border-right: 1px solid var(--neutral-200); position: fixed; left: 0; top: 0; height: 100vh; overflow-y: auto; z-index: 1000; transition: transform 0.3s ease; }
    .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--neutral-200); background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); }
    .sidebar-brand { display: flex; align-items: center; gap: 0.75rem; color: white; }
    .brand-avatar { width: 44px; height: 44px; border-radius: 999px; object-fit: cover; border: 2px solid rgba(255,255,255,0.55); }
    .brand-avatar-placeholder { width: 44px; height: 44px; border-radius: 999px; background: rgba(255, 255, 255, 0.25); color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; border: 2px solid rgba(255,255,255,0.4); }
    .brand-meta { display: flex; flex-direction: column; line-height: 1.15; }
    .brand-text { font-size: 1.2rem; font-weight: 800; }
    .brand-subtext { font-size: 0.78rem; opacity: 0.9; font-weight: 600; }
    .sidebar-nav { padding: 1rem 0; }
    .nav-section { margin-bottom: 0.5rem; }
    .nav-section-title { padding: 0.75rem 1.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--neutral-500); }
    .nav-item { display: flex; align-items: center; padding: 0.875rem 1.5rem; color: var(--neutral-700); text-decoration: none; transition: all 0.2s; position: relative; cursor: pointer; }
    .nav-item:hover { background: var(--neutral-50); color: var(--primary); }
    .nav-item.active { background: rgba(30, 58, 95, 0.1); color: var(--primary); font-weight: 600; }
    .nav-item.active::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--primary); }
    .nav-item svg { width: 20px; height: 20px; margin-right: 0.75rem; }
    .nav-item-text { flex: 1; }

    .main-content { margin-left: 280px; flex: 1; padding: 2rem; }
    .page-title { font-size: 2rem; color: var(--neutral-800); margin-bottom: 1.5rem; }
    .page-subtitle { color: var(--neutral-500); margin-bottom: 1.5rem; font-size: 0.95rem; }
    .settings-grid { display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    .card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: var(--shadow-sm); }
    .card h2 { font-size: 1.2rem; color: var(--neutral-800); margin-bottom: 1rem; }
    .profile-photo { width: 140px; height: 140px; border-radius: 999px; object-fit: cover; border: 4px solid var(--neutral-100); margin: 0 auto 1rem auto; display: block; }
    .photo-placeholder { width: 140px; height: 140px; border-radius: 999px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; margin: 0 auto 1rem auto; }
    .profile-meta { text-align: center; margin-bottom: 1.25rem; }
    .profile-name { font-size: 1.15rem; font-weight: 700; color: var(--neutral-800); }
    .profile-username { color: var(--neutral-500); font-size: 0.9rem; }
    .btn { width: 100%; padding: 0.7rem 1rem; border: none; border-radius: 0.65rem; font-weight: 600; cursor: pointer; margin-bottom: 0.6rem; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-danger { background: #fee2e2; color: #991b1b; }
    .btn:hover { opacity: 0.95; }
    .file-input { width: 100%; margin-bottom: 0.6rem; font-size: 0.9rem; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group { margin-bottom: 0.75rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { display: block; font-size: 0.9rem; color: var(--neutral-600); margin-bottom: 0.45rem; }
    .form-group input { width: 100%; border: 2px solid var(--neutral-200); border-radius: 0.65rem; padding: 0.7rem 0.8rem; font-size: 0.95rem; }
    .form-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.08); }
    .inline-note { color: var(--neutral-500); font-size: 0.85rem; }
    .cpw-strength { margin-top: 0.75rem; display: none; }
    .cpw-strength-bar { height: 6px; background: var(--neutral-200); border-radius: 3px; overflow: hidden; margin-bottom: 0.45rem; }
    .cpw-strength-fill { height: 100%; width: 0%; border-radius: 3px; transition: all 0.25s ease; }
    .cpw-strength-fill.weak { width: 33%; background: #dc2626; }
    .cpw-strength-fill.medium { width: 66%; background: #d97706; }
    .cpw-strength-fill.strong { width: 100%; background: #059669; }
    .cpw-strength-label { font-size: 0.82rem; font-weight: 700; color: var(--neutral-600); }
    .cpw-reqs { margin-top: 0.55rem; padding: 0.75rem; background: var(--neutral-50); border-radius: 0.55rem; font-size: 0.84rem; display: none; }
    .cpw-req { display: flex; align-items: center; gap: 0.45rem; margin-bottom: 0.28rem; color: var(--neutral-600); }
    .cpw-req:last-child { margin-bottom: 0; }
    .cpw-req svg { width: 15px; height: 15px; flex-shrink: 0; }
    .cpw-req.met { color: #059669; }
    .cpw-match-msg { margin-top: 0.45rem; font-size: 0.84rem; font-weight: 600; display: none; }
    .cpw-match-msg.ok { color: #059669; }
    .cpw-match-msg.err { color: #991b1b; }

    .log-card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: var(--shadow-sm); margin-bottom: 1.5rem; }
    .log-header { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; }
    .log-card h2 { font-size: 1.2rem; color: var(--neutral-800); margin-bottom: 0; }
    .log-actions { display: inline-flex; align-items: center; gap: 0.5rem; }
    .log-btn { border: 1px solid var(--neutral-300); background: white; color: var(--neutral-700); border-radius: 0.55rem; padding: 0.42rem 0.75rem; font-size: 0.84rem; font-weight: 700; cursor: pointer; }
    .log-btn.primary { background: var(--primary); border-color: var(--primary); color: white; }
    .log-btn:hover { background: var(--neutral-200); }
    .log-btn.primary:hover { background: var(--primary-dark); }
    .log-body { margin-top: 1rem; }
    .log-table-wrap { overflow-x: auto; }
    .log-table { width: 100%; border-collapse: collapse; min-width: 680px; }
    .log-table thead { background: var(--neutral-50); }
    .log-table th { text-align: left; font-size: 0.82rem; color: var(--neutral-600); padding: 0.75rem; border-bottom: 2px solid var(--neutral-200); text-transform: uppercase; letter-spacing: 0.04em; }
    .log-table td { padding: 0.75rem; border-bottom: 1px solid var(--neutral-100); font-size: 0.93rem; color: var(--neutral-700); vertical-align: top; }
    .logout-card { display: flex; gap: 1rem; align-items: center; background: var(--neutral-50); padding: 1rem; border-radius: 0.85rem; border: 1px solid var(--neutral-200); }
    .logout-icon { width: 48px; height: 48px; border-radius: 0.8rem; background: rgba(30, 58, 95, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; }
    .logout-title { font-size: 1.05rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 0.2rem; }
    .logout-note { color: var(--neutral-600); font-size: 0.9rem; }
    .empty-state { color: var(--neutral-500); padding: 1rem 0; }
    .error-box { background: #fee2e2; color: #991b1b; border-radius: 0.65rem; padding: 0.8rem 1rem; margin-bottom: 1rem; font-size: 0.9rem; }
    .modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); z-index: 2000; align-items: center; justify-content: center; padding: 1rem; }
    .modal.active { display: flex; }
    .modal-content { background: white; border-radius: 1rem; width: min(900px, 100%); max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); }
    .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: space-between; }
    .modal-title { font-size: 1.2rem; font-weight: 700; color: var(--neutral-800); }
    .modal-close { width: 36px; height: 36px; border: none; background: var(--neutral-100); border-radius: 0.5rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; color: var(--neutral-600); }
    .modal-close:hover { background: var(--neutral-200); color: var(--neutral-800); }
    .modal-body { padding: 1.25rem 1.5rem; }
    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; }

    .mobile-header { display: none; padding: 1rem; background: white; border-bottom: 1px solid var(--neutral-200); }
    .mobile-header-content { display: flex; align-items: center; justify-content: space-between; }
    .sidebar-toggle { width: 40px; height: 40px; border: none; background: var(--neutral-100); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 999; }
    .sidebar-overlay.active { display: block; }

    @media (max-width: 992px) {
        .settings-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.active { transform: translateX(0); }
        .main-content { margin-left: 0; padding: 1rem; }
        .mobile-header { display: block; }
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="inventory-layout" @unless($user->isAdmin()) data-live-logs-root data-refresh-seconds="30" @endunless>
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
                <a href="{{ route('user.dashboard') }}" class="nav-item">
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
                <a href="{{ route('account.settings') }}" class="nav-item active">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A11.956 11.956 0 0112 15.75c2.675 0 5.146.876 7.121 2.054M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="nav-item-text">Account Settings</span>
                </a>
                @if($user->isAdmin())
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

    <main class="main-content">
        <div class="mobile-header">
            <div class="mobile-header-content">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span style="font-weight: 700; color: var(--neutral-800);">Account Settings</span>
                <div style="width: 40px;"></div>
            </div>
        </div>

        <h1 class="page-title">Account Settings</h1>
        <p class="page-subtitle">
            Philippines Time:
            <span data-ph-now></span>
        </p>

        @if($errors->any())
            <div class="error-box">
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="settings-grid">
            <div class="card">
                @if($user->profile_picture_url)
                    <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" class="profile-photo">
                @else
                    <div class="photo-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif

                <div class="profile-meta">
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-username">{{ '@' . $user->username }}</div>
                </div>

                <form method="POST" action="{{ route('account.settings.profile-picture.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input class="file-input" type="file" name="profile_picture" accept="image/*" required>
                    <button type="submit" class="btn btn-primary">Upload / Change Photo</button>
                </form>

                @if($user->profile_picture)
                    <form method="POST" action="{{ route('account.settings.profile-picture.remove') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Remove Photo</button>
                    </form>
                @endif

                <div class="inline-note">Allowed file types: JPG, PNG, GIF, WEBP. Max size: 2MB.</div>
            </div>

            <div class="card">
                <h2>Profile Information</h2>
                <form method="POST" action="{{ route('account.settings.profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input id="username" type="text" value="{{ $user->username }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="mobile_number">Mobile Number</label>
                            <input id="mobile_number" name="mobile_number" type="text" value="{{ old('mobile_number', $user->mobile_number) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input id="birthdate" name="birthdate" type="date" value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group full">
                            <label for="email">Email (optional)</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="max-width: 220px; margin-top: 0.5rem;">Save Profile Changes</button>
                </form>
            </div>
        </div>

        <div class="card" style="margin-bottom: 1.5rem;">
            <h2>Change Password</h2>
            <form method="POST" action="{{ route('account.settings.password.update') }}" id="changePasswordForm">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group full">
                        <label for="current_password">Current Password</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                        @error('current_password', 'updatePassword')
                            <div class="inline-note" style="color:#991b1b; margin-top:0.4rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input id="new_password" name="new_password" type="password" autocomplete="new-password" required>
                        <div class="cpw-strength" id="cpwStrength">
                            <div class="cpw-strength-bar">
                                <div class="cpw-strength-fill" id="cpwStrengthFill"></div>
                            </div>
                            <div class="cpw-strength-label" id="cpwStrengthLabel">Weak Password</div>
                        </div>
                        <div class="cpw-reqs" id="cpwReqs">
                            <div class="cpw-req" id="cpwReqLength">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="cpw-req" id="cpwReqCase">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                <span>Mix of uppercase & lowercase</span>
                            </div>
                            <div class="cpw-req" id="cpwReqNumber">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                <span>At least one number</span>
                            </div>
                            <div class="cpw-req" id="cpwReqSpecial">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                <span>At least one special character</span>
                            </div>
                        </div>
                        @error('new_password', 'updatePassword')
                            <div class="inline-note" style="color:#991b1b; margin-top:0.4rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input id="new_password_confirmation" name="new_password_confirmation" type="password" autocomplete="new-password" required>
                        <div class="cpw-match-msg" id="cpwMatchMsg"></div>
                        @error('new_password_confirmation', 'updatePassword')
                            <div class="inline-note" style="color:#991b1b; margin-top:0.4rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="inline-note" style="margin-bottom: 0.7rem;">Use 8+ characters with uppercase, lowercase, number, and special character.</div>
                <button type="submit" class="btn btn-primary" id="updatePasswordBtn" style="max-width: 220px; margin-top: 0.2rem;" disabled>Update Password</button>
            </form>
        </div>

        @unless($user->isAdmin())
            <div class="log-card">
                <div class="log-header">
                    <h2>Your Login Logs</h2>
                    <div class="log-actions">
                        <button type="button" class="log-btn" onclick="openModal('loginLogsModal')">View All</button>
                        <button type="button" class="log-btn primary" onclick="printModal('loginLogsPrint', 'Login Logs')">Print</button>
                    </div>
                </div>
                <div class="log-table-wrap">
                    <table class="log-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>IP Address</th>
                                <th>Device / Browser</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loginLogsPreview as $log)
                                <tr>
                                    <td>
                                        <span data-log-timestamp="{{ optional($log->logged_in_at ?? $log->created_at)?->toIso8601String() }}">
                                            {{ ($log->logged_in_at ?? $log->created_at)->timezone(config('app.timezone'))->format('M d, Y h:i A') }}
                                        </span>
                                    </td>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($log->user_agent ?? 'N/A', 90) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-state">No login logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="log-card">
                <div class="log-header">
                    <h2>Your Recent Product Logs</h2>
                    <div class="log-actions">
                        <button type="button" class="log-btn" onclick="openModal('productLogsModal')">View All</button>
                        <button type="button" class="log-btn primary" onclick="printModal('productLogsPrint', 'Recent Activity')">Print</button>
                    </div>
                </div>
                <div class="log-table-wrap">
                    <table class="log-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Action</th>
                                <th>Product</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProductLogsPreview as $log)
                                <tr>
                                    <td>
                                        <span data-log-timestamp="{{ $log->created_at?->toIso8601String() }}">
                                            {{ $log->created_at->timezone(config('app.timezone'))->format('M d, Y h:i A') }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($log->action) }}</td>
                                    <td>{{ $log->product->name ?? 'N/A' }}</td>
                                    <td>{{ $log->description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state">No product logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endunless
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
                    <div class="logout-note">You are signed in as {{ auth()->user()->isAdmin() ? 'Administrator' : 'Staff' }}. You will need to log in again to continue.</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="log-btn" onclick="closeLogoutModal()">Cancel</button>
            <button type="button" class="log-btn primary" onclick="document.getElementById('logoutForm').submit()">Logout</button>
        </div>
    </div>
</div>

@unless($user->isAdmin())
    <div class="modal" id="loginLogsModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Login Logs</div>
                <button class="modal-close" onclick="closeModal('loginLogsModal')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="loginLogsPrint">
                <div class="log-table-wrap">
                    <table class="log-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>IP Address</th>
                                <th>Device / Browser</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loginLogs as $log)
                                <tr>
                                    <td>
                                        <span data-log-timestamp="{{ optional($log->logged_in_at ?? $log->created_at)?->toIso8601String() }}">
                                            {{ ($log->logged_in_at ?? $log->created_at)->timezone(config('app.timezone'))->format('M d, Y h:i A') }}
                                        </span>
                                    </td>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($log->user_agent ?? 'N/A', 120) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-state">No login logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="log-btn" onclick="closeModal('loginLogsModal')">Close</button>
                <button type="button" class="log-btn primary" onclick="printModal('loginLogsPrint', 'Login Logs')">Print</button>
            </div>
        </div>
    </div>

    <div class="modal" id="productLogsModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Recent Activity</div>
                <button class="modal-close" onclick="closeModal('productLogsModal')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="productLogsPrint">
                <div class="log-table-wrap">
                    <table class="log-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Action</th>
                                <th>Product</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProductLogs as $log)
                                <tr>
                                    <td>
                                        <span data-log-timestamp="{{ $log->created_at?->toIso8601String() }}">
                                            {{ $log->created_at->timezone(config('app.timezone'))->format('M d, Y h:i A') }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($log->action) }}</td>
                                    <td>{{ $log->product->name ?? 'N/A' }}</td>
                                    <td>{{ $log->description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state">No product logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="log-btn" onclick="closeModal('productLogsModal')">Close</button>
                <button type="button" class="log-btn primary" onclick="printModal('productLogsPrint', 'Recent Activity')">Print</button>
            </div>
        </div>
    </div>
@endunless
@endsection

@section('scripts')
<script src="{{ asset('js/inventory-v2.js') }}"></script>
<script src="{{ asset('js/account-settings.js') }}"></script>
@endsection


