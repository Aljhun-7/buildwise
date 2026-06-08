<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|unique:products,sku',
                'category' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::slug($validated['name']) . '-' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
            }

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'category' => $validated['category'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'description' => $validated['description'] ?? '',
                'image_path' => $imagePath,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Log activity
            ProductActivityLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'action' => 'created',
                'new_values' => $product->toArray(),
                'description' => "Product '{$product->name}' was added to inventory with {$product->quantity} units",
            ]);

            return redirect()->route('user.dashboard')
                ->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            \Log::error('Product Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while adding the product: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|unique:products,sku,' . $product->id,
                'category' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'remove_image' => 'nullable',
            ]);

            $oldValues = $product->toArray();
            $imagePath = $product->image_path;

            // Handle image removal
            if ($request->input('remove_image') == '1') {
                if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $imagePath = null;
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                    Storage::disk('public')->delete($product->image_path);
                }

                $image = $request->file('image');
                $imageName = Str::slug($validated['name']) . '-' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
            }

            // Update product
            $product->update([
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'category' => $validated['category'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'description' => $validated['description'] ?? '',
                'image_path' => $imagePath,
                'updated_by' => Auth::id(),
            ]);

            // Build change description
            $changes = [];
            if ($oldValues['name'] != $product->name) {
                $changes[] = "name";
            }
            if ($oldValues['price'] != $product->price) {
                $changes[] = "price (₱" . number_format($oldValues['price'], 2) . " → ₱" . number_format($product->price, 2) . ")";
            }
            if ($oldValues['quantity'] != $product->quantity) {
                $changes[] = "quantity ({$oldValues['quantity']} → {$product->quantity})";
            }
            if ($oldValues['category'] != $product->category) {
                $changes[] = "category";
            }
            if ($oldValues['image_path'] != $product->image_path) {
                $changes[] = "image";
            }

            $changeText = !empty($changes) ? implode(', ', $changes) : 'details';

            // Log activity
            ProductActivityLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'action' => 'updated',
                'old_values' => $oldValues,
                'new_values' => $product->toArray(),
                'description' => "Product '{$product->name}' was updated: {$changeText}",
            ]);

            return redirect()->route('user.dashboard')
                ->with('success', 'Product updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('error', 'Please check the form for errors.');

        } catch (\Exception $e) {
            \Log::error('Product Update Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the product. Please try again.');
        }
    }

    /**
     * Remove the specified product (soft delete)
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $productName = $product->name;

            // Log activity before deletion
            ProductActivityLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'action' => 'archived',
                'old_values' => $product->toArray(),
                'description' => "Product '{$productName}' was archived and removed from active inventory",
            ]);

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product archived successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Product Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while archiving the product'
            ], 500);
        }
    }

    /**
     * Display archived products
     */
    public function archived()
    {
        $products = Product::onlyTrashed()
            ->with(['creator', 'updater'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(12);

        return view('dashboards.archived', compact('products'));
    }

    /**
     * Restore archived product
     */
    public function restore($id)
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            $product->restore();

            // Log activity
            ProductActivityLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'action' => 'restored',
                'new_values' => $product->toArray(),
                'description' => "Product '{$product->name}' was restored and returned to active inventory with {$product->quantity} units",
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product restored successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Product Restore Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while restoring the product'
            ], 500);
        }
    }

    /**
     * Get activity log for a product
     */
    public function activityLog($id)
    {
        try {
            $product = Product::findOrFail($id);

            $logs = ProductActivityLog::where('product_id', $product->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'product' => $product,
                'logs' => $logs
            ]);

        } catch (\Exception $e) {
            \Log::error('Activity Log Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching activity logs'
            ], 500);
        }
    }
}
