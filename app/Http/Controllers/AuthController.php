<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(RegisterRequest $request)
    {
        // Create the user
        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'role' => $request->role,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
        ]);

        // Log the user in
        Auth::login($user);
        if (Schema::hasTable('login_logs')) {
            LoginLog::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logged_in_at' => now(),
            ]);
        }

        // Redirect based on role
        return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'user.dashboard')
            ->with('success', 'Registration successful! Welcome to BuildWise.');
    }

    /**
     * Handle user login with security features
     */
    public function login(LoginRequest $request)
    {
        // Rate limiting - prevent brute force attacks
        $throttleKey = strtolower($request->username) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 30)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => "Too many login attempts. Try again in {$seconds} secs.",
            ]);
        }

        // Find user by username
        $user = User::where('username', $request->username)->first();

        // Check if user exists
        if (!$user) {
            RateLimiter::hit($throttleKey, 30); // Lock for 30 seconds
            throw ValidationException::withMessages([
                'username' => 'These credentials do not match our records.',
            ]);
        }

        // Check if account is locked
        if ($user->isLocked()) {
            $seconds = now()->diffInSeconds($user->locked_until, false);
            $seconds = max(1, $seconds);
            throw ValidationException::withMessages([
                'username' => "Account locked. Try again in {$seconds} secs.",
            ]);
        }

        // Attempt to authenticate
        if (!Hash::check($request->password, $user->password)) {
            $user->incrementLoginAttempts();
            RateLimiter::hit($throttleKey, 30);

            throw ValidationException::withMessages([
                'username' => 'These credentials do not match our records.',
            ]);
        }

        // Reset failed attempts on successful login
        $user->resetLoginAttempts();
        RateLimiter::clear($throttleKey);

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        // Log the user in
        Auth::login($user, $request->boolean('remember'));
        if (Schema::hasTable('login_logs')) {
            LoginLog::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logged_in_at' => now(),
            ]);
        }

        // Redirect based on role
        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : route('user.dashboard')
        );
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Check password strength (AJAX endpoint)
     */
    public function checkPasswordStrength(Request $request)
    {
        $password = $request->input('password', '');
        $strength = 0;
        $feedback = [];

        if (strlen($password) >= 8) {
            $strength += 20;
        } else {
            $feedback[] = 'At least 8-12 characters';
        }

        if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) {
            $strength += 20;
        } else {
            $feedback[] = 'Mix of uppercase & lowercase';
        }

        if (preg_match('/[0-9]/', $password)) {
            $strength += 20;
        } else {
            $feedback[] = 'At least one number';
        }

        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $strength += 20;
        } else {
            $feedback[] = 'At least one special character';
        }

        if (strlen($password) >= 12) {
            $strength += 20;
        }

        $level = 'weak';
        if ($strength >= 80) {
            $level = 'strong';
        } elseif ($strength >= 60) {
            $level = 'medium';
        }

        return response()->json([
            'strength' => $strength,
            'level' => $level,
            'feedback' => $feedback
        ]);
    }

    /**
     * Reset password from login page using account verification fields.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ], [
            'new_password.confirmed' => 'New password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'resetPassword')->withInput();
        }

        $user = User::where('username', $request->input('username'))->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Account details did not match our records.',
            ], 'resetPassword')->withInput();
        }

        if (Hash::check($request->input('new_password'), $user->password)) {
            return back()->withErrors([
                'new_password' => 'New password must be different from your current password.',
            ], 'resetPassword')->withInput();
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        if (method_exists($user, 'resetLoginAttempts')) {
            $user->resetLoginAttempts();
        }

        return redirect()->route('login')->with('reset_success', 'Password has been reset successfully. You can now sign in.');
    }
}
