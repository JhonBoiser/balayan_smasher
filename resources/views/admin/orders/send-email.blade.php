<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Update - {{ $order->order_number }}</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .email-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .email-body {
            padding: 30px;
        }

        .order-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 500;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce7ff; color: #004085; }
        .status-shipped { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .payment-paid { background: #d4edda; color: #155724; }
        .payment-pending { background: #fff3cd; color: #856404; }
        .payment-failed { background: #f8d7da; color: #721c24; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .items-table th {
            background: #667eea;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }

        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #e9ecef;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .totals-table td {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .totals-table tr:last-child td {
            border-bottom: none;
            font-weight: 700;
            font-size: 18px;
            color: #667eea;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .customer-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .customer-info h3 {
            margin-top: 0;
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .custom-message {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .custom-message h4 {
            margin-top: 0;
            color: #1976D2;
        }

        .email-footer {
            background: #2c3e50;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .footer-links {
            margin: 20px 0;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 15px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #764ba2;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .copyright {
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.8;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .email-body {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .items-table {
                font-size: 14px;
            }

            .items-table th,
            .items-table td {
                padding: 8px 10px;
            }

            .email-header {
                padding: 20px 15px;
            }

            .email-header h1 {
                font-size: 24px;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white !important;
            }

            .email-container {
                box-shadow: none !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Order Update</h1>
            <p>Order #{{ $order->order_number }}</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Greeting -->
            <h2>Hello, {{ $order->user->name }}!</h2>
            <p>We wanted to provide you with an update regarding your recent order.</p>

            <!-- Custom Message Section -->
            @if(!empty($customMessage))
            <div class="custom-message">
                <h4>📝 Special Message from Our Team</h4>
                <p>{{ $customMessage }}</p>
            </div>
            @endif

            <!-- Order Information -->
            <div class="order-info">
                <h3 style="margin-top: 0; color: #667eea;">Order Summary</h3>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Order Number</span>
                        <strong class="info-value">#{{ $order->order_number }}</strong>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Order Date</span>
                        <span class="info-value">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Order Status</span>
                        <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Payment Status</span>
                        <span class="status-badge payment-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    @if($order->tracking_number)
                    <div class="info-item">
                        <span class="info-label">Tracking Number</span>
                        <strong class="info-value">{{ $order->tracking_number }}</strong>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Total Amount</span>
                        <strong class="info-value">₱{{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <h3 style="color: #667eea;">Order Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->product && $item->product->sku)
                            <br><small style="color: #666;">SKU: {{ $item->product->sku }}</small>
                            @endif
                        </td>
                        <td>₱{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">₱{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Order Totals -->
            <table class="totals-table">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">₱{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Shipping Fee:</strong></td>
                    <td class="text-right">₱{{ number_format($order->shipping_fee, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Amount:</strong></td>
                    <td class="text-right">₱{{ number_format($order->total, 2) }}</td>
                </tr>
            </table>

            <!-- Shipping Information -->
            <div class="customer-info">
                <h3>Shipping Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <strong>Shipping Address:</strong><br>
                        {{ $order->shipping_name }}<br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_province }}<br>
                        {{ $order->shipping_zipcode }}<br>
                        📞 {{ $order->shipping_phone }}<br>
                        📧 {{ $order->shipping_email }}
                    </div>
                    <div>
                        <strong>Customer Information:</strong><br>
                        {{ $order->user->name }}<br>
                        📧 {{ $order->user->email }}<br>
                        @if($order->user->phone)
                        📞 {{ $order->user->phone }}<br>
                        @endif
                        Customer ID: #{{ str_pad($order->user->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div style="background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <h4 style="margin-top: 0; color: #1976D2;">What's Next?</h4>
                @switch($order->status)
                    @case('processing')
                        <p>Your order is currently being processed. We'll notify you once it's shipped.</p>
                        @break
                    @case('shipped')
                        <p>Your order has been shipped!
                        @if($order->tracking_number)
                            You can track your package using this number: <strong>{{ $order->tracking_number }}</strong>
                        @endif
                        </p>
                        @break
                    @case('delivered')
                        <p>Your order has been delivered. We hope you're enjoying your products!</p>
                        @break
                    @case('cancelled')
                        <p>Your order has been cancelled. If you have any questions, please contact our support team.</p>
                        @break
                    @default
                        <p>We're working on your order and will keep you updated on its progress.</p>
                @endswitch
            </div>

            <!-- Support Information -->
            <div style="text-align: center; margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #667eea;">Need Help?</h4>
                <p>If you have any questions about your order, please don't hesitate to contact our support team.</p>
                <p>
                    📧 support@yourstore.com<br>
                    📞 1-800-YOUR-STORE<br>
                    🕒 Mon-Fri 9AM-6PM
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div style="margin-bottom: 20px;">
                <h3 style="margin: 0; color: #667eea;">Your Store Name</h3>
                <p style="margin: 10px 0; opacity: 0.9;">Quality Products, Exceptional Service</p>
            </div>

            <div class="footer-links">
                <a href="{{ url('/') }}">Visit Our Store</a>


            </div>

            <div class="social-links">
                <a href="#">📘 Facebook</a>
                <a href="#">📷 Instagram</a>
                <a href="#">🐦 Twitter</a>
                <a href="#">💼 LinkedIn</a>
            </div>

            <div class="copyright">
                &copy; {{ date('Y') }} Your Store Name. All rights reserved.<br>
                <small>This email was sent to {{ $order->user->email }}. Please do not reply to this email.</small>
            </div>
        </div>
    </div>
</body>
</html>
