<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductActivityLog;
use App\Models\LoginLog;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Load the shared product category list from the markdown source file.
     */
    private function loadCategories(): array
    {
        $path = base_path('PRODUCT_CATEGORIES.md');

        if (!file_exists($path)) {
            return [
                'Building Materials',
                'Essentials',
                'Appliances',
                'Paints',
                'Hardware & Tools',
                'Ceiling Panels',
                'Wall Tiles',
                'Windows & Doors',
                'Bathroom',
                'Plumbing',
                'Kitchen',
                'Heating & Cooling',
                'Electrical',
                'Lighting',
                'Floorings',
            ];
        }

        $categories = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            if (preg_match('/^[-*]\s+(.+)$/', $trimmed, $matches)) {
                $categories[] = trim($matches[1]);
            }
        }

        return array_values(array_unique(array_filter($categories)));
    }

    /**
     * User dashboard - Main inventory view
     */
    public function userDashboard(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $products = Product::query()
            ->with(['creator', 'updater'])
            ->when($search, function ($query, $search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($category && $category !== 'all', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categoryCounts = Product::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $categories = $this->loadCategories();

        return view('dashboards.inventory', compact('products', 'search', 'category', 'categoryCounts', 'categories'));
    }

    /**
     * Admin dashboard - separate view with same inventory data/features
     */
    public function adminDashboard(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $products = Product::query()
            ->with(['creator', 'updater'])
            ->when($search, function ($query, $search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($category && $category !== 'all', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categoryCounts = Product::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $categories = $this->loadCategories();

        return view('dashboards.admin-dashboard', compact('products', 'search', 'category', 'categoryCounts', 'categories'));
    }

    /**
     * Admin staff audit logs overview
     */
    public function staffAudit(Request $request)
    {
        $staffQuery = User::where('role', 'user')
            ->orderBy('name')
            ->withCount('productActivityLogs');

        $staffUsers = $staffQuery->get();

        $selectedStaffId = $request->filled('user_id') ? (int) $request->input('user_id') : null;
        $selectedStaff = null;

        if ($selectedStaffId) {
            $selectedStaff = $staffUsers->firstWhere('id', $selectedStaffId);
        }

        if (!$selectedStaff) {
            $selectedStaff = $staffUsers->first();
        }

        $productLogsPreview = collect();
        $productLogsAll = collect();

        if ($selectedStaff) {
            $productLogsPreview = ProductActivityLog::with('product')
                ->where('user_id', $selectedStaff->id)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            $productLogsAll = ProductActivityLog::with('product')
                ->where('user_id', $selectedStaff->id)
                ->orderByDesc('created_at')
                ->limit(200)
                ->get();
        }

        return view('dashboards.staff-audit', compact(
            'staffUsers',
            'selectedStaff',
            'productLogsPreview',
            'productLogsAll'
        ));
    }

    /**
     * Archive (delete) a staff member.
     */
    public function destroyStaff(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()
                ->route('admin.staff-audit')
                ->with('error', 'Admin accounts cannot be deleted here.');
        }

        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.staff-audit')
                ->with('error', 'You cannot delete your own account here.');
        }

        $user->delete();

        return redirect()
            ->route('admin.staff-audit')
            ->with('success', 'Staff account archived successfully.');
    }

    /**
     * Archived staff list (soft-deleted).
     */
    public function archivedStaff()
    {
        $archivedStaff = User::onlyTrashed()
            ->where('role', 'user')
            ->orderByDesc('deleted_at')
            ->get();

        return view('dashboards.staff-archived', compact('archivedStaff'));
    }

    /**
     * Restore a soft-deleted staff member.
     */
    public function restoreStaff($user)
    {
        $archivedUser = User::withTrashed()->findOrFail($user);

        if ($archivedUser->isAdmin()) {
            return redirect()
                ->route('admin.staff.archived')
                ->with('error', 'Admin accounts cannot be restored here.');
        }

        $archivedUser->restore();

        return redirect()
            ->route('admin.staff.archived')
            ->with('success', 'Staff account restored successfully.');
    }

    /**
     * Permanently delete a soft-deleted staff member.
     */
    public function forceDeleteStaff($user)
    {
        $archivedUser = User::withTrashed()->findOrFail($user);

        if ($archivedUser->isAdmin()) {
            return redirect()
                ->route('admin.staff.archived')
                ->with('error', 'Admin accounts cannot be deleted here.');
        }

        $archivedUser->forceDelete();

        return redirect()
            ->route('admin.staff.archived')
            ->with('success', 'Staff account permanently deleted.');
    }

    /**
     * Dashboard overview with statistics
     */
    public function overview()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->isAdmin();
        $previewCount = 6;

        $totalProducts = Product::count();
        $lowStockProducts = Product::where('quantity', '<', 10)->where('quantity', '>', 0)->get();
        $lowStockCount = $lowStockProducts->count();
        $outOfStockCount = Product::where('quantity', '<=', 0)->count();
        $totalValue = Product::sum(DB::raw('price * quantity'));

        $recentProductLogs = ProductActivityLog::with(['product', 'user'])
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();
        $recentLoginLogs = collect();

        if (Schema::hasTable('login_logs')) {
            $recentLoginLogs = LoginLog::with('user')
                ->orderByDesc('logged_in_at')
                ->limit(200)
                ->get();
        }

        $recentActivities = $recentProductLogs
            ->map(function ($log) {
                return (object) [
                    'action' => $log->action,
                    'icon_action' => $log->action,
                    'title' => ucfirst($log->action) . ' Material',
                    'description' => $log->description,
                    'user_name' => $log->user->name ?? 'System',
                    'happened_at' => $log->created_at,
                ];
            })
            ->sortByDesc('happened_at')
            ->values();

        $recentLogsPreview = $recentActivities->take($previewCount);
        $recentLogsRemaining = $recentActivities->slice($previewCount)->values();
        $loginLogsPreview = $recentLoginLogs->take($previewCount);

        $activityCounts = ProductActivityLog::select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->pluck('count', 'action')
            ->toArray();

        $productsByCategory = Product::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        return view('dashboards.overview', compact(
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'totalValue',
            'recentLogsPreview',
            'recentLogsRemaining',
            'isAdmin',
            'previewCount',
            'activityCounts',
            'productsByCategory',
            'lowStockProducts',
            'loginLogsPreview',
            'recentLoginLogs'
        ));
    }

    /**
     * Sales report and analytics
     */
    public function salesReport(Request $request)
    {
        return view('dashboards.sales', $this->buildSalesReportData($request));
    }

    /**
     * Printable sales report
     */
    public function salesReportPrint(Request $request)
    {
        return view('dashboards.sales-print', $this->buildSalesReportData($request));
    }

    /**
     * Build sales report data with role-based access rules.
     */
    private function buildSalesReportData(Request $request): array
    {
        $currentUser = $request->user();
        $isAdmin = $currentUser && $currentUser->isAdmin();

        $period = (int) $request->input('period', 30);
        $period = max(1, min(365, $period));
        $requestedUserId = $request->filled('user_id') ? (int) $request->input('user_id') : null;
        $userId = $isAdmin ? $requestedUserId : $currentUser->id;
        $startDate = Carbon::now()->subDays($period);
        $salesBaseQuery = Sale::query()
            ->where('sale_date', '>=', $startDate)
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            });

        $totalSales = (clone $salesBaseQuery)->sum(DB::raw('quantity * unit_price'));
        $totalItemsSold = (clone $salesBaseQuery)->sum('quantity');
        $transactionCount = (clone $salesBaseQuery)->count();
        $averageTransaction = $transactionCount > 0 ? $totalSales / $transactionCount : 0;

        $salesByDay = Sale::select(
            DB::raw('DATE(sale_date) as date'),
            DB::raw('COALESCE(SUM(quantity * unit_price), 0) as total'),
            DB::raw('SUM(quantity) as items')
        )
            ->where('sale_date', '>=', $startDate)
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = Sale::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('COALESCE(SUM(quantity * unit_price), 0) as total_revenue')
        )
            ->where('sale_date', '>=', $startDate)
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->limit(10)
            ->get();

        $salesByCategory = Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->select(
                'products.category',
                DB::raw('COALESCE(SUM(sales.quantity * sales.unit_price), 0) as total'),
                DB::raw('SUM(sales.quantity) as items')
            )
            ->where('sales.sale_date', '>=', $startDate)
            ->when($userId, function ($query, $userId) {
                $query->where('sales.user_id', $userId);
            })
            ->groupBy('products.category')
            ->orderByDesc('total')
            ->get();

        $salesByStaff = Sale::join('users', 'sales.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                DB::raw('COUNT(sales.id) as orders_count'),
                DB::raw('SUM(sales.quantity) as total_items'),
                DB::raw('COALESCE(SUM(sales.quantity * sales.unit_price), 0) as total_revenue')
            )
            ->where('sales.sale_date', '>=', $startDate)
            ->groupBy('users.id', 'users.name')
            ->when($userId, function ($query, $userId) {
                $query->where('sales.user_id', $userId);
            })
            ->orderByDesc('total_revenue')
            ->get();

        $recentSales = Sale::with(['product', 'user'])
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->orderByDesc('sale_date')
            ->limit(20)
            ->get();

        $staffUsers = $isAdmin
            ? User::where('role', 'user')->orderBy('name')->get(['id', 'name'])
            : collect();

        $selectedStaffName = null;
        if ($isAdmin && $userId) {
            $selectedStaff = $staffUsers->firstWhere('id', $userId);
            $selectedStaffName = $selectedStaff?->name;
        } elseif (!$isAdmin) {
            $selectedStaffName = $currentUser->name;
        }

        $scopeLabel = ($isAdmin && !$userId)
            ? 'All Staff'
            : ('Staff: ' . ($selectedStaffName ?? 'Unknown'));

        return compact(
            'totalSales',
            'totalItemsSold',
            'transactionCount',
            'averageTransaction',
            'salesByDay',
            'topProducts',
            'salesByCategory',
            'salesByStaff',
            'recentSales',
            'period',
            'userId',
            'staffUsers',
            'isAdmin',
            'scopeLabel',
            'selectedStaffName'
        );
    }

    /**
     * Process a sale transaction
     */
    public function processSale(Request $request)
    {
        if (auth()->user() && auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Admins are not allowed to process sales. Only staff/users can sell products.',
            ], 403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            [$sale, $product] = DB::transaction(function () use ($validated) {
                $product = Product::where('id', $validated['product_id'])->lockForUpdate()->firstOrFail();

                if ($product->quantity < $validated['quantity']) {
                    throw new \RuntimeException('Insufficient stock. Available: ' . $product->quantity);
                }

                $unitPrice = (float) $product->price;
                $totalPrice = $unitPrice * (int) $validated['quantity'];
                $oldQuantity = (int) $product->quantity;

                $sale = Sale::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'quantity' => $validated['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'sale_date' => now(),
                    'notes' => $validated['notes'] ?? null,
                ]);

                $sale->order_number = 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) $sale->id, 6, '0', STR_PAD_LEFT);
                $sale->save();

                $product->quantity = $oldQuantity - (int) $validated['quantity'];
                $product->save();

                ProductActivityLog::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'action' => 'sold',
                    'old_values' => ['quantity' => $oldQuantity],
                    'new_values' => ['quantity' => $product->quantity],
                    'description' => "Order {$sale->order_number}: Sold {$validated['quantity']} units of '{$product->name}' for PHP " . number_format($totalPrice, 2),
                ]);

                return [$sale, $product];
            });
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sale completed successfully!',
            'sale' => $sale,
            'remaining_stock' => $product->quantity,
        ]);
    }

}
