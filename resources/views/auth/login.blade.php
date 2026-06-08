@extends('layouts.app')

@section('title', 'Login')

@section('styles')
<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
        overflow: hidden;
        background-image: url('{{ asset("images/IAS_buildwise.png") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    /* Dark overlay for better text readability */
    .auth-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(81, 102, 129, 0.85) 0%, rgba(76, 110, 150, 0.75) 25%, rgba(50, 77, 112, 0.85) 75%);
        z-index: 0;
    }

    /* Optional: Animated gradient overlay */
    .auth-wrapper::after {
        content: '';
        position: absolute;
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(217, 119, 6, 0.15) 0%, transparent 70%);
        top: -400px;
        right: -400px;
        animation: float 20s ease-in-out infinite;
        z-index: 0;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(30px, 30px); }
    }

    .auth-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        width: 100%;
        max-width: 480px;
        padding: 1rem 2.5rem;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
    }

    .logo-section {
        text-align: center;
        margin-bottom: 2rem;
    }

    .logo {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: min(100%, 520px);
        min-height: 100px;
        margin-bottom: 0.75rem;
    }

    .logo-icon {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 96px;
        height: 96px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        pointer-events: none;
    }

    .logo-icon svg {
        width: 28px;
        height: 28px;
        stroke: white;
    }
    .logo-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .logo-text {
        position: relative;
        display: block;
        width: 52.4%;
        text-align: center;
        z-index: 3;
        font-size: 1.8rem;
        font-weight: 1000;
        text-transform: uppercase;
        letter-spacing: 0.30em;
        color: #f5a00c;
        -webkit-text-stroke: 1.6px #061933;
        text-shadow: 0 2px 40px rgba(30, 58, 95, 0.7);
    }

    .auth-title {
        font-size: 1.20rem;
        color: var(--neutral-800);
        margin-bottom: 0.3rem;
    }

    .auth-subtitle {
        color: var(--neutral-500);
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 1.3rem;
    }

    label {
        display: block;
        margin-bottom: 0.2rem;
        font-weight: 600;
        color: var(--neutral-700);
        font-size: 0.9rem;
        letter-spacing: 0.01em;
    }

    .input-wrapper {
        position: relative;
    }

    input[type="text"],
    input[type="password"],
    input[type="date"] {
        width: 100%;
        padding: 0.6rem 1rem;
        border: 2px solid var(--neutral-200);
        border-radius: 0.75rem;
        font-size: 0.95rem;
        font-family: 'DM Sans', sans-serif;
        transition: all 0.3s ease;
        background: var(--neutral-50);
    }

    input:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.1);
    }

    input.error {
        border-color: var(--error);
    }

    input.error:focus {
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }

    .error-message {
        color: var(--error);
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-weight: 500;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        color: var(--neutral-400);
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: var(--neutral-600);
    }

    .password-toggle svg {
        width: 20px;
        height: 20px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.5rem 0;
    }

    .remember-me input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    .remember-me label {
        margin: 0;
        font-weight: 500;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn {
        width: 100%;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border: none;
        border-radius: 0.75rem;
        font-size: 1rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 0.02em;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 58, 95, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }
    .btn-primary:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    .lock-countdown {
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: var(--warning);
        font-weight: 600;
        display: none;
    }
    .btn-secondary {
        background: white;
        color: var(--primary);
        border: 2px solid var(--primary);
    }
    .btn-secondary:hover {
        background: rgba(30, 58, 95, 0.08);
    }
    .reset-toggle {
        margin-top: 0.5rem;
        width: 100%;
        background: transparent;
        border: none;
        color: var(--primary);
        font-weight: 700;
        cursor: pointer;
        text-align: center;
        padding: 0.5rem 0;
        transition: color 0.2s;
    }
    .reset-toggle:hover {
        color: var(--secondary);
        text-decoration: underline;
    }
    .reset-panel {
        margin-top: 1rem;
        border: 1px solid rgba(30, 58, 95, 0.12);
        border-radius: 1rem;
        padding: 1.25rem;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.95) 0%, rgba(255, 255, 255, 0.98) 100%);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.1);
        display: none;
    }
    .reset-panel.active {
        display: block;
        animation: resetReveal 0.3s ease;
    }
    .reset-header {
        margin-bottom: 1rem;
    }
    .reset-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--neutral-800);
        margin-bottom: 0.35rem;
    }
    .reset-subtitle {
        font-size: 0.9rem;
        color: var(--neutral-500);
    }
    .reset-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }
    .reset-field {
        margin-bottom: 0;
    }
    .reset-field.full {
        grid-column: 1 / -1;
    }
    .reset-note {
        margin-top: 1rem;
        padding: 0.75rem;
        background: rgba(30, 58, 95, 0.06);
        border-radius: 0.75rem;
        font-size: 0.85rem;
        color: var(--neutral-600);
    }
    .reset-actions {
        margin-top: 1.25rem;
    }
    @keyframes resetReveal {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .success-message {
        color: #065f46;
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 0.65rem;
        padding: 0.75rem 0.85rem;
        margin-bottom: 0.9rem;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .divider {
        text-align: center;
        margin: 2rem 0;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: var(--neutral-200);
    }

    .divider span {
        background: white;
        padding: 0 1rem;
        color: var(--neutral-500);
        font-size: 0.85rem;
        position: relative;
        z-index: 1;
    }

    .register-link {
        text-align: center;
        color: var(--neutral-600);
        font-size: 0.95rem;
    }

    .register-link a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .register-link a:hover {
        color: var(--secondary);
        text-decoration: underline;
    }

    @media (max-width: 640px) {
        .auth-card {
            padding: 2rem 1.5rem;
        }

        .logo-text {
            font-size: 1.5rem;
        }

        .auth-title {
            font-size: 1.5rem;
        }
        .reset-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="logo-section">
            <div class="logo">
                <div class="logo-icon">
                    <img src="{{ asset('images/buildwise.png') }}" alt="BuildWise logo">
                </div>
                <span class="logo-text">BuildWise</span>
            </div>
            <h1 class="auth-title">R. Borje Inventory and Management System</h1>
        </div>

        @if(session('reset_success'))
            <div class="success-message">{{ session('reset_success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        class="@error('username') error @enderror"
                        autocomplete="username"
                        required
                    >
                </div>
                @error('username')
                    <div class="error-message" id="usernameErrorMessage">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="@error('password') error @enderror"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <svg id="eye-icon-password" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="error-message">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary" id="loginSubmitBtn">Sign In</button>
            <div id="lockCountdown" class="lock-countdown"></div>
        </form>

        <button type="button" class="reset-toggle" id="toggleResetBtn">Forgot your password? Reset it here.</button>

        <div class="reset-panel" id="resetPanel">
            <form method="POST" action="{{ route('password.reset') }}">
                @csrf
                <div class="reset-header">
                    <div class="reset-title">Reset Password</div>
                    <div class="reset-subtitle">Confirm your identity then set a new password.</div>
                </div>

                <div class="reset-grid">
                    <div class="form-group reset-field full">
                        <label for="reset_username">Username</label>
                        <input
                            type="text"
                            id="reset_username"
                            name="username"
                            value="{{ old('username') }}"
                            class="@error('username', 'resetPassword') error @enderror"
                            required
                        >
                        @error('username', 'resetPassword')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group reset-field">
                        <label for="reset_new_password">New Password</label>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                id="reset_new_password"
                                name="new_password"
                                class="@error('new_password', 'resetPassword') error @enderror"
                                required
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('reset_new_password')">
                                <svg id="eye-icon-reset_new_password" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('new_password', 'resetPassword')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group reset-field">
                        <label for="reset_new_password_confirmation">Confirm New Password</label>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                id="reset_new_password_confirmation"
                                name="new_password_confirmation"
                                required
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('reset_new_password_confirmation')">
                                <svg id="eye-icon-reset_new_password_confirmation" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="reset-note">Tip: Use a strong password with at least 8 characters and a mix of letters and numbers.</div>

                <div class="reset-actions">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>

        <div class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Create Account</a>
        </div>
    </div>
</div>

<script>
    function startLockCountdown(seconds) {
        const submitBtn = document.getElementById('loginSubmitBtn');
        const countdownEl = document.getElementById('lockCountdown');
        if (!submitBtn || !countdownEl || seconds <= 0) return;

        let remaining = seconds;
        submitBtn.disabled = true;
        countdownEl.style.display = 'block';

        const updateText = () => {
            countdownEl.textContent = `${remaining}s`;
        };
        updateText();

        const interval = setInterval(() => {
            remaining -= 1;
            if (remaining <= 0) {
                clearInterval(interval);
                submitBtn.disabled = false;
                countdownEl.textContent = '0s';
                setTimeout(() => {
                    countdownEl.style.display = 'none';
                    countdownEl.textContent = '';
                }, 2000);
                return;
            }
            updateText();
        }, 1000);
    }

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById('eye-icon-' + fieldId);

        if (field.type === 'password') {
            field.type = 'text';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            field.type = 'password';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }

    (function initLockCountdownFromError() {
        const usernameError = document.getElementById('usernameErrorMessage');
        if (!usernameError) return;

        const messageText = usernameError.textContent || '';
        const isLockMessage = messageText.includes('locked') || messageText.includes('Too many login attempts');
        if (!isLockMessage) return;

        const match = messageText.match(/(\d+)\s*(?:seconds?|secs?|s)?/i);
        if (!match) return;

        const seconds = parseInt(match[1], 10);
        if (!Number.isNaN(seconds) && seconds > 0) {
            startLockCountdown(seconds);
        }
    })();

    (function initResetPanel() {
        const toggleBtn = document.getElementById('toggleResetBtn');
        const panel = document.getElementById('resetPanel');
        if (!toggleBtn || !panel) return;

        const hasResetErrors = {{ $errors->resetPassword->any() ? 'true' : 'false' }};
        if (hasResetErrors) {
            panel.classList.add('active');
            toggleBtn.textContent = 'Hide reset form';
        }

        toggleBtn.addEventListener('click', function() {
            const active = panel.classList.toggle('active');
            toggleBtn.textContent = active ? 'Hide reset form' : 'Forgot your password? Reset it here.';
        });
    })();
</script>
@endsection
