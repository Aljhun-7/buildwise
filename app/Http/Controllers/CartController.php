<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductActivityLog;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private const SESSION_KEY = 'cart';

    public function index(Request $request)
    {
        $this->ensureStaffOnly();

        $cart = $this->getCart();
        $hydrated = $this->hydrateCart($cart);
        $this->saveCart($hydrated['cart']);

        return response()->json([
            'success' => true,
            'cart' => $hydrated['summary'],
        ]);
    }

    public function add(Request $request)
    {
        $this->ensureStaffOnly();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        if ($product->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This product is out of stock.',
            ], 400);
        }

        $cart = $this->getCart();
        $currentQty = isset($cart[$product->id]) ? (int) $cart[$product->id]['quantity'] : 0;
        $newQty = $currentQty + (int) $validated['quantity'];

        if ($newQty > (int) $product->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $product->quantity,
            ], 400);
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'quantity' => $newQty,
            'image_path' => $product->image_path,
        ];

        $this->saveCart($cart);

        $summary = $this->buildSummary($cart, $this->getProductsMap($cart));

        return response()->json([
            'success' => true,
            'message' => 'Added to cart.',
            'cart' => $summary,
        ]);
    }

    public function update(Request $request)
    {
        $this->ensureStaffOnly();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();
        if (!isset($cart[$validated['product_id']])) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.',
            ], 404);
        }

        $product = Product::findOrFail($validated['product_id']);
        if ((int) $validated['quantity'] > (int) $product->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $product->quantity,
            ], 400);
        }

        $cart[$product->id]['quantity'] = (int) $validated['quantity'];
        $cart[$product->id]['price'] = (float) $product->price;
        $cart[$product->id]['name'] = $product->name;
        $cart[$product->id]['image_path'] = $product->image_path;

        $this->saveCart($cart);

        $summary = $this->buildSummary($cart, $this->getProductsMap($cart));

        return response()->json([
            'success' => true,
            'message' => 'Cart updated.',
            'cart' => $summary,
        ]);
    }

    public function remove(Request $request)
    {
        $this->ensureStaffOnly();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = $this->getCart();
        unset($cart[$validated['product_id']]);
        $this->saveCart($cart);

        $summary = $this->buildSummary($cart, $this->getProductsMap($cart));

        return response()->json([
            'success' => true,
            'message' => 'Item removed.',
            'cart' => $summary,
        ]);
    }

    public function clear()
    {
        $this->ensureStaffOnly();

        $this->saveCart([]);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared.',
            'cart' => $this->emptySummary(),
        ]);
    }

    public function checkout(Request $request)
    {
        $this->ensureStaffOnly();

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $cart = $this->getCart();
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty.',
            ], 400);
        }

        $items = collect($cart)->sortBy('product_id')->values();
        $orderNumber = null;
        $sales = [];

        try {
            DB::transaction(function () use ($items, $validated, &$orderNumber, &$sales) {
                foreach ($items as $item) {
                    $product = Product::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();
                    $requestedQty = (int) $item['quantity'];

                    if ($product->quantity < $requestedQty) {
                        throw new \RuntimeException('Insufficient stock for ' . $product->name . '. Available: ' . $product->quantity);
                    }

                    $unitPrice = (float) $product->price;
                    $totalPrice = $unitPrice * $requestedQty;
                    $oldQuantity = (int) $product->quantity;

                    $sale = Sale::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'quantity' => $requestedQty,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'sale_date' => now(),
                        'notes' => $validated['notes'] ?? null,
                    ]);

                    if (!$orderNumber) {
                        $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) $sale->id, 6, '0', STR_PAD_LEFT);
                    }

                    $sale->order_number = $orderNumber;
                    $sale->save();

                    $product->quantity = $oldQuantity - $requestedQty;
                    $product->save();

                    ProductActivityLog::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'action' => 'sold',
                        'old_values' => ['quantity' => $oldQuantity],
                        'new_values' => ['quantity' => $product->quantity],
                        'description' => "Order {$orderNumber}: Sold {$requestedQty} units of '{$product->name}' for PHP " . number_format($totalPrice, 2),
                    ]);

                    $sales[] = $sale;
                }
            });
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

        $this->saveCart([]);

        return response()->json([
            'success' => true,
            'message' => 'Checkout completed successfully!',
            'order_number' => $orderNumber,
            'sales' => $sales,
        ]);
    }

    private function ensureStaffOnly(): void
    {
        if (auth()->user() && auth()->user()->isAdmin()) {
            abort(403, 'Admins are not allowed to process sales.');
        }
    }

    private function getCart(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    private function saveCart(array $cart): void
    {
        session()->put(self::SESSION_KEY, $cart);
    }

    private function hydrateCart(array $cart): array
    {
        if (empty($cart)) {
            return [
                'cart' => [],
                'summary' => $this->emptySummary(),
            ];
        }

        $products = $this->getProductsMap($cart);

        foreach ($cart as $productId => $item) {
            if (!isset($products[$productId])) {
                unset($cart[$productId]);
                continue;
            }

            $product = $products[$productId];
            $cart[$productId]['name'] = $product->name;
            $cart[$productId]['price'] = (float) $product->price;
            $cart[$productId]['image_path'] = $product->image_path;
        }

        return [
            'cart' => $cart,
            'summary' => $this->buildSummary($cart, $products),
        ];
    }

    private function getProductsMap(array $cart): array
    {
        $ids = array_keys($cart);
        if (empty($ids)) {
            return [];
        }

        return Product::whereIn('id', $ids)->get()->keyBy('id')->all();
    }

    private function buildSummary(array $cart, array $products): array
    {
        $items = [];
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $product = $products[$item['product_id']] ?? null;
            if (!$product) {
                continue;
            }

            $quantity = (int) $item['quantity'];
            $unitPrice = (float) $product->price;
            $lineTotal = $unitPrice * $quantity;

            $items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $unitPrice,
                'quantity' => $quantity,
                'stock' => (int) $product->quantity,
                'image_path' => $product->image_path,
                'line_total' => $lineTotal,
            ];

            $subtotal += $lineTotal;
            $totalItems += $quantity;
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'total_items' => $totalItems,
        ];
    }

    private function emptySummary(): array
    {
        return [
            'items' => [],
            'subtotal' => 0,
            'total_items' => 0,
        ];
    }
}
