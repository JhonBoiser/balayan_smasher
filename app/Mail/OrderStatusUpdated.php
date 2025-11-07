<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        $subject = 'Order Status Update - #' . $this->order->order_number;

        if ($this->newStatus === 'shipped') {
            $subject = 'Your Order Has Been Shipped! - #' . $this->order->order_number;
        } elseif ($this->newStatus === 'delivered') {
            $subject = 'Your Order Has Been Delivered! - #' . $this->order->order_number;
        }

        return $this->subject($subject)
                    ->view('emails.orders.status-updated')
                    ->with([
                        'order' => $this->order,
                        'oldStatus' => $this->oldStatus,
                        'newStatus' => $this->newStatus,
                    ]);
    }
}
