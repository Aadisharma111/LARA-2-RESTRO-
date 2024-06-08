<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant-Managment')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Adjust the path as necessary -->
    @yield('styles')
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    <script src="{{ asset('js/app.js') }}"></script> <!-- Adjust the path as necessary -->
    @yield('scripts')
</body>
</html>
