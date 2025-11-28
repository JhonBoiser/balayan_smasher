<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'status', 'subtotal', 'shipping_fee',
        'total', 'payment_method', 'payment_status', 'shipping_name',
        'shipping_email', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_province', 'shipping_zipcode', 'notes',
        'cancelled_at', 'cancellation_reason', 'cancelled_by'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest Customer',
            'email' => 'guest@example.com'
        ]);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class)->with('product.primaryImage');
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']) &&
               $this->payment_status !== 'paid';
    }

    /**
     * Check if order is cancelled
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Cancel the order
     */
    public function cancel($reason = null, $cancelledBy = 'customer')
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
            'cancelled_by' => $cancelledBy
        ]);

        return $this;
    }

    /**
     * Get readable cancellation reason
     */
    public function getReadableCancellationReasonAttribute()
    {
        if (!$this->cancellation_reason) {
            return 'No reason provided';
        }

        $reasons = [
            'changed_mind' => 'Changed my mind',
            'found_cheaper' => 'Found cheaper elsewhere',
            'shipping_issues' => 'Shipping issues',
            'product_unavailable' => 'Product became unavailable',
            'payment_issues' => 'Payment issues',
            'other' => 'Other reasons'
        ];

        return $reasons[$this->cancellation_reason] ?? $this->cancellation_reason;
    }

    /**
     * Scope for cancelled orders
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for active orders (not cancelled)
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    /**
     * Scope for cancellable orders
     */
    public function scopeCancellable($query)
    {
        return $query->whereIn('status', ['pending', 'processing'])
                    ->where('payment_status', '!=', 'paid');
    }
}
