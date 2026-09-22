<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - IT CMS</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body style="display: flex;">
    <div class="auth-wrapper">
        <div class="auth-container">
            <h1 class="auth-title">Create an account</h1>
        <p class="auth-subtitle">Register to submit IT complaints.</p>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="text-input" value="{{ old('username') }}" required>
            </div>
            
            <div class="form-group">
                <label for="phone_number">Phone Number (for SMS updates)</label>
                <input type="text" id="phone_number" name="phone_number" class="text-input" value="{{ old('phone_number') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="text-input" required>
            </div>

            <div class="button-center-wrapper">
                <button type="submit" class="button-primary">Register</button>
            </div>
        </form>

        <a href="{{ route('login') }}" class="auth-link">Already have an account? Log in</a>
        </div>
    </div>
</body>
</html>
