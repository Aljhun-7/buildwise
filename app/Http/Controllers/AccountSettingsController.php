<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\ProductActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AccountSettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $loginLogs = collect();
        if (!$user->isAdmin() && Schema::hasTable('login_logs')) {
            $loginLogs = LoginLog::where('user_id', $user->id)
                ->latest('logged_in_at')
                ->limit(200)
                ->get();
        }

        $recentProductLogs = $user->isAdmin()
            ? collect()
            : ProductActivityLog::with('product')
                ->where('user_id', $user->id)
                ->latest()
                ->limit(200)
                ->get();

        $previewCount = 6;
        $loginLogsPreview = $loginLogs->take($previewCount);
        $recentProductLogsPreview = $recentProductLogs->take($previewCount);

        return view('dashboards.account-settings', compact(
            'user',
            'loginLogs',
            'recentProductLogs',
            'loginLogsPreview',
            'recentProductLogsPreview'
        ));
    }

    public function adminLoginLogs()
    {
        $user = auth()->user();

        abort_unless($user && $user->isAdmin(), 403);

        $loginLogs = collect();

        if (Schema::hasTable('login_logs')) {
            $loginLogs = LoginLog::where('user_id', $user->id)
                ->latest('logged_in_at')
                ->limit(200)
                ->get();
        }

        return view('dashboards.admin-login-logs', compact('user', 'loginLogs'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'regex:/^\+63[0-9]{10}$/'],
            'birthdate' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateProfilePicture(Request $request)
    {
        $user = auth()->user();

        if (!Schema::hasColumn('users', 'profile_picture')) {
            return back()->with('error', 'Database update required: run php artisan migrate to add profile_picture column.');
        }

        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $image = $request->file('profile_picture');
        $imageName = 'staff-' . $user->id . '-' . Str::slug($user->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('profile-pictures', $imageName, 'public');

        $user->update(['profile_picture' => $imagePath]);

        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function removeProfilePicture()
    {
        $user = auth()->user();

        if (!Schema::hasColumn('users', 'profile_picture')) {
            return back()->with('error', 'Database update required: run php artisan migrate to add profile_picture column.');
        }

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->update(['profile_picture' => null]);

        return back()->with('success', 'Profile picture removed successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
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
            return back()->withErrors($validator, 'updatePassword');
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ], 'updatePassword');
        }

        if (Hash::check($request->input('new_password'), $user->password)) {
            return back()->withErrors([
                'new_password' => 'New password must be different from your current password.',
            ], 'updatePassword');
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
