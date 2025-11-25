@component('mail::message')
# Order Update

Hello {{ $order->user->name }},

Your order **#{{ $order->order_number }}** status is now **{{ ucfirst($order->status) }}**.

**Total:** ₱{{ number_format($order->total, 2) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
