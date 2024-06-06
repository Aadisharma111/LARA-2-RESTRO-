<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Details</title>
</head>
<body>
    <h1>Restaurant Details</h1>
    <p>Name: {{ $restaurant->name }}</p>
    <p>Address: {{ $restaurant->address }}</p>
    <p>Contact: {{ $restaurant->contact }}</p>
    <!-- Add more details here as needed -->
</body>
</html>
