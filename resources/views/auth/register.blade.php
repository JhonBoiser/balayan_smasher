<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Balayan Smashers Hub</title>
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

        .register-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 45px 16px 32px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 36px 32px;
            border-radius: 18px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 450px;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
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

        .password-strength {
            margin-top: 6px;
            height: 3px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            display: none;
        }

        .password-strength.active {
            display: block;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .password-strength-bar.weak {
            width: 33%;
            background: #ff4444;
        }

        .password-strength-bar.medium {
            width: 66%;
            background: #ffa500;
        }

        .password-strength-bar.strong {
            width: 100%;
            background: #6ba932;
        }

        .terms-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            margin-bottom: 16px;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .terms-checkbox input[type="checkbox"] {
            margin-top: 2px;
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #6ba932;
            flex-shrink: 0;
        }

        .terms-text {
            font-size: 12px;
            color: #555;
            line-height: 1.5;
        }

        .terms-text a {
            color: #6ba932;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .terms-text a:hover {
            color: #5a9028;
            text-decoration: underline;
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 169, 50, 0.4);
            background: linear-gradient(135deg, #5a9028 0%, #4a7820 100%);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
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

        .signin-section {
            text-align: center;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 16px;
        }

        .signin-section p {
            margin: 0;
            color: #666;
            font-size: 13px;
        }

        .signin-section a {
            color: #6ba932;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .signin-section a:hover {
            color: #5a9028;
            text-decoration: underline;
        }

        /* Success Message */
        .success-message {
            background: #f0fff4;
            border: 2px solid #68d391;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success-message h4 {
            color: #2d7d32;
            margin: 0 0 8px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Error Messages */
        .error-messages {
            background: #fff5f5;
            border: 2px solid #ff4444;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .error-messages h4 {
            color: #ff4444;
            margin: 0 0 10px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-messages ul {
            margin: 0;
            padding-left: 20px;
            color: #cc0000;
        }

        .error-messages li {
            margin-bottom: 5px;
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
            .register-wrapper {
                padding: 32px 12px 24px;
            }

            .register-container {
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

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-control {
                padding: 11px 13px;
                font-size: 13px;
            }

            .btn-register {
                padding: 12px;
                font-size: 14px;
            }

            .form-label {
                font-size: 12px;
            }

            .terms-text {
                font-size: 11px;
            }
        }

        @media (max-width: 400px) {
            .register-container {
                padding: 24px 16px;
            }

            .brand-title {
                font-size: 19px;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-container">
            <!-- Brand Section -->
            <div class="brand-section">
                <img src="https://tse1.mm.bing.net/th/id/OIP.iyU99v5mL6DEKe2bKcn8kAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Balayan Smashers Hub" class="brand-logo">
                <h1 class="brand-title">Create Account</h1>
                <p class="brand-subtitle">Join Balayan Smashers Hub today</p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="success-message">
                    <h4>
                        <i class="fas fa-check-circle"></i>
                        {{ session('status') }}
                    </h4>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-messages">
                    <h4>
                        <i class="fas fa-exclamation-triangle"></i>
                        Please fix the following errors:
                    </h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name Field -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your full name">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email address">
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
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Create a strong password" style="padding-right: 45px;" oninput="checkPasswordStrength()">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="password-strength" id="passwordStrength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                    <div class="password-wrapper">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter your password" style="padding-right: 45px;">
                        <button type="button" class="password-toggle" onclick="togglePassword('password-confirm', 'toggleIcon2')">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="terms-box">
                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms" class="terms-text">
                            I agree to Balayan Smashers Hub's <a href="#" target="_blank">Privacy Policy</a> and <a href="#" target="_blank">Terms of Service</a>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus me-2"></i>{{ __('Create Account') }}
                </button>

                <div class="divider">
                    <span>OR</span>
                </div>

                <!-- Login Link -->
                <div class="signin-section">
                    <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthContainer = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthContainer.classList.remove('active');
                return;
            }

            strengthContainer.classList.add('active');

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthBar.className = 'password-strength-bar';

            if (strength <= 2) {
                strengthBar.classList.add('weak');
            } else if (strength === 3) {
                strengthBar.classList.add('medium');
            } else {
                strengthBar.classList.add('strong');
            }
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password-confirm').value;
            const termsCheckbox = document.getElementById('terms');

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }

            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy.');
                return false;
            }
        });

        // Add animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const registerCyontainer = document.querySelector('.register-container');
            registerContainer.style.animation = 'slideUp 0.6s ease';
        });
    </script>
</body>
</html>
