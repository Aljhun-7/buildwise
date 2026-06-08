@extends('layouts.app')

@section('title', 'Register')

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
        background: linear-gradient(135deg, rgba(30, 58, 95, 0.85) 0%, rgba(45, 90, 140, 0.75) 50%, rgba(30, 58, 95, 0.85) 100%);
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
        max-width: 600px;
        padding: 1rem 1.7rem;
        position: relative;
        z-index: 1;
        max-height: 89vh;
        overflow-y: auto;
    }

    .logo-section {
        text-align: center;
        margin-bottom: 0.5rem;
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
        width: 41.7%;
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
        font-size: 1.5rem;
        color: var(--neutral-800);
    }

    .auth-subtitle {
        color: var(--neutral-500);
        font-size: 0.85rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 0.5rem;
    }

    label {
        display: block;
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
    input[type="date"],
    input[type="tel"],
    select {
        width: 100%;
        padding: 0.4rem 1rem;
        border: 2px solid var(--neutral-200);
        border-radius: 0.75rem;
        font-size: 0.95rem;
        font-family: 'DM Sans', sans-serif;
        transition: all 0.3s ease;
        background: var(--neutral-50);
    }

    input:focus, select:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.1);
    }

    input.error, select.error {
        border-color: var(--error);
    }

    input.error:focus, select.error:focus {
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }

    input.success {
        border-color: var(--success);
    }

    input.success:focus {
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
    }

    select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 20px;
        padding-right: 3rem;
    }

    /* Phone Input with Prefix */
    .phone-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .phone-prefix {
        position: absolute;
        left: 1rem;
        font-weight: 600;
        color: var(--neutral-600);
        font-size: 0.95rem;
        pointer-events: none;
        z-index: 1;
    }

    input[type="tel"] {
        padding-left: 4rem;
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

    .success-message {
        color: var(--success);
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

    .password-strength {
        margin-top: 0.75rem;
    }

    .strength-bar {
        height: 6px;
        background: var(--neutral-200);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 3px;
    }

    .strength-fill.weak {
        width: 33%;
        background: var(--error);
    }

    .strength-fill.medium {
        width: 66%;
        background: var(--warning);
    }

    .strength-fill.strong {
        width: 100%;
        background: var(--success);
    }

    .strength-text {
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .strength-label {
        color: var(--neutral-600);
    }

    .strength-label.weak {
        color: var(--error);
    }

    .strength-label.medium {
        color: var(--warning);
    }

    .strength-label.strong {
        color: var(--success);
    }

    .password-requirements {
        margin-top: 0.5rem;
        padding: 0.75rem;
        background: var(--neutral-50);
        border-radius: 0.5rem;
        font-size: 0.85rem;
    }

    .requirement {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--neutral-600);
        margin-bottom: 0.25rem;
    }

    .requirement:last-child {
        margin-bottom: 0;
    }

    .requirement svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .requirement.met {
        color: var(--success);
    }

    .btn {
        width: 100%;
        padding: 0.7rem;
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

    .divider {
        text-align: center;
        margin-top: 0.8rem;
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

    .login-link {
        text-align: center;
        color: var(--neutral-600);
        font-size: 0.95rem;
    }

    .login-link a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .login-link a:hover {
        color: var(--secondary);
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .auth-card {
            padding: 2rem 1.5rem;
        }

        .logo-text {
            font-size: 1.5rem;
        }

        .auth-title {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="logo-section">
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join BuildWise to manage your inventory efficiently</p>
        </div>

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        class="@error('username') error @enderror"
                        required
                    >
                    @error('username')
                        <div class="error-message">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="@error('name') error @enderror"
                        required
                    >
                    @error('name')
                        <div class="error-message">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="birthdate">Birthdate *</label>
                    <input
                        type="date"
                        id="birthdate"
                        name="birthdate"
                        value="{{ old('birthdate') }}"
                        class="@error('birthdate') error @enderror"
                        max="{{ date('Y-m-d') }}"
                        required
                    >
                    @error('birthdate')
                        <div class="error-message">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role">Account Role *</label>
                    <select
                        id="role"
                        name="role"
                        class="@error('role') error @enderror"
                        required
                    >
                        <option value="">Select Role</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <div class="error-message">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group" id="adminRegistrationKeyGroup" style="{{ old('role') === 'admin' ? '' : 'display:none;' }}">
                <label for="admin_registration_key">Admin Registration Key *</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="admin_registration_key"
                        name="admin_registration_key"
                        value="{{ old('admin_registration_key') }}"
                        class="@error('admin_registration_key') error @enderror"
                        autocomplete="off"
                        {{ old('role') === 'admin' ? 'required' : '' }}
                    >
                </div>
                <small style="color: var(--neutral-500); font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                    This key is required to create an Administrator account.
                </small>
                @error('admin_registration_key')
                    <div class="error-message">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mobile_number">Mobile Number (Philippines) *</label>
                <div class="phone-input-wrapper">
                    <span class="phone-prefix">+63</span>
                    <input
                        type="tel"
                        id="mobile_number"
                        name="mobile_number"
                        value="{{ old('mobile_number') }}"
                        class="@error('mobile_number') error @enderror"
                        placeholder="9171234567"
                        pattern="[0-9]{10}"
                        maxlength="10"
                        required
                    >
                </div>
                <small style="color: var(--neutral-500); font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                    Enter 10 digits (e.g., 9171234567 for +63 917 123 4567)
                </small>
                @error('mobile_number')
                    <div class="error-message">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="@error('password') error @enderror"
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <svg id="eye-icon-password" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                <div class="password-strength" id="passwordStrength" style="display: none;">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <div class="strength-text">
                        <span class="strength-label" id="strengthLabel">Weak</span>
                    </div>
                </div>

                <div class="password-requirements" id="passwordReqs" style="display: none;">
                    <div class="requirement" id="req-length">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>At least 8-12 characters</span>
                    </div>
                    <div class="requirement" id="req-case">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Mix of uppercase & lowercase</span>
                    </div>
                    <div class="requirement" id="req-number">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>At least one number</span>
                    </div>
                    <div class="requirement" id="req-special">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>At least one special character</span>
                    </div>
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

            <div class="form-group">
                <label for="password_confirmation">Confirm Password *</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <svg id="eye-icon-password_confirmation" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div id="passwordMatchMessage" style="display: none;"></div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Create Account</button>
        </form>

        <div class="divider login-link">
            <span>Already have an account? <a href="{{ route('login') }}" class="login-link"> Sign In</a></span>
        </div>

    </div>
</div>

<script>
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

    // Mobile number formatting - only allow numbers
    const mobileInput = document.getElementById('mobile_number');
    mobileInput.addEventListener('input', function(e) {
        // Remove any non-digit characters
        this.value = this.value.replace(/\D/g, '');

        // Limit to 10 digits
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });

    // Admin key visibility based on selected role
    const roleSelect = document.getElementById('role');
    const adminRegistrationKeyGroup = document.getElementById('adminRegistrationKeyGroup');
    const adminRegistrationKeyInput = document.getElementById('admin_registration_key');

    function toggleAdminRegistrationKey() {
        const isAdmin = roleSelect.value === 'admin';
        adminRegistrationKeyGroup.style.display = isAdmin ? 'block' : 'none';
        adminRegistrationKeyInput.required = isAdmin;

        if (!isAdmin) {
            adminRegistrationKeyInput.value = '';
            adminRegistrationKeyInput.classList.remove('error', 'success');
        }
    }

    roleSelect.addEventListener('change', toggleAdminRegistrationKey);
    toggleAdminRegistrationKey();

    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthLabel = document.getElementById('strengthLabel');
    const passwordReqs = document.getElementById('passwordReqs');

    passwordInput.addEventListener('input', function() {
        const password = this.value;

        if (password.length > 0) {
            strengthIndicator.style.display = 'block';
            passwordReqs.style.display = 'block';
            checkPasswordStrength(password);
        } else {
            strengthIndicator.style.display = 'none';
            passwordReqs.style.display = 'none';
        }

        // Also check password match when password changes
        checkPasswordMatch();
    });

    function checkPasswordStrength(password) {
        let strength = 0;

        // Check length
        const reqLength = document.getElementById('req-length');
        if (password.length >= 8) {
            strength += 25;
            markRequirementMet(reqLength);
        } else {
            markRequirementUnmet(reqLength);
        }

        // Check mixed case
        const reqCase = document.getElementById('req-case');
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            strength += 25;
            markRequirementMet(reqCase);
        } else {
            markRequirementUnmet(reqCase);
        }

        // Check numbers
        const reqNumber = document.getElementById('req-number');
        if (/[0-9]/.test(password)) {
            strength += 25;
            markRequirementMet(reqNumber);
        } else {
            markRequirementUnmet(reqNumber);
        }

        // Check special characters
        const reqSpecial = document.getElementById('req-special');
        if (/[^a-zA-Z0-9]/.test(password)) {
            strength += 25;
            markRequirementMet(reqSpecial);
        } else {
            markRequirementUnmet(reqSpecial);
        }

        // Update strength indicator
        strengthFill.className = 'strength-fill';
        strengthLabel.className = 'strength-label';

        if (strength >= 100) {
            strengthFill.classList.add('strong');
            strengthLabel.classList.add('strong');
            strengthLabel.textContent = 'Strong Password';
        } else if (strength >= 50) {
            strengthFill.classList.add('medium');
            strengthLabel.classList.add('medium');
            strengthLabel.textContent = 'Medium Strength';
        } else {
            strengthFill.classList.add('weak');
            strengthLabel.classList.add('weak');
            strengthLabel.textContent = 'Weak Password';
        }
    }

    function markRequirementMet(element) {
        element.classList.add('met');
        element.querySelector('svg').innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        `;
    }

    function markRequirementUnmet(element) {
        element.classList.remove('met');
        element.querySelector('svg').innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        `;
    }

    // Password confirmation checker
    const passwordConfirmation = document.getElementById('password_confirmation');
    const passwordMatchMessage = document.getElementById('passwordMatchMessage');
    const submitBtn = document.getElementById('submitBtn');

    passwordConfirmation.addEventListener('input', checkPasswordMatch);
    passwordConfirmation.addEventListener('blur', checkPasswordMatch);

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmation = passwordConfirmation.value;

        // Only show message if confirmation field has value
        if (confirmation.length === 0) {
            passwordMatchMessage.style.display = 'none';
            passwordConfirmation.classList.remove('error', 'success');
            submitBtn.disabled = true;
            return;
        }

        passwordMatchMessage.style.display = 'block';

        if (password === confirmation) {
            // Passwords match
            passwordConfirmation.classList.remove('error');
            passwordConfirmation.classList.add('success');
            passwordMatchMessage.className = 'success-message';
            passwordMatchMessage.innerHTML = `
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Password match!
            `;

            // Enable submit button if password is strong enough
            if (password.length >= 8) {
                submitBtn.disabled = false;
            }
        } else {
            // Passwords don't match
            passwordConfirmation.classList.remove('success');
            passwordConfirmation.classList.add('error');
            passwordMatchMessage.className = 'error-message';
            passwordMatchMessage.innerHTML = `
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Password do not match
            `;
            submitBtn.disabled = true;
        }
    }

    // Form submission - prepend +63 to mobile number
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const mobileInput = document.getElementById('mobile_number');
        const mobileValue = mobileInput.value;

        // Only prepend +63 if it's a 10-digit number
        if (mobileValue.length === 10 && !mobileValue.startsWith('+63')) {
            mobileInput.value = '+63' + mobileValue;
        }
    });
</script>
@endsection
