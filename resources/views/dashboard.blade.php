<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <div style="background: #fff; padding: 30px; border-radius: 10px; text-align: center;">
            <h2>Welcome, {{ Auth::user()->name }}!</h2>
            <p>Role: {{ Auth::user()->role }}</p>
            <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>