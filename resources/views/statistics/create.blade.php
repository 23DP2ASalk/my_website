<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add Statistics - Stats Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <statistics-create
            :sport-types='@json($sportTypes)'
            user-name="{{ Auth::user()->name }}"
            user-role="{{ Auth::user()->role }}"
            csrf-token="{{ csrf_token() }}"
        ></statistics-create>
    </div>
</body>
</html>
