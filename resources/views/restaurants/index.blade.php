<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
</head>
<body>
    <h1>Restaurants</h1>
    <a href="{{ route('restaurants.create') }}">Create New Restaurant</a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($restaurants as $restaurant)
                <tr>
                    <td>{{ $restaurant->name }}</td>
                    <td>{{ $restaurant->address }}</td>
                    <td>
                        <a href="{{ route('restaurants.show', $restaurant->id) }}">View</a>
                        <a href="{{ route('restaurants.edit', $restaurant->id) }}">Edit</a>
                        <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
       <style>
        /* Style for the table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-family: Arial, sans-serif;
}

/* Style for table header */
th {
    background-color: #007bff;
    color: #fff;
    padding: 15px;
    text-align: left;
}

/* Style for table rows */
tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Style for table cells */
td {
    padding: 15px;
}

/* Style for links */
a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s;
}

a:hover {
    color: #0056b3;
}

/* Style for buttons */
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

 </style>
</body>
</html>
