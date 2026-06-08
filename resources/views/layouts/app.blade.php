<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ url('') }}">
    <title>@yield('title', 'BuildWise') - Inventory Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Professional Hardware Store Color Palette */
            --primary: #1e3a5f;          /* Deep Navy Blue */
            --primary-dark: #152940;     /* Darker Navy */
            --primary-light: #2d5a8c;    /* Lighter Navy */
            --secondary: #d97706;        /* Warm Amber/Orange */
            --secondary-dark: #b45309;   /* Darker Amber */
            --accent: #059669;           /* Professional Green */
            --accent-light: #10b981;     /* Light Green */
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-300: #cbd5e1;
            --neutral-400: #94a3b8;
            --neutral-500: #64748b;
            --neutral-600: #475569;
            --neutral-700: #334155;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
            --error: #dc2626;
            --success: #059669;
            --warning: #d97706;

            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--neutral-50) 0%, var(--neutral-100) 100%);
            color: var(--neutral-800);
            line-height: 1.6;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--accent);
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--error);
        }

        .alert::before {
            content: '';
            width: 20px;
            height: 20px;
            background-size: contain;
        }

        .alert-success::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23059669'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/%3E%3C/svg%3E");
        }

        .alert-error::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23dc2626'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'/%3E%3C/svg%3E");
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
        }

        /* Footer */
        .app-footer {
            margin-top: 3rem;
            padding: 1.5rem 0;
            background: linear-gradient(135deg, rgba(30, 58, 95, 0.08), rgba(217, 119, 6, 0.08));
            border-top: 1px solid var(--neutral-200);
        }

        .app-footer__inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .app-footer__brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.2px;
        }

        .app-footer__meta {
            color: var(--neutral-600);
            font-size: 0.9rem;
        }

        .app-footer__links {
            display: flex;
            gap: 1rem;
            align-items: center;
            font-size: 0.9rem;
        }

        .app-footer__link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .app-footer__link:hover {
            color: var(--secondary);
        }

        @media (max-width: 768px) {
            .app-footer__inner {
                padding: 0 1rem;
                flex-direction: column;
                align-items: flex-start;
            }
        }

        body.has-sidebar .app-footer {
            margin-left: 280px;
            width: calc(100% - 280px);
        }

        .inventory-layout + .app-footer,
        .dashboard-layout + .app-footer {
            margin-left: 280px;
            width: calc(100% - 280px);
        }

        @media (max-width: 768px) {
            body.has-sidebar .app-footer {
                margin-left: 0;
                width: 100%;
            }

            .inventory-layout + .app-footer,
            .dashboard-layout + .app-footer {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>

    @yield('styles')
</head>
<body class="@yield('body-class')">
    @if(session('success'))
        <div style="position: fixed; top: 1.5rem; right: 1.5rem; z-index: 1000; max-width: 400px;">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div style="position: fixed; top: 1.5rem; right: 1.5rem; z-index: 1000; max-width: 400px;">
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @yield('content')

    @auth
        <footer class="app-footer">
            <div class="app-footer__inner">
                <div>
                    <div class="app-footer__brand">BuildWise</div>
                    <div class="app-footer__meta">
                        {{ auth()->user()->isAdmin() ? 'Admin Dashboard' : 'User Dashboard' }} • Inventory Management System
                    </div>
                </div>
                <div class="app-footer__links">
                    <span class="app-footer__meta">© {{ now()->format('Y') }} BuildWise</span>
                    <a class="app-footer__link" href="{{ route('dashboard.overview') }}">Overview</a>
                    <a class="app-footer__link" href="{{ route('dashboard.sales') }}">Sales</a>
                </div>
            </div>
        </footer>
    @endauth

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    <script src="{{ asset('js/inventory-functions.js') }}"></script>
    <script src="{{ asset('js/logs-realtime.js') }}"></script>

    @yield('scripts')

    <script>
        // Ensure footer aligns when a sidebar layout is present
        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelector('.sidebar')) {
                document.body.classList.add('has-sidebar');
            }
        });
    </script>

</body>
</html>
