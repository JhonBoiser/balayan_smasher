<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Balayan Smashers Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('https://th.bing.com/th/id/R.51f15701309e54aa15d2371e89263c1a?rik=sYtWXHPPSExZHg&riu=http%3a%2f%2fwww.publicdomainpictures.net%2fpictures%2f210000%2fvelka%2fshuttlecocks-and-badminton-racket.jpg&ehk=gW3YLLv5b0CRD5iea7AxRode%2b666PuGjx2HZDqFa6aE%3d&risl=&pid=ImgRaw&r=0');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.15;
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 45px 16px 32px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 36px 32px;
            border-radius: 18px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 420px;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-section {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            margin-bottom: 12px;
            animation: float 3s ease-in-out infinite;
            object-fit: cover;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .brand-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-weight: 600;
            font-size: 13px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #1a1a1a;
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-control:hover {
            border-color: #6ba932;
            background: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #6ba932;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(107, 169, 50, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background: #fff;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 7px;
            color: #666;
            transition: all 0.3s ease;
            border-radius: 6px;
        }

        .password-toggle:hover {
            color: #6ba932;
            background: rgba(107, 169, 50, 0.1);
        }

        .password-toggle i {
            font-size: 16px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-check {
            margin-bottom: 0;
        }

        .form-check-input {
            margin-right: 8px;
        }

        .form-check-input:checked {
            background-color: #6ba932;
            border-color: #6ba932;
        }

        .form-check-label {
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        .forgot-password a {
            color: #6ba932;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-password a:hover {
            color: #5a9028;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            box-shadow: 0 3px 12px rgba(107, 169, 50, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 169, 50, 0.4);
            background: linear-gradient(135deg, #5a9028 0%, #4a7820 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #999;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .divider span {
            padding: 0 12px;
            font-weight: 600;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 1px solid #e0e0e0;
            color: #666;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .social-btn.google:hover {
            color: #db4437;
            border-color: #db4437;
        }

        .social-btn.facebook:hover {
            color: #4267B2;
            border-color: #4267B2;
        }

        .social-btn.twitter:hover {
            color: #1DA1F2;
            border-color: #1DA1F2;
        }

        .signup-section {
            text-align: center;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 16px;
        }

        .signup-section p {
            margin: 0;
            color: #666;
            font-size: 13px;
        }

        .signup-section a {
            color: #6ba932;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .signup-section a:hover {
            color: #5a9028;
            text-decoration: underline;
        }

        .invalid-feedback {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #dc3545;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-wrapper {
                padding: 32px 12px 24px;
            }

            .login-container {
                padding: 28px 20px;
                border-radius: 14px;
                max-width: 100%;
            }

            .brand-logo {
                width: 72px;
                height: 72px;
            }

            .brand-title {
                font-size: 21px;
            }

            .brand-subtitle {
                font-size: 12px;
            }

            .form-control {
                padding: 11px 13px;
                font-size: 13px;
            }

            .btn-login {
                padding: 12px;
                font-size: 14px;
            }

            .form-label {
                font-size: 12px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .forgot-password {
                align-self: flex-end;
            }
        }

        @media (max-width: 400px) {
            .login-container {
                padding: 24px 16px;
            }

            .brand-title {
                font-size: 19px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Brand Section -->
            <div class="brand-section">
                <img src="https://tse1.mm.bing.net/th/id/OIP.iyU99v5mL6DEKe2bKcn8kAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Balayan Smashers Hub" class="brand-logo">
                <h1 class="brand-title">Welcome Back</h1>
                <p class="brand-subtitle">Sign in to continue to your account</p>
            </div>

            <!-- Social Login Options -->
            <div class="social-login">
                <a href="#" class="social-btn google">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-btn twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>

            <div class="divider">
                <span>OR</span>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="password-wrapper">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password" style="padding-right: 45px;">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('Sign In') }}
                </button>
            </form>

            <!-- Register Link -->
            <div class="signup-section">
                <p>Don't have an account? <a href="{{ route('register') }}">Create Account</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const loginContainer = document.querySelector('.login-container');
            loginContainer.style.animation = 'slideUp 0.6s ease';
        });
    </script>
</body>
</html>
