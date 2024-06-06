<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
</head>
<body>
    <h1>Create Order</h1>
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
            <select name="items[]" required>
                <!-- Options will be populated dynamically using JavaScript -->
            </select>
            <button type="button" onclick="removeItem(this)">Remove</button>
        </div>
    </div>
    <button type="button" onclick="addItem()">Add Item</button>
    <button type="submit">Create Order</button>
</form>

<script>
    function addItem() {
        var itemsContainer = document.getElementById('items-container');
        var newItemDiv = document.createElement('div');
        newItemDiv.innerHTML = `
            <label for="item">Select Item:</label>
            <select name="items[]" required>
                <!-- Options will be populated dynamically using JavaScript -->
            </select>
            <button type="button" onclick="removeItem(this)">Remove</button>
        `;
        itemsContainer.appendChild(newItemDiv);

        // Fetch food items for the selected restaurant and populate the item dropdown
        var restaurantId = document.getElementById('restaurant').value;
        fetch('/orders/food-items/' + restaurantId)
            .then(response => response.json())
            .then(data => {
                var itemSelect = newItemDiv.querySelector('select');
                itemSelect.innerHTML = '';
                data.forEach(item => {
                    var option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name + ' - ₹' + item.price;
                    itemSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching food items:', error));
    }

    function removeItem(button) {
        var itemDiv = button.parentNode;
        itemDiv.parentNode.removeChild(itemDiv);
    }
</script>
          <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #1a1a1a; /* Dark background color */
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
    background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    animation: slide-in 0.5s ease-out;
}

h1 {
    color: #fff; /* White text color */
    text-align: center;
    margin-top: 0;
}

label {
    color: #fff; /* White label color */
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
    background-color: #fff; /* White background color */
    color: #333; /* Text color */
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
        <div class="container">
        <!-- Content goes here -->
        </div>
</body>
</html>
