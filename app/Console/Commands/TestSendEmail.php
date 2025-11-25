<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderUpdateMail;
use App\Models\Order;

class TestSendEmail extends Command
{
    protected $signature = 'email:test {order_id?}';
    protected $description = 'Send a test order email to check mail configuration';

    public function handle()
    {
        // Kunin ang order ID kung meron, else pick latest
        $orderId = $this->argument('order_id');
        $order = $orderId ? Order::find($orderId) : Order::latest()->first();

        if (!$order) {
            $this->error("❌ No orders found to send email!");
            return 1;
        }

        if (!$order->user || !$order->user->email) {
            $this->error("❌ Order has no user email!");
            return 1;
        }

        try {
            Mail::to($order->user->email)->send(new OrderUpdateMail($order));
            $this->info("✅ Email sent successfully to {$order->user->email}");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
        }

        return 0;
    }
}
