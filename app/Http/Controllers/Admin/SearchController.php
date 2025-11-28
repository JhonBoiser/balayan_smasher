<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Handle global search across products, orders, customers, and categories
     */
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');

        // Check if this is an AJAX request
        $isAjax = $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';

        // Validate query length
        if (!$query || strlen($query) < 2) {
            if ($isAjax) {
                return response()->json([
                    'products' => [],
                    'orders' => [],
                    'customers' => [],
                    'categories' => []
                ]);
            }
            return view('admin.search.global', ['results' => []]);
        }

        try {
            $results = [
                'products' => $this->searchProducts($query),
                'orders' => $this->searchOrders($query),
                'customers' => $this->searchCustomers($query),
                'categories' => $this->searchCategories($query)
            ];

            // Return JSON for AJAX requests
            if ($isAjax) {
                return response()->json($results);
            }

            return view('admin.search.global', compact('results', 'query'));

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());

            if ($isAjax) {
                return response()->json([
                    'products' => [],
                    'orders' => [],
                    'customers' => [],
                    'categories' => [],
                    'error' => 'Search temporarily unavailable'
                ], 500);
            }

            return view('admin.search.global', ['results' => [], 'error' => 'Search temporarily unavailable']);
        }
    }

    /**
     * Search products by name, SKU, description, or category
     */
    private function searchProducts($query)
    {
        return Product::with(['category', 'primaryImage'])
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
                $price = $product->price ?? 0;
                $formattedPrice = '₱' . number_format((float)$price, 2);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku ?? 'N/A',
                    'price' => $formattedPrice,
                    'category' => $product->category->name ?? 'Uncategorized',
                    'type' => 'product',
                    'url' => route('admin.products.edit', $product->id),
                    'image' => $product->primaryImage->image_path ?? '/images/placeholder-product.jpg'
                ];
            })
            ->toArray();
    }

    /**
     * Search orders by order number, ID, or customer name/email
     */
    private function searchOrders($query)
    {
        // Check if query is numeric for ID search
        $numericQuery = is_numeric($query) ? (int)$query : null;

        return Order::with('user')
            ->where(function($q) use ($query, $numericQuery) {
                $q->where('order_number', 'like', "%{$query}%");

                if ($numericQuery) {
                    $q->orWhere('id', $numericQuery);
                }
            })
            ->orWhereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function($order) {
                $total = $order->total ?? 0;
                $formattedTotal = '₱' . number_format((float)$total, 2);

                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name ?? 'Unknown Customer',
                    'total' => $formattedTotal,
                    'status' => $order->status,
                    'type' => 'order',
                    'url' => route('admin.orders.show', $order->id),
                    'created_at' => $order->created_at->format('M d, Y')
                ];
            })
            ->toArray();
    }

    /**
     * Search customers by name, email, or phone
     */
    private function searchCustomers($query)
    {
        return User::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? 'N/A',
                    'orders_count' => $user->orders()->count(),
                    'type' => 'customer',
                    'url' => route('admin.orders.index', ['customer' => $user->id]),
                    'joined_date' => $user->created_at->format('M d, Y')
                ];
            })
            ->toArray();
    }

    /**
     * Search categories by name or description
     */
    private function searchCategories($query)
    {
        return Category::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->withCount('products')
            ->limit(5)
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'products_count' => $category->products_count,
                    'type' => 'category',
                    'url' => route('admin.categories.edit', $category->id),
                    'description' => $category->description ?? 'No description'
                ];
            })
            ->toArray();
    }

    /**
     * Get recent orders for notifications
     */
    public function recentOrders(Request $request)
    {
        try {
            $orders = Order::with('user')
                ->where('created_at', '>=', now()->subHours(24))
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->user->name ?? 'Unknown Customer',
                        'total' => $order->total ?? 0,
                        'status' => $order->status,
                        'created_at' => $order->created_at->toISOString()
                    ];
                });

            return response()->json($orders);

        } catch (\Exception $e) {
            Log::error('Recent orders error: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Advanced search with filters
     */
    public function advancedSearch(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');

        // Check if this is an AJAX request
        $isAjax = $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';

        if (!$query || strlen($query) < 2) {
            if ($isAjax) {
                return response()->json([]);
            }
            return view('admin.search.advanced', ['results' => []]);
        }

        try {
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

            if ($isAjax) {
                return response()->json($results);
            }

            return view('admin.search.advanced', compact('results', 'query', 'type'));

        } catch (\Exception $e) {
            Log::error('Advanced search error: ' . $e->getMessage());

            if ($isAjax) {
                return response()->json(['error' => 'Search temporarily unavailable'], 500);
            }

            return view('admin.search.advanced', ['results' => [], 'error' => 'Search temporarily unavailable']);
        }
    }
}
