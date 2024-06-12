<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Include CSRF token meta tag -->
</head>
<body>
   <h1>Restaurants</h1>
   <a href="{{ route('restaurants.create') }}" style="margin-right: 40px;">Create New Restaurant</a>
    <a href="{{ route('orders.index') }}">Create Orders</a>
   
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($restaurants as $restaurant)
                <tr>
                    <td>{{ $restaurant->name }}</td>
                    <td>{{ $restaurant->address }}</td>
                    <td>{{ $restaurant->contact }}</td>
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
    <a href="#" id="logout">Logout</a> <!-- Move logout link inside body -->
       <script>
          $(document).ready(function(){
            $('#logout').click(function(e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("logout") }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response){
                        window.location.href = '{{ route("login") }}';
                    }
                });
            });
        });
          </script>
       
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
        a, button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            margin-right: 20px; /* Add spacing between buttons */
        }
        a:last-child, button:last-child {
            margin-right: 0; /* Remove margin from the last button */
        }
        a:hover, button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Add spacing between buttons and table */
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
        td a {
            color: whitesmoke;
            text-decoration: blink;
            transition: color 0.3s;
        }
        td a:hover {
            color: #0056b3;
        }
        td form {
            display: inline;
        }
        button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: blink;
            transition: background-color 0.3s, transform 0.3s;
        }
        button:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
        body {
            position: relative; /* Set body to relative position */
        }
        #logout {
            position: absolute; /* Position the logout button */
            top: 20px; /* Distance from the top */
            right: 20px; /* Distance from the right */
            padding: 5px 10px; /* Adjust padding */
            background-color: black; /* Change background color */
            color: yellow; /* Change text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Apply border radius */
            text-decoration: none; /* Remove underline */
            transition: background-color 0.3s, transform 0.3s; /* Apply transition */
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
</body>
</html>
