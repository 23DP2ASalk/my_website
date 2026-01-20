<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Stats Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <dashboard
            user-name="{{ Auth::user()->name }}"
            user-role="{{ Auth::user()->role }}"
            logout-route="{{ route('logout') }}"
            news-api-key="993171fccd68402289dfb60b73b35149"
        ></dashboard>
    </div>
</body>
</html>