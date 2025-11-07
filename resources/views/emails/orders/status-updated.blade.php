<?php
// ============================================
// resources/views/emails/orders/status-updated.blade.php
// ============================================
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3498db; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .status-badge { display: inline-block; padding: 8px 15px; border-radius: 20px; font-weight: bold; }
        .status-processing { background: #3498db; color: white; }
        .status-shipped { background: #9b59b6; color: white; }
        .status-delivered { background: #27ae60; color: white; }
        .order-details { background: white; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
        .btn { background: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Status Update</h1>
        </div>

        <div class="content">
            <h2>Your order status has been updated</h2>
            <p>Hi {{ $order->shipping_name }},</p>
            <p>Your order #{{ $order->order_number }} status has been updated.</p>

            <div class="order-details" style="text-align: center;">
                <p>
                    <span class="status-badge status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span>
                    <span style="margin: 0 10px;">→</span>
                    <span class="status-badge status-{{ $newStatus }}">{{ ucfirst($newStatus) }}</span>
                </p>

                @if($newStatus === 'processing')
                    <h3>Your order is being prepared</h3>
                    <p>We're getting your items ready for shipment. You'll receive another email once your order ships.</p>
                @elseif($newStatus === 'shipped')
                    <h3>Your order is on its way!</h3>
                    <p>Your package has been shipped and is on its way to you. Expected delivery within 3-5 business days.</p>
                @elseif($newStatus === 'delivered')
                    <h3>Your order has been delivered!</h3>
                    <p>We hope you enjoy your purchase. If you have any issues, please contact us.</p>
                @endif
            </div>

            <p style="text-align: center;">
                <a href="{{ route('orders.show', $order->id) }}" class="btn">Track Your Order</a>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Balayan Smashers Hub. All rights reserved.</p>
            <p>Questions? Call us at +63 906 623 8257</p>
        </div>
    </div>
</body>
</html>
