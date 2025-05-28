<p>Hi {{ $order->user->name }},</p>

<p>Your order <strong>#{{ $order->invoice_number }}</strong> has been successfully scheduled for pickup.</p>

<p><strong>AWB / Resi Number:</strong> {{ $awb }}</p>

<p><a href="{{ url('/orders/' . $order->invoice_number . '/detail#track-order') }}">Track your pickup here</a>.</p>
