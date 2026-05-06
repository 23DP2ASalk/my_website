<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <admin-users
            :users='@json($users)'
            user-name="{{ Auth::user()->name }}"
            :current-user-id="{{ Auth::user()->id }}"
            csrf-token="{{ csrf_token() }}"
        ></admin-users>
    </div>
</body>
</html>
