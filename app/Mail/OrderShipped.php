<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $trackingNumber;

    public function __construct(Order $order, $trackingNumber = null)
    {
        $this->order = $order;
        $this->trackingNumber = $trackingNumber;
    }

    public function build()
    {
        return $this->subject('Your Order Has Shipped! - #' . $this->order->order_number)
                    ->view('emails.orders.shipped')
                    ->with([
                        'order' => $this->order,
                        'trackingNumber' => $this->trackingNumber,
                    ]);
    }
}
