<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-box active" id="login-form">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <h2>Login</h2>
                
                @if($errors->any())
                    <div style="color: red; margin-bottom: 15px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box" id="register-form">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <h2>Register</h2>
                
                @if($errors->any())
                    <div style="color: red; margin-bottom: 15px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <select name="role" required>
                    <option value="">--Select Role--</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit">Register</button>
                <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>