<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Admin Login</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    @php
        $webSetting = \App\Models\WebSetting::first();
    @endphp

    <div class="blob"></div>
    <div class="blob"></div>

    <div class="login-wrapper">
        <div class="login-card reveal @if($errors->any()) shake @endif">
            <div class="login-header">
                <div class="logo">
                    <h1 class="neon-text">{{ $webSetting->title }}</h1>
                </div>
                <p>Welcome back, please login to your account</p>
            </div>

            <form id="loginForm" method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <div class="input-field">
                        <i class='bx bx-envelope'></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                               placeholder="admin@corewar.com" required autofocus>
                    </div>
                    @error('email')
                        <span class="error-msg" style="color: #ff3e1d; font-size: 11px; margin-top: 5px; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-field">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <i class='bx bx-hide toggle-password' id="togglePassword"></i>
                    </div>
                    @error('password')
                        <span class="error-msg" style="color: #ff3e1d; font-size: 11px; margin-top: 5px; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        Remember Me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="btn-text">Sign In</span>
                    <div class="loader" id="btnLoader"></div>
                </button>
            </form>

            <div class="login-footer">
                <p>Don't have access? <a href="#">Contact Superadmin</a></p>
            </div>
        </div>
    </div>

    <div id="notification-area">
        @if (session('status'))
            <div class="notif success">
                <i class='bx bx-check-circle' style="color: var(--primary); font-size: 20px;"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>