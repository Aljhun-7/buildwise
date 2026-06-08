<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
        return redirect()->route('login');
    });

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// AJAX route for password strength (no auth required)
Route::post('/check-password-strength', [AuthController::class, 'checkPasswordStrength'])
    ->name('check.password.strength');

// Authenticated routes (all roles)
Route::middleware(['auth', \App\Http\Middleware\NoBackHistory::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin landing route (same UI/features as user dashboard)
Route::middleware(['auth', \App\Http\Middleware\NoBackHistory::class, 'App\Http\Middleware\CheckRole:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->name('admin.dashboard');
    Route::get('/admin/login-logs', [AccountSettingsController::class, 'adminLoginLogs'])
        ->name('admin.login-logs');
    Route::get('/admin/staff-audit', [DashboardController::class, 'staffAudit'])
        ->name('admin.staff-audit');
    Route::delete('/admin/staff/{user}', [DashboardController::class, 'destroyStaff'])
        ->name('admin.staff.destroy');
    Route::get('/admin/staff-archived', [DashboardController::class, 'archivedStaff'])
        ->name('admin.staff.archived');
    Route::patch('/admin/staff/{user}/restore', [DashboardController::class, 'restoreStaff'])
        ->name('admin.staff.restore');
    Route::delete('/admin/staff/{user}/force-delete', [DashboardController::class, 'forceDeleteStaff'])
        ->name('admin.staff.force-delete');
});

// Shared app routes (admin + user)
Route::middleware(['auth', \App\Http\Middleware\NoBackHistory::class])->group(function () {
    // Main inventory page (with sidebar)
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
        ->name('user.dashboard');

    // Dashboard overview (stats & activity)
    Route::get('/dashboard/overview', [DashboardController::class, 'overview'])
        ->name('dashboard.overview');

    // Sales reports
    Route::get('/dashboard/sales', [DashboardController::class, 'salesReport'])
        ->name('dashboard.sales');
    Route::get('/dashboard/sales/print', [DashboardController::class, 'salesReportPrint'])
        ->name('dashboard.sales.print');

    // Process sale transaction
    Route::post('/sales/process', [DashboardController::class, 'processSale'])
        ->name('sales.process');

    // Cart (staff checkout flow)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Product management (KEEP existing routes)
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/archived', [ProductController::class, 'archived'])->name('products.archived');
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/products/{product}/activity-log', [ProductController::class, 'activityLog'])->name('products.activity-log');

    // Account settings
    Route::get('/account/settings', [AccountSettingsController::class, 'index'])->name('account.settings');
    Route::put('/account/settings/profile', [AccountSettingsController::class, 'updateProfile'])->name('account.settings.profile.update');
    Route::put('/account/settings/password', [AccountSettingsController::class, 'updatePassword'])->name('account.settings.password.update');
    Route::post('/account/settings/profile-picture', [AccountSettingsController::class, 'updateProfilePicture'])->name('account.settings.profile-picture.update');
    Route::delete('/account/settings/profile-picture', [AccountSettingsController::class, 'removeProfilePicture'])->name('account.settings.profile-picture.remove');
});
