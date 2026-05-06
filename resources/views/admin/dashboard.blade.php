<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Stats Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <admin-dashboard
            :total-users="{{ $total_users }}"
            :total-admins="{{ $total_admins }}"
            :total-statistics="{{ $total_statistics }}"
            :recent-users='@json($recent_users)'
            :statistics-by-sport='@json($statistics_by_sport)'
            :users-with-stats='@json($users_with_stats)'
            user-name="{{ Auth::user()->name }}"
            csrf-token="{{ csrf_token() }}"
        ></admin-dashboard>
    </div>
</body>
</html>
