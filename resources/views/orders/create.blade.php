<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
</head>
<body>
    @if ($errors->any())
        <div>
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div>
            <label for="restaurant">Select Restaurant:</label>
            <select id="restaurant" name="restaurant_id" required>
                <option value="">Select Restaurant</option>
                @foreach ($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                @endforeach
            </select>
        </div>
        <div id="items-container">
            <!-- Initially, only one item select field is shown -->
            <div class="item">
                <label for="item">Select Item:</label>
                <select name="items[0][id]" required onchange="updatePrice(this)">
                    <option value="">Select Item</option>
                    <!-- Options will be populated dynamically using JavaScript -->
                </select>
                <label for="quantity">Quantity:</label>
                <input type="number" name="items[0][quantity]" value="1" min="1" required>
                <label for="price">Price:</label>
                <input type="number" name="items[0][price]" step="0.01" required readonly>
                <button type="button" onclick="removeItem(this)">Remove</button>
            </div>
        </div>
        <button type="button" onclick="addItem()">Add Item</button>
        <button type="button" onclick="addManualItem()">Add Manual Item</button>
        <button type="submit">Create Order</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const restaurantSelect = document.getElementById('restaurant');
            const itemsContainer = document.getElementById('items-container');
            let foodItems = [];

            restaurantSelect.addEventListener('change', function() {
                fetchItemsForRestaurant(this.value);
            });

            function fetchItemsForRestaurant(restaurantId) {
                if (!restaurantId) return;
                
                fetch(`/orders/food-items/${restaurantId}`)
                    .then(response => response.json())
                    .then(data => {
                        foodItems = data;
                        populateAllItemSelects(data);
                    })
                    .catch(error => console.error('Error fetching food items:', error));
            }

            function populateAllItemSelects(items) {
                const itemSelects = document.querySelectorAll('select[name^="items["]');
                itemSelects.forEach(select => {
                    populateItemSelect(select, items);
                });
            }

            function populateItemSelect(select, items) {
                select.innerHTML = '<option value="">Select Item</option>';
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.name} - ₹${item.price}`;
                    select.appendChild(option);
                });
            }

            window.updatePrice = function(select) {
                const selectedItem = foodItems.find(item => item.id == select.value);
                const priceInput = select.parentNode.querySelector('input[name$="[price]"]');
                if (selectedItem) {
                    priceInput.value = selectedItem.price;
                } else {
                    priceInput.value = '';
                }
            };

            window.addItem = function() {
                const index = document.querySelectorAll('.item').length;
                const newItemDiv = document.createElement('div');
                newItemDiv.className = 'item';
                newItemDiv.innerHTML = `
                    <label for="item">Select Item:</label>
                    <select name="items[${index}][id]" required onchange="updatePrice(this)">
                        <option value="">Select Item</option>
                    </select>
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="items[${index}][quantity]" value="1" min="1" required>
                    <label for="price">Price:</label>
                    <input type="number" name="items[${index}][price]" step="0.01" required readonly>
                    <button type="button" onclick="removeItem(this)">Remove</button>
                `;
                itemsContainer.appendChild(newItemDiv);
                const restaurantId = restaurantSelect.value;
                fetchItemsForRestaurant(restaurantId);
            }

            window.addManualItem = function() {
                const index = document.querySelectorAll('.item').length;
                const manualItemName = prompt('Enter the item name:');
                const manualItemPrice = prompt('Enter the item price:');

                if (manualItemName && manualItemPrice) {
                    const newItemDiv = document.createElement('div');
                    newItemDiv.className = 'item';
                    newItemDiv.innerHTML = `
                        <label for="item">Manual Item:</label>
                        <input type="text" name="items[${index}][name]" value="${manualItemName}" readonly required>
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="items[${index}][quantity]" value="1" min="1" required>
                        <label for="price">Price:</label>
                        <input type="number" name="items[${index}][price]" value="${manualItemPrice}" step="0.01" required>
                        <button type="button" onclick="removeItem(this)">Remove</button>
                    `;
                    itemsContainer.appendChild(newItemDiv);
                }
            }

            window.removeItem = function(button) {
                const itemDiv = button.parentNode;
                itemDiv.parentNode.removeChild(itemDiv);
            }
            // Initial fetch for the default selected restaurant (if any)
            if (restaurantSelect.value) {
                fetchItemsForRestaurant(restaurantSelect.value);
            }
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            background-image: url('https://t3.ftcdn.net/jpg/06/13/98/06/360_F_613980668_FJHE8IaDpJRg9pWw2cHQZhWRnDygixnR.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            animation: slide-in 0.5s ease-out;
        }

        h1 {
            color: #fff;
            text-align: center;
            margin-top: 0;
        }

        label {
            color: #fff;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
            background-color: #fff;
            color: #333;
        }

        select:hover,
        input[type="number"]:hover {
            border-color: #007bff;
        }

        select:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 5px;
            animation: slide-down 0.3s ease-out;
        }

        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
