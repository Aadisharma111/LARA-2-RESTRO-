<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Manager</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            padding: 20px;
            color: #333;
        }
        h1, h2 {
            text-align: center;
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
        .bill-template {
            display: none;
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
    <button type="button" id="generateBillButton">Generate Bill</button>
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
  <!-- Bill Template -->
   <template id="billTemplate" style="display: none;">
    <div class="bill-template">
        <h2>Bill Details</h2>
        <p>Restaurant: <span id="billRestaurant"></span></p>
        <p>Total Price: ₹<span id="billTotalPrice"></span></p>
        <table id="billItemsTable">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <!-- Items will be dynamically added here -->
            </tbody>
        </table>
        <button id="confirmButton">Confirm Order</button>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('restaurant').addEventListener('change', function() {
            const restaurantId = this.value;
            if (restaurantId) {
                console.log(`Restaurant selected: ${restaurantId}`);
                fetch(`/orders/food-items/${restaurantId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // Debug: Log fetched data
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

        document.getElementById('generateBillButton').addEventListener('click', function() {
            generateBill();
        });

        function updateTotalPrice() {
            const quantities = document.querySelectorAll('.quantity');
            let totalPrice = 0;
            quantities.forEach(input => {
                const price = parseFloat(input.getAttribute('data-price'));
                const quantity = parseInt(input.value, 10);
                totalPrice += price * quantity;
            });
            document.getElementById('totalPrice').value = totalPrice.toFixed(2);
            console.log(`Total price updated: ${totalPrice}`);
        }

        function generateBill() {
    const restaurantSelect = document.getElementById('restaurant');
    const selectedOption = restaurantSelect.options[restaurantSelect.selectedIndex];
    const restaurantName = selectedOption.text;
    let totalPrice = parseFloat(document.getElementById('totalPrice').value).toFixed(2); // Parse as float and format to 2 decimal places

    // Ensure jsPDF is loaded
    if (typeof window.jspdf === 'undefined' || typeof window.jspdf.jsPDF === 'undefined') {
        console.error('jsPDF library is not loaded.');
        return;
    }

    // Create a new jsPDF instance
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Add content to the PDF
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    const centerX = pageWidth / 2;
    const centerY = pageHeight / 2;

     // Add background watermark
    const watermarkText = restaurantName.toUpperCase(); // Convert to uppercase for better visibility
    const watermarkFontSize = 60;
    const watermarkColor = '#000000'; // Adjust color as needed

     // Add watermark behind bill details
     doc.setFontSize(watermarkFontSize);
    doc.setTextColor(200);
    doc.setFont('helvetica', 'italic');
    doc.text(watermarkText, centerX, centerY, { align: 'center', angle: -45 });

    // Reset text styles
    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(0);
    doc.setFillColor(0); // Reset fill color
    doc.setDrawColor(0); // Reset draw color

    // Add other content
    doc.setFont('helvetica', 'bold');
    doc.text('Restaurant Invoice', centerX, 20, null, null, 'center');
    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');
    doc.text(`Restaurant: ${restaurantName}`, 20, 30);
    doc.text(`GST Number: 1234567890`, 20, 40); // Example GST number
    doc.text(`Date: ${new Date().toLocaleDateString('en-GB')}`, 20, 50); // Format date as dd/mm/yy

    doc.setLineWidth(0.5);
    doc.line(20, 55, pageWidth - 20, 55);

    doc.setFont('helvetica', 'bold');
    doc.text(`Total Price: ₹${totalPrice}`, 20, 65);
    doc.setFont('helvetica', 'normal');
    doc.text('Items:', 20, 75);

    // Add item details
    const foodItems = document.querySelectorAll('#foodItemsTable tbody tr');
    let yOffset = 85;
    foodItems.forEach(item => {
        const itemName = item.cells[0].textContent;
        const itemPrice = item.cells[1].querySelector('input').value;
        const itemQuantity = item.cells[2].querySelector('input').value;
        const itemDetails = `${itemName} - ₹${itemPrice} x${itemQuantity}`;
        doc.text(itemDetails, 20, yOffset);
        yOffset += 10;
    });

    // Add "Thank you" message
    doc.setFont('helvetica', 'italic');
    doc.text('Thank you for your order!', centerX, yOffset + 20, null, null, 'center');

    // Generate PDF Blob
    const pdfBlob = doc.output('blob');

    // Create URL for Blob
    const pdfUrl = URL.createObjectURL(pdfBlob);

    // Open PDF preview in new window
    window.open(pdfUrl, '_blank');

    // Clean up
    URL.revokeObjectURL(pdfUrl);
            doc.line(20, yOffset, pageWidth - 20, yOffset);
            yOffset += 20;
            doc.setFontSize(12);
            doc.setFont("helvetica", "italic");
            doc.text('Thank you for your order!', centerX, yOffset, null, null, 'center');
            // Save the PDF
           // doc.save('bill.pdf');
        }
    });
</script>
</body>
</html>