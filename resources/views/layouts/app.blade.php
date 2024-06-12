<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant-Managment')</title>
    @vite('resources/js/app.js')
    @yield('styles')
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    @vite('resources/js/app.js')
    @yield('scripts')
</body>
</html>
