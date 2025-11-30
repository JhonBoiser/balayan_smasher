<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_intent_id',
        'payment_method_id',
        'payment_method',
        'amount',
        'status',
        'currency',
        'response_data',
        'error_message',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'response_data' => 'json'
    ];

    /**
     * Relationship to Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
