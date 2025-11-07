<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #e74c3c; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .order-details { background: white; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .item { padding: 10px; border-bottom: 1px solid #eee; }
        .total { font-size: 18px; font-weight: bold; color: #e74c3c; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
        .btn { background: #e74c3c; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
        </div>

        <div class="content">
            <h2>Thank you for your order!</h2>
            <p>Hi {{ $order->shipping_name }},</p>
            <p>We've received your order and it's being processed. Here are your order details:</p>

            <div class="order-details">
                <h3>Order #{{ $order->order_number }}</h3>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>

                <h4>Order Items:</h4>
                @foreach($items as $item)
                <div class="item">
                    <strong>{{ $item->product_name }}</strong> x{{ $item->quantity }}<br>
                    <span style="color: #666;">₱{{ number_format($item->price, 2) }} each</span>
                    <span style="float: right;">₱{{ number_format($item->subtotal, 2) }}</span>
                </div>
                @endforeach

                <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #e74c3c;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Shipping:</span>
                        <span>₱{{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <span class="total">Total:</span>
                        <span class="total">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="order-details">
                <h4>Shipping Address:</h4>
                <p>
                    {{ $order->shipping_name }}<br>
                    {{ $order->shipping_phone }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_zipcode }}
                </p>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('orders.show', $order->id) }}" class="btn">View Order Details</a>
            </p>

            <p>If you have any questions, please contact us at +63 906 623 8257 or reply to this email.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Balayan Smashers Hub. All rights reserved.</p>
            <p>Calzada, Ermita, Balayan, Batangas</p>
        </div>
    </div>
</body>
</html>
