<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Order</title>
</head>
<body>
    <h1>Order Details</h1>
    <p><strong>ID:</strong> {{ $order->id }}</p>
    <p><strong>Restaurant:</strong> {{ $order->restaurant->name }}</p>
    <p><strong>Total Price:</strong> {{ $order->total_price }}</p>
    <h2>Items:</h2>
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->name }} - ₹{{ $item->price }}</li>
        @endforeach
    </ul>
</body>
</html>
