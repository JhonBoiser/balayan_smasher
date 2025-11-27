<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Get the display image for the product
     */
    public function getDisplayImage()
    {
        if ($this->primaryImage) {
            return $this->primaryImage;
        }

        return $this->images->first();
    }

    /**
     * Get the display image URL for the product
     */
    public function getDisplayImageUrl()
    {
        $image = $this->getDisplayImage();

        if ($image) {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($image->image_path)) {
                return Storage::url($image->image_path);
            }

            // Fallback to asset helper
            return asset('storage/' . $image->image_path);
        }

        return 'https://via.placeholder.com/300x300?text=No+Image';
    }

    /**
     * Get image URL for a specific product image
     */
    public function getImageUrl($imagePath)
    {
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::url($imagePath);
        }

        return asset('storage/' . $imagePath);
    }

    /**
     * Check if product has any images
     */
    public function hasImages()
    {
        return $this->images->count() > 0;
    }
}
