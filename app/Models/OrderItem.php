<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'subtotal'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method for automatic calculations
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate subtotal before saving
        static::saving(function ($orderItem) {
            if ($orderItem->isDirty(['price', 'quantity'])) {
                $orderItem->subtotal = $orderItem->calculateSubtotal();
            }
        });

        // Auto-calculate subtotal when creating
        static::creating(function ($orderItem) {
            if (empty($orderItem->subtotal) && $orderItem->price && $orderItem->quantity) {
                $orderItem->subtotal = $orderItem->calculateSubtotal();
            }

            // Ensure product_name is set if not provided
            if (empty($orderItem->product_name) && $orderItem->product) {
                $orderItem->product_name = $orderItem->product->name;
            }
        });
    }

    /**
     * Relationship with the order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship with the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name' => 'Product Not Available',
            'price' => 0,
            'primaryImage' => null,
        ]);
    }

    /**
     * Calculate the subtotal for this order item
     */
    public function calculateSubtotal()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->subtotal, 2);
    }

    /**
     * Check if the product is still available
     */
    public function getIsProductAvailableAttribute()
    {
        return $this->product && $this->product->exists;
    }

    /**
     * Get product image URL
     */
    public function getProductImageUrlAttribute()
    {
        if ($this->product && $this->product->primaryImage) {
            return asset('storage/' . $this->product->primaryImage->image_path);
        }

        return 'https://via.placeholder.com/300x300?text=No+Image';
    }

    /**
     * Scope for items with available products
     */
    public function scopeWithAvailableProducts($query)
    {
        return $query->whereHas('product');
    }

    /**
     * Scope for items from specific order
     */
    public function scopeFromOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /**
     * Update quantity and recalculate subtotal
     */
    public function updateQuantity($newQuantity)
    {
        $this->quantity = $newQuantity;
        $this->subtotal = $this->calculateSubtotal();
        return $this->save();
    }

    /**
     * Update price and recalculate subtotal
     */
    public function updatePrice($newPrice)
    {
        $this->price = $newPrice;
        $this->subtotal = $this->calculateSubtotal();
        return $this->save();
    }

    /**
     * Get item details as array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'formatted_price' => $this->formatted_price,
            'formatted_subtotal' => $this->formatted_subtotal,
            'is_product_available' => $this->is_product_available,
            'product_image_url' => $this->product_image_url,
        ]);
    }
}
