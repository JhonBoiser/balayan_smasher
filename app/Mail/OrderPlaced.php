<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Order Confirmation - #' . $this->order->order_number)
                    ->view('emails.orders.placed')
                    ->with([
                        'order' => $this->order,
                        'items' => $this->order->items()->with('product')->get(),
                    ]);
    }
}
