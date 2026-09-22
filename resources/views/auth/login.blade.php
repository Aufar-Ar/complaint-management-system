<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT CMS</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body style="display: flex;">
    <div class="auth-wrapper">
        <div class="auth-container">
            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-subtitle">Sign in to the IT Complaint Management System.</p>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="text-input" value="{{ old('username') }}" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="text-input" required>
            </div>

            <div class="button-center-wrapper">
                <button type="submit" class="button-primary">Log in</button>
            </div>
        </form>

       <a href="{{ route('register') }}" class="auth-link">Don't have an account? Register</a>
        </div>
    </div>
</body>
</html>
