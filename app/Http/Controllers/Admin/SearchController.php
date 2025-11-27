<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Log; // Added Log facade

class SearchController extends Controller
{
    /**
     * Handle global search across products, orders, customers, and categories
     */
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');

        // Validate query length
        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'orders' => [],
                'customers' => [],
                'categories' => []
            ]);
        }

        try {
            $results = [
                'products' => $this->searchProducts($query),
                'orders' => $this->searchOrders($query),
                'customers' => $this->searchCustomers($query),
                'categories' => $this->searchCategories($query)
            ];

            return response()->json($results);

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage()); // Fixed Log reference

            return response()->json([
                'products' => [],
                'orders' => [],
                'customers' => [],
                'categories' => [],
                'error' => 'Search temporarily unavailable'
            ], 500);
        }
    }

    /**
     * Search products by name, SKU, description, or category
     */
    private function searchProducts($query)
    {
        return Product::with('category')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orWhereHas('category', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function($product) {
                // Handle null price safely
                $price = $product->price ?? 0;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => '₱' . number_format((float)$price, 2), // Fixed: cast to float
                    'category' => $product->category->name ?? 'Uncategorized',
                    'type' => 'product',
                    'url' => route('admin.products.edit', $product->id),
                    'image' => $product->primaryImage->image_path ?? null
                ];
            });
    }

    /**
     * Search orders by order number, ID, or customer name/email
     */
    private function searchOrders($query)
    {
        return Order::with('user')
            ->where(function($q) use ($query) {
                $q->where('order_number', 'like', "%{$query}%")
                  ->orWhere('id', $query);
            })
            ->orWhereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function($order) {
                // Handle null total safely
                $total = $order->total ?? 0;
                return [
                    'id' => $order->id,
                    'number' => $order->order_number,
                    'customer' => $order->user->name,
                    'amount' => '₱' . number_format((float)$total, 2), // Fixed: cast to float
                    'status' => $order->status,
                    'type' => 'order',
                    'url' => route('admin.orders.show', $order->id),
                    'status_badge' => $this->getStatusBadge($order->status)
                ];
            });
    }

    /**
     * Search customers by name, email, or phone
     */
    private function searchCustomers($query)
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? 'N/A',
                    'orders' => $user->orders()->count(),
                    'type' => 'customer',
                    'url' => route('admin.orders.index', ['customer' => $user->id]),
                    'joined_date' => $user->created_at->format('M d, Y')
                ];
            });
    }

    /**
     * Search categories by name or description
     */
    private function searchCategories($query)
    {
        return Category::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'products' => $category->products()->count(),
                    'type' => 'category',
                    'url' => route('admin.categories.edit', $category->id),
                    'description' => $category->description
                ];
            });
    }

    /**
     * Get CSS class for order status badge
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => 'status-pending',
            'processing' => 'status-processing',
            'shipped' => 'status-completed',
            'delivered' => 'status-completed',
            'cancelled' => 'status-cancelled'
        ];

        return $badges[$status] ?? 'status-pending';
    }

    /**
     * Advanced search with filters
     */
    public function advancedSearch(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all'); // all, products, orders, customers, categories

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        switch ($type) {
            case 'products':
                $results = $this->searchProducts($query);
                break;
            case 'orders':
                $results = $this->searchOrders($query);
                break;
            case 'customers':
                $results = $this->searchCustomers($query);
                break;
            case 'categories':
                $results = $this->searchCategories($query);
                break;
            default:
                $results = [
                    'products' => $this->searchProducts($query),
                    'orders' => $this->searchOrders($query),
                    'customers' => $this->searchCustomers($query),
                    'categories' => $this->searchCategories($query)
                ];
        }

        return response()->json($results);
    }
}
