<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'specifications',
        'price', 'sale_price', 'sku', 'stock', 'low_stock_threshold',
        'is_featured', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentPrice()
    {
        return $this->sale_price ?? $this->price;
    }

    public function isOnSale()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function isLowStock()
    {
        return $this->stock <= $this->low_stock_threshold && $this->stock > 0;
    }
}
