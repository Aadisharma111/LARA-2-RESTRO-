<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            padding: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
            animation: fadeIn 1s ease-in-out;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        select, input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            animation: slideInUp 1s ease-in-out;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
<h1>Order Manager</h1>
<h2>Create Order</h2>
<form id="orderForm" action="{{ route('orders.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="restaurant">Select Restaurant:</label>
        <select id="restaurant" name="restaurant_id" required>
            <option value="">Select a restaurant</option>
            @foreach ($restaurants as $restaurant)
                <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="foodItems">Select Food Items:</label>
        <table id="foodItemsTable">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <!-- Food items will be loaded here via JavaScript -->
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label for="totalPrice">Total Price:</label>
        <input type="text" id="totalPrice" name="total_price" readonly>
    </div>
    <button type="submit">Generate Bill</button>
</form>
<h2>Existing Orders</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Restaurant</th>
            <th>Items</th>
            <th>Total Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->restaurant->name }}</td>
                <td>
                    @foreach ($order->items as $item)
                        {{ $item->name }} (x{{ $item->pivot->quantity }})<br>
                    @endforeach
                </td>
                <td>{{ $order->total_price }}</td>
                <td>
                    <!-- Actions such as edit or delete can be added here -->
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    document.getElementById('restaurant').addEventListener('change', function() {
        const restaurantId = this.value;
        if (restaurantId) {
            fetch(`/orders/food-items/${restaurantId}`)
                .then(response => response.json())
                .then(data => {
                    const foodItemsTable = document.getElementById('foodItemsTable').getElementsByTagName('tbody')[0];
                    foodItemsTable.innerHTML = '';
                    data.forEach(item => {
                        const row = foodItemsTable.insertRow();
                        row.innerHTML = `
                            <td>${item.name}</td>
                            <td>
                                <input type="text" name="items[${item.id}][price]" value="${item.price}" readonly>
                            </td>
                            <td>
                                <input type="number" name="items[${item.id}][quantity]" data-price="${item.price}" value="1" min="1" class="quantity">
                                <input type="hidden" name="items[${item.id}][name]" value="${item.name}">
                            </td>
                        `;
                    });
                    updateTotalPrice();
                });
        }
    });

    document.getElementById('foodItemsTable').addEventListener('input', function(event) {
        if (event.target.classList.contains('quantity')) {
            updateTotalPrice();
        }
    });

    function updateTotalPrice() {
        const quantities = document.querySelectorAll('.quantity');
        let totalPrice = 0;
        quantities.forEach(input => {
            const price = input.getAttribute('data-price');
            const quantity = input.value;
            totalPrice += price * quantity;
        });
        document.getElementById('totalPrice').value = totalPrice.toFixed(2);
    }

    document.getElementById('orderForm').addEventListener('submit', function(event) {
        event.preventDefault();
        generateBill();
        this.submit();
    });

    function generateBill() {
        const restaurantName = document.getElementById('restaurant').options[document.getElementById('restaurant').selectedIndex].text;
        const totalPrice = document.getElementById('totalPrice').value;
        const billTemplate = document.getElementById('billTemplate');

        if (billTemplate) {
            const clonedTemplate = billTemplate.cloneNode(true);
            clonedTemplate.style.display = 'block';
            clonedTemplate.querySelector('#billRestaurant').textContent = restaurantName;
            clonedTemplate.querySelector('#billTotalPrice').textContent = totalPrice;

            const foodItemsTable = clonedTemplate.querySelector('#billItemsTable tbody');
            const foodItems = document.querySelectorAll('#foodItemsTable tbody tr');
            foodItems.forEach(item => {
                const itemName = item.cells[0].textContent;
                const itemPrice = item.cells[1].querySelector('input').value;
                const itemQuantity = item.cells[2].querySelector('input').value;
                const row = foodItemsTable.insertRow();
                row.innerHTML = `
                    <td>${itemName}</td>
                    <td>${itemPrice}</td>
                    <td>${itemQuantity}</td>
                `;
            });

            document.body.appendChild(clonedTemplate);
        } else {
            console.error('Bill template not found.');
        }
    }
</script>
</body>
</html>
